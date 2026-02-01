<?php
include './query.php';

// Utility Functions
function validatePaymentParameters($email, $amount, $callbackUrl)
{
    if (!$email || !$amount || !$callbackUrl) {
        throw new Exception('Invalid payment parameters.');
    }
}

function recordTransaction($model, $data)
{
    $tblName = 'tbl_transaction';
    if (!$model->insert_data($tblName, $data)) {
        throw new Exception('Failed to record transaction.');
    }
}

// Dynamic Verify URL - Prefer dynamic over hardcoded
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$verifyUrl = "$protocol://$host/app/paymentHandler.php";

// Helper to load the Payment View
function loadPaymentView($inpay, $email, $amount, $callbackUrl, $transactionReference)
{
    if (!$inpay) {
        throw new Exception('Payment gateway not initialized.');
    }
    $publicKey = $inpay->getPublicKey();
    // Include the view file. Variables $publicKey, $amount, $email, etc. will be available in the included file's scope.
    include '../view/pages/payment/pay.php';
    exit();
}

// Clearance Process
if (isset($_GET['pageid'], $_GET['reference']) && $utility->inputDecode($_GET['pageid']) === 'clearanceProcess') {
    $centreNumber = $utility->inputDecode($_GET['reference']);
    $tblName = 'tbl_remittance';
    $conditions = [
        'where' => [
            'examYearRef' => $examYear['id'],
            'submittedby' => $_SESSION['activeID'],
            'recordSchoolCode' => $centreNumber
        ],
        'return_type' => 'single',
    ];

    $paymentDetails = $model->getRows($tblName, $conditions);

    try {
        $email = $consultantDetails['contactEmail'] ?? null;
        $amount = ($utility->inputDecode($paymentDetails['amountdue']) * 100) ?? null;
        $callbackUrl = $verifyUrl;

        validatePaymentParameters($email, $amount, $callbackUrl);

        $schoolCodes = [$centreNumber];
        $recordSchoolCode = json_encode($schoolCodes);

        $transactionReference = $centreNumber . strtoupper($utility->generateRandomText(4));
        $transactionData = [
            'transactionRef' => $transactionReference,
            'transSchoolCode' => $recordSchoolCode,
            'transAmount' => $paymentDetails['amountdue'],
            'transactionType' => 'Individual School',
            'transExamYear' => $examYear['id'],
            'transInitiator' => $_SESSION['active']
        ];

        recordTransaction($model, $transactionData);
        $user->recordLog(
            $_SESSION['active'],
            'Payment Initiated',
            "User ID: {$_SESSION['active']} initiated Individual School payment for Centre Number: {$centreNumber}. 
    Amount Due: ₦{$paymentDetails['amountdue']}. 
    Transaction Reference: {$transactionReference}."
        );
        loadPaymentView($inpay, $email, $amount, $callbackUrl, $transactionReference);
    } catch (Exception $e) {
        $utility->redirectWithNotification('danger', 'Error completing transaction: ' . $e->getMessage(), 'capturingRecord');
    }
}

// Additional Candidates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['additionalCandidates']) && $utility->inputDecode($_POST['additionalCandidates']) === 'clear_candidates') {
    $recordSchoolCode = htmlspecialchars($_POST['schoolName'], ENT_QUOTES, 'UTF-8');
    $numberCaptured = filter_var($_POST['numCandidatesCaptured'], FILTER_VALIDATE_INT);

    $tblName = 'tbl_remittance';
    $conditions = [
        'where' => [
            'examYearRef' => $examYear['id'],
            'submittedby' => $_SESSION['activeID'],
            'recordSchoolCode' => $recordSchoolCode
        ],
        'return_type' => 'count',
    ];

    if ($model->getRows($tblName, $conditions) == 1) {
        $conditions['return_type'] = 'single';
        $paymentDetails = $model->getRows($tblName, $conditions);

        $_SESSION['ExAmount'] = $paymentDetails['amountdue'];
        $_SESSION['ExNumber'] = $paymentDetails['numberCaptured'];
        $schoolCodes = [$recordSchoolCode];
        $recordSchoolenCode = json_encode($schoolCodes);

        try {
            $email = $consultantDetails['contactEmail'] ?? null;

            $tblName = 'tbl_schoollist';
            $conditions = [
                'where' => [
                    'centreNumber' => $recordSchoolCode,
                ],
                'return_type' => 'single',
            ];
            $selectedSchoolType = $model->getRows($tblName, $conditions);

            if (!empty($selectedSchoolType)) {
                $ratePerCandidate = ($selectedSchoolType['schType'] == 1) ? 250 : (($selectedSchoolType['schType'] == 2) ? 150 : 0);
                $amountDue = $numberCaptured * $ratePerCandidate;
            } else {
                $utility->redirectWithNotification('danger', 'School Type not Specified.', 'capturingRecord');
            }

            $amount = ($amountDue * 100) ?? null;
            $callbackUrl = $verifyUrl;

            validatePaymentParameters($email, $amount, $callbackUrl);

            $transactionReference = $recordSchoolCode . strtoupper($utility->generateRandomText(4));
            $transactionData = [
                'transactionRef' => $transactionReference,
                'transSchoolCode' => $recordSchoolenCode,
                'transactionType' => 'Additional Candidate',
                'transAmount' => $utility->inputEncode(($amount / 100)),
                'transExamYear' => $examYear['id'],
                'transInitiator' => $_SESSION['active']
            ];

            recordTransaction($model, $transactionData);
            $user->recordLog(
                $_SESSION['active'],
                'Payment Initiated',
                "User ID: {$_SESSION['active']} initiated Additional Candidate payment for Centre Number: {$recordSchoolCode}. 
    Number of Additional Candidates: {$numberCaptured}. 
    Amount Due: ₦{$amountDue}. 
    Transaction Reference: {$transactionReference}."
            );

            loadPaymentView($inpay, $email, $amount, $callbackUrl, $transactionReference);
        } catch (Exception $e) {
            $utility->redirectWithNotification('danger', 'Error completing transaction: ' . $e->getMessage(), 'capturingRecord');
        }
    } else {
        $utility->redirectWithNotification('danger', 'Addition of candidates can only be done for schools with existing clearance records.', 'capturingRecord');
    }
}

