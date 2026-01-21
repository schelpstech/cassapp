<?php
// Include core initialization (Model, DB, etc.)
include './query.php';

// Retrieve headers and body
$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$timestamp = (int) ($_SERVER['HTTP_X_WEBHOOK_TIMESTAMP'] ?? 0);
$eventName = $_SERVER['HTTP_X_WEBHOOK_EVENT'] ?? '';

// 1. Validate Timestamp (Replay Attack Protection)
$now          = (int) round(microtime(true) * 1000);
$allowedSkew  = 5 * 60 * 1000; // 5 minutes

if (abs($now - $timestamp) > $allowedSkew) {
    http_response_code(400);
    exit('Invalid timestamp');
}

// 2. Validate Signature
$expectedSignature = hash_hmac('sha256', $payload, $secretKey);
$cleanSignature    = preg_replace('/^sha256=/', '', $signature);

if (!hash_equals($expectedSignature, $cleanSignature)) {
    http_response_code(401);
    exit('Invalid signature');
}

// 3. Parse Data
$data = json_decode($payload, true);

$user->recordLog(
    "inpay_webhook",
    'Webhook Received',
    "Webhook event received from iNPAY.
    Event: {$eventName}.
    Timestamp: {$timestamp}."
);

if (json_last_error() !== JSON_ERROR_NONE || empty($data['event'])) {
    http_response_code(400);

    $user->recordLog(
        "inpay_webhook",
        'Webhook Signature Failed',
        "Invalid webhook signature detected.
        Event: {$eventName}.
        IP Address: {$_SERVER['REMOTE_ADDR']}."
    );

    exit('Malformed event');
}