// Bulk Payment
if (isset($_GET['pageid']) && $utility->inputDecode($_GET['pageid']) === 'bulkClearanceProcess') {
    $dueRemittance = isset($totalfigure) && is_numeric($totalfigure) ? htmlspecialchars($totalfigure) : 0;
    $paidRemittance = isset($totalRemittedfigure) && is_numeric($totalRemittedfigure) ? htmlspecialchars($totalRemittedfigure) : 0;
    $balanceRemittance = intval($dueRemittance) - intval($paidRemittance);

    $tblName = 'tbl_remittance';
    $conditions = [
        'where' => [
            'examYearRef' => $examYear['id'],
            'submittedby' => $_SESSION['activeID'],
            'clearanceStatus' => 100
        ],
    ];

    $numberOfSchools = $model->getRows($tblName, $conditions);
    if (!empty($numberOfSchools)) {
        $schoolCodes = [];
        foreach ($numberOfSchools as $row) {
            if (isset($row['recordSchoolCode'])) {
                $schoolCodes[] = $row['recordSchoolCode'];
            }
        }

        $schoolCount = count($schoolCodes);
        $serializedArray = json_encode($schoolCodes);

        try {
            $email = $consultantDetails['contactEmail'] ?? null;
            $amount = ($balanceRemittance * 100) ?? null;
            $callbackUrl = $verifyUrl;

            validatePaymentParameters($email, $amount, $callbackUrl);

            $transactionReference = $schoolCount . 'SCH' . strtoupper($utility->generateRandomText(4));
            $transactionData = [
                'transactionRef' => $transactionReference,
                'transSchoolCode' => $serializedArray,
                'transAmount' => $utility->inputEncode($balanceRemittance),
                'transactionType' => 'Bulk Payment',
                'transExamYear' => $examYear['id'],
                'transInitiator' => $_SESSION['active']
            ];

            recordTransaction($model, $transactionData);
            $user->recordLog(
                $_SESSION['active'],
                'Payment Initiated',
                "User ID: {$_SESSION['active']} initiated Bulk Payment for {$schoolCount} schools. 
                                Total Amount: ₦{$balanceRemittance}. 
                                Transaction Reference: {$transactionReference}."
            );

            loadPaymentView($inpay, $email, $amount, $callbackUrl, $transactionReference);
        } catch (Exception $e) {
            $utility->redirectWithNotification('danger', 'Error completing transaction: ' . $e->getMessage(), 'capturingRecord');
        }
    } else {
        $utility->redirectWithNotification('danger', 'No Pending transaction Found', 'capturingRecord');
    }
}
// Verify Payment (iNPAY)
if (isset($_GET['reference'])) {
    try {
        // Verify transaction using iNPAY API via the new class
        $transactionData = $inpay->verifyTransaction($_GET['reference']);

        // Check if verified successfully
        // The verifyTransaction method throws exception if not successful, so we are good if we reach here.
        // But let's be double sure.
        if ($transactionData['status'] === 'completed') {
            $user->recordLog(
                $_SESSION['active'],
                'Payment Verification Successful',
                "Payment verified successfully. 
                                Transaction Reference: {$transactionReference}. 
                                Amount Paid: ₦{$amountPaid}. 
                                Payment Type: {$transDetails['transactionType']}."
            );

            $transactionReference = $transactionData['reference'];
            $amountPaid = $transactionData['amount'] / 100;  // Convert amount to standard format (Naira)

            $tblName = 'tbl_transaction';
            $transData = [
                'transAmount' => $utility->inputEncode($amountPaid),
                'transStatus' => 1,
                'transDate' => date('Y-m-d')
            ];
            $condition = ['transactionRef' => $transactionReference];

            // Update transaction details in the database
            if (!$model->upDate($tblName, $transData, $condition)) {
                // It might have been updated by webhook already.
                // We should check if it's already 1.
            }

            // Retrieve transaction details
            $conditions = ['return_type' => 'single', 'where' => ['transactionRef' => $transactionReference]];
            $transDetails = $model->getRows($tblName, $conditions);

            if (!$transDetails) {
                throw new Exception('Transaction details not found.');
            }

            $tblName = 'tbl_remittance';
            $destination = '';
            $updateData = [];
            $clearedSchool = [];

            // Handle different transaction types
            switch ($transDetails['transactionType']) {
                case 'Individual School':
                    $clearedSchool = json_decode($transDetails['transSchoolCode'], true);
                    $_SESSION['clearedSchool'] = $clearedSchool[0];

                    $updateData = [
                        'clearanceStatus' => 200,
                        'clearanceDate' => date('Y-m-d')
                    ];
                    $condition = ['recordSchoolCode' => $_SESSION['clearedSchool']];
                    $destination = 'clearancePage';
                    $user->recordLog(
                        $_SESSION['active'],
                        'Clearance Updated',
                        "Clearance completed for Centre Number: {$_SESSION['clearedSchool']} 
                                        via Individual School payment. 
                                        Transaction Reference: {$transactionReference}."
                    );

                    break;

                case 'Additional Candidate':
                    $clearedSchool = json_decode($transDetails['transSchoolCode'], true);
                    $_SESSION['clearedSchool'] = $clearedSchool[0];
                    // Get school type
                    $schoolConditions = [
                        'where' => ['centreNumber' => $clearedSchool[0]],
                        'return_type' => 'single'
                    ];
                    $selectedSchoolType = $model->getRows('tbl_schoollist', $schoolConditions);

                    if (empty($selectedSchoolType['schType'])) {
                        $utility->redirectWithNotification('danger', 'School Type not specified.', 'capturingRecord');
                    }
                    $ratePerCandidate = ($selectedSchoolType['schType'] == 1) ? 250 : (($selectedSchoolType['schType'] == 2) ? 150 : '');

                    $newNumber = ($amountPaid / $ratePerCandidate) + $utility->inputDecode($_SESSION['ExNumber']);
                    $newAmountPaid = intval($amountPaid) + intval($utility->inputDecode($_SESSION['ExAmount']));

                    $updateData = [
                        'amountdue' => $utility->inputEncode($newAmountPaid),
                        'numberCaptured' => $utility->inputEncode($newNumber),
                        'clearanceDate' => date('Y-m-d')
                    ];
                    $condition = ['recordSchoolCode' => $_SESSION['clearedSchool']];
                    $destination = 'clearancePage';
                    $user->recordLog(
                        $_SESSION['active'],
                        'Clearance Updated',
                        "Additional candidates clearance updated for Centre Number: {$_SESSION['clearedSchool']}. 
                        New Total Candidates: {$newNumber}. 
                        Transaction Reference: {$transactionReference}."
                    );

                    break;

                case 'Bulk Payment':
                    $updateData = [
                        'clearanceStatus' => 200,
                        'clearanceDate' => date('Y-m-d')
                    ];
                    $condition = [
                        'clearanceStatus' => 100,
                        'examYearRef' => $examYear['id'],
                        'submittedby' => $_SESSION['activeID']
                    ];
                    $destination = 'capturingRecord';
                    $user->recordLog(
                        $_SESSION['active'],
                        'Clearance Updated',
                        "Bulk clearance completed for multiple schools. 
                                Total Schools Cleared: {$schoolCount}. 
                                Transaction Reference: {$transactionReference}."
                    );

                    break;

                default:
                    // Maybe already handled?
                    break;
            }

            // Update clearance status and redirect
            // Note: If webhook already updated, this might not change anything, which is fine.
            if (!empty($updateData)) {
                $model->upDate($tblName, $updateData, $condition);
            }

            $utility->redirectWithNotification('success', 'Transaction verified and saved successfully.', $destination);
        } else {
            throw new Exception('Transaction verification failed. Status: ' . $transactionData['status']);
        }
    } catch (Exception $e) {
        $user->recordLog(
            $_SESSION['active'] ?? 0,
            'Payment Verification Failed',
            "Payment verification failed for Transaction Reference: {$_GET['reference']}. 
    Error Message: {$e->getMessage()}."
        );

        // Handle errors and redirect with an error message
        $utility->redirectWithNotification('danger', 'Error verifying transaction: ' . $e->getMessage(), 'capturingRecord');
    }
}