// Check if event is completed
if (strpos($data['event'], '.completed') !== false) {

    $transaction = $data['data'] ?? [];
    $reference   = $transaction['reference'] ?? null;

    // Metadata
    $metadata = $transaction['metadata'] ?? [];
    if (is_string($metadata)) {
        $decodedMeta = json_decode($metadata, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $metadata = $decodedMeta;
        }
    }

    if (!$reference && isset($data['data']['reference'])) {
        $reference = $data['data']['reference'];
    }

    if ($reference) {
        try {
            $verifiedData = $inpay->verifyTransaction($reference);

            if ($verifiedData['status'] === 'completed') {

                $amountPaid = $verifiedData['amount'] / 100;

                $user->recordLog(
                    $initiatorID ?? "inpay_webhook",
                    'Webhook Payment Verified',
                    "Payment verified via webhook double-check.
                    Transaction Reference: {$reference}.
                    Amount Paid: ₦{$amountPaid}.
                    Source: Webhook."
                );

                // Update transaction
                $tblName   = 'tbl_transaction';
                $transData = [
                    'transAmount' => $utility->inputEncode($amountPaid),
                    'transStatus' => 1,
                    'transDate'   => date('Y-m-d')
                ];
                $condition = ['transactionRef' => $reference];

                $model->upDate($tblName, $transData, $condition);

                // Fetch transaction details
                $conditions   = ['return_type' => 'single', 'where' => ['transactionRef' => $reference]];
                $transDetails = $model->getRows($tblName, $conditions);

                if ($transDetails) {

                    $initiatorUser   = $transDetails['transInitiator'];
                    $userConditions  = ['return_type' => 'single', 'where' => ['user_name' => $initiatorUser]];
                    $userDetails     = $model->getRows('book_of_life', $userConditions);

                    $initiatorID = 0;
                    if ($userDetails) {
                        $consultantConditions = ['return_type' => 'single', 'where' => ['userid' => $userDetails['userid']]];
                        $consultantData       = $model->getRows('tbl_consultantdetails', $consultantConditions);

                        if ($consultantData) {
                            $initiatorID = $consultantData['userId'];
                        }
                    }

                    if ($initiatorID > 0) {

                        $tblNameRem  = 'tbl_remittance';
                        $updateData  = [];
                        $conditionRem = [];

                        switch ($transDetails['transactionType']) {

                            case 'Individual School':
                                $clearedSchool = json_decode($transDetails['transSchoolCode'], true);
                                $schoolCode    = $clearedSchool[0];

                                $updateData   = ['clearanceStatus' => 200, 'clearanceDate' => date('Y-m-d')];
                                $conditionRem = ['recordSchoolCode' => $schoolCode];

                                $user->recordLog(
                                    $initiatorID ?? "inpay_webhook",
                                    'Webhook Clearance Updated',
                                    "Clearance completed via webhook for Centre Number: {$schoolCode}.
                                    Transaction Reference: {$reference}.
                                    Payment Type: Individual School."
                                );
                                break;

                            case 'Additional Candidate':
                                $clearedSchool = json_decode($transDetails['transSchoolCode'], true);
                                $schoolCode    = $clearedSchool[0];

                                $schoolConditions   = ['where' => ['centreNumber' => $schoolCode], 'return_type' => 'single'];
                                $selectedSchoolType = $model->getRows('tbl_schoollist', $schoolConditions);

                                $ratePerCandidate = ($selectedSchoolType['schType'] == 1)
                                    ? 250
                                    : (($selectedSchoolType['schType'] == 2) ? 150 : 0);

                                if ($ratePerCandidate > 0) {
                                    $remConditions     = ['where' => ['recordSchoolCode' => $schoolCode], 'return_type' => 'single'];
                                    $currentRemittance = $model->getRows($tblNameRem, $remConditions);

                                    if ($currentRemittance) {
                                        $currentAmount = intval($utility->inputDecode($currentRemittance['amountdue']));
                                        $currentNumber = intval($utility->inputDecode($currentRemittance['numberCaptured']));

                                        $addedNumber = $amountPaid / $ratePerCandidate;
                                        $newNumber   = $currentNumber + $addedNumber;
                                        $newAmount   = $currentAmount + intval($amountPaid);

                                        $user->recordLog(
                                            $initiatorID ?? "inpay_webhook",
                                            'Webhook Additional Candidate Clearance',
                                            "Additional candidate clearance updated via webhook for Centre Number: {$schoolCode}.
                                            Added Candidates: {$addedNumber}.
                                            New Total Candidates: {$newNumber}.
                                            Transaction Reference: {$reference}."
                                        );

                                        $updateData = [
                                            'amountdue'      => $utility->inputEncode($newAmount),
                                            'numberCaptured' => $utility->inputEncode($newNumber),
                                            'clearanceDate'  => date('Y-m-d')
                                        ];
                                        $conditionRem = ['recordSchoolCode' => $schoolCode];
                                    }
                                }
                                break;

                            case 'Bulk Payment':
                                $updateData = ['clearanceStatus' => 200, 'clearanceDate' => date('Y-m-d')];
                                $conditionRem = [
                                    'clearanceStatus' => 100,
                                    'examYearRef'    => $transDetails['transExamYear'],
                                    'submittedby'    => $initiatorID
                                ];

                                $user->recordLog(
                                    $initiatorID ?? "inpay_webhook",
                                    'Webhook Bulk Clearance Updated',
                                    "Bulk clearance completed via webhook.
                                    Exam Year ID: {$transDetails['transExamYear']}.
                                    Transaction Reference: {$reference}."
                                );
                                break;
                        }

                        if (!empty($updateData) && !empty($conditionRem)) {
                            $model->upDate($tblNameRem, $updateData, $conditionRem);
                        }
                    }
                }
            }
        } catch (Exception $e) {

            error_log("Webhook Verification Error: " . $e->getMessage());

            $user->recordLog(
                "inpay_webhook",
                'Webhook Processing Failed',
                "Webhook processing failed for Transaction Reference: {$reference}.
                Error: {$e->getMessage()}."
            );

            http_response_code(400);
            exit();
        }
    }
}

$user->recordLog(
    "inpay_webhook",
    'Webhook Processed Successfully',
    "Webhook processed successfully for Transaction Reference: {$reference}."
);

http_response_code(200);
echo 'OK';
