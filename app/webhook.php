<?php
// Include core initialization (Model, DB, etc.)
// Adjust path if necessary. paymentHandler.php is in app/ so it includes ./query.php.
// If webhook.php is in app/, it can do the same.
include './query.php';

// Webhook Secret
// Logic in start.inc.php already initialized $inpay with keys from DB.
// But we need the secret key raw for hash_hmac.
// $inpay object has private secretKey.
// We can fetch it again from DB or add a getter to InpayPayment.
// Simplest is to fetch again or use the variables if they are in scope (from start.inc.php).
// start.inc.php defines variables $secretKey and $publicKey globally before instantiating InpayPayment.
// Let's verify start.inc.php modification.
// Yes:  $secretKey = $inpaySettings['secret_key'] ?? '';

// Retrieve headers and body
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$timestamp = (int) ($_SERVER['HTTP_X_WEBHOOK_TIMESTAMP'] ?? 0);
$eventName = $_SERVER['HTTP_X_WEBHOOK_EVENT'] ?? '';

// 1. Validate Timestamp (Replay Attack Protection)
$now = (int) round(microtime(true) * 1000);
$allowedSkew = 5 * 60 * 1000; // 5 minutes
if (abs($now - $timestamp) > $allowedSkew) {
  http_response_code(400);
  exit('Invalid timestamp');
}

// 2. Validate Signature
$expectedSignature = hash_hmac('sha256', $payload, $secretKey);
$cleanSignature = preg_replace('/^sha256=/', '', $signature);

if (!hash_equals($expectedSignature, $cleanSignature)) {
  http_response_code(401);
  exit('Invalid signature');
}

// 3. Parse Data
$data = json_decode($payload, true);
if (json_last_error() !== JSON_ERROR_NONE || empty($data['event'])) {
  http_response_code(400);
  exit('Malformed event');
}

$completionEvents = [
  'payment.virtual_payid.completed',
  'payment.checkout_payid.completed',
  'payment.virtual_account.completed',
  'payment.checkout_virtual_account.completed',
  'payment.card.completed', // Assuming card events follow pattern
  'payment.completed' // Generic fallback check
];

// Check if event is a completion event
// The docs list specific events, but it's safe to check common ones or if logic implies success.
// Better to check if event ends in .completed
if (strpos($data['event'], '.completed') !== false) {
  $transaction = $data['data'] ?? [];
  $reference = $transaction['reference'] ?? null;

  // Metadata
  $metadata = $transaction['metadata'] ?? [];
  if (is_string($metadata)) {
    $decodedMeta = json_decode($metadata, true);
    if (json_last_error() === JSON_ERROR_NONE) {
      $metadata = $decodedMeta;
    }
  }
  // Fallback to metadata in root if not in transaction object (api variation)
  if (!$reference && isset($data['data']['reference'])) {
    $reference = $data['data']['reference'];
  }

  if ($reference) {
    try {
      // Check status via API (Double Check)
      // Use existing $inpay object
      $verifiedData = $inpay->verifyTransaction($reference);

      if ($verifiedData['status'] === 'completed') {
        $amountPaid = $verifiedData['amount'] / 100;

        // Update tbl_transaction
        $tblName = 'tbl_transaction';
        $transData = [
          'transAmount' => $utility->inputEncode($amountPaid),
          'transStatus' => 1,
          'transDate' => date('Y-m-d')
        ];
        $condition = ['transactionRef' => $reference];
        $model->upDate($tblName, $transData, $condition);

        // Get Transaction Details to perform Fulfillment
        $conditions = ['return_type' => 'single', 'where' => ['transactionRef' => $reference]];
        $transDetails = $model->getRows($tblName, $conditions);

        if ($transDetails) {
          // We need to resolve the User ID ('submittedby') from 'transInitiator' (username)
          // The 'book_of_life' table maps user_name to userid.
          // 'tbl_consultantdetails' also has userid but book_of_life is the auth table.
          $initiatorUser = $transDetails['transInitiator'];
          $userConditions = ['return_type' => 'single', 'where' => ['user_name' => $initiatorUser]];
          $userDetails = $model->getRows('book_of_life', $userConditions);

          // We also need the mapping from book_of_life.userid to tbl_consultantdetails.userId (sometimes they differ or are related)
          // In query.php: $_SESSION['activeID'] = $consultantDetails['userId'];
          // where consultantDetails joined on book_of_life.userid = tbl_consultantdetails.userid.

          $initiatorID = 0;
          if ($userDetails) {
            // Get Consultant ID
            $consultantConditions = ['return_type' => 'single', 'where' => ['userid' => $userDetails['userid']]];
            $consultantData = $model->getRows('tbl_consultantdetails', $consultantConditions);
            if ($consultantData) {
              $initiatorID = $consultantData['userId'];
            }
          }

          if ($initiatorID > 0) {
            // Fulfillment Logic
            $tblNameRem = 'tbl_remittance';
            $updateData = [];
            $conditionRem = [];

            switch ($transDetails['transactionType']) {
              case 'Individual School':
                $clearedSchool = json_decode($transDetails['transSchoolCode'], true);
                $schoolCode = $clearedSchool[0];
                $updateData = ['clearanceStatus' => 200, 'clearanceDate' => date('Y-m-d')];
                $conditionRem = ['recordSchoolCode' => $schoolCode];
                break;

              case 'Additional Candidate':
                $clearedSchool = json_decode($transDetails['transSchoolCode'], true);
                $schoolCode = $clearedSchool[0];

                // Need to calculate new numbers
                // We duplicates logic partly, but we can also just rely on update being done.
                // However, paymentHandler.php calculates new number based on rate.
                // For webhook, we should ideally simply mark as paid OR perform the calculation.
                // Logic in paymentHandler:
                // $newNumber = ($amountPaid / $ratePerCandidate) + existing...
                // This requires fetching existing session data which we don't have.
                // BUT: The Update logic in Transaction Verification is separate from Initialization.
                // "Additional Candidate" is tricky because it modifies 'amountdue' and 'numberCaptured' instead of just status.
                // We can try to replicate it:

                // Get School Type
                $schoolConditions = ['where' => ['centreNumber' => $schoolCode], 'return_type' => 'single'];
                $selectedSchoolType = $model->getRows('tbl_schoollist', $schoolConditions);
                $ratePerCandidate = ($selectedSchoolType['schType'] == 1) ? 280 : (($selectedSchoolType['schType'] == 2) ? 130 : 0);

                if ($ratePerCandidate > 0) {
                  // Get current state of remittance
                  $remConditions = ['where' => ['recordSchoolCode' => $schoolCode], 'return_type' => 'single'];
                  $currentRemittance = $model->getRows($tblNameRem, $remConditions);

                  if ($currentRemittance) {
                    $currentAmount = intval($utility->inputDecode($currentRemittance['amountdue']));
                    $currentNumber = intval($utility->inputDecode($currentRemittance['numberCaptured']));

                    $addedNumber = $amountPaid / $ratePerCandidate;

                    $newNumber = $currentNumber + $addedNumber;
                    $newAmount = $currentAmount + intval($amountPaid);

                    $updateData = [
                      'amountdue' => $utility->inputEncode($newAmount),
                      'numberCaptured' => $utility->inputEncode($newNumber),
                      'clearanceDate' => date('Y-m-d')
                    ];
                    $conditionRem = ['recordSchoolCode' => $schoolCode];
                  }
                }
                break;

              case 'Bulk Payment':
                $updateData = ['clearanceStatus' => 200, 'clearanceDate' => date('Y-m-d')];
                $conditionRem = [
                  'clearanceStatus' => 100,
                  'examYearRef' => $transDetails['transExamYear'],
                  'submittedby' => $initiatorID
                ];
                break;
            }

            if (!empty($updateData) && !empty($conditionRem)) {
              $model->upDate($tblNameRem, $updateData, $conditionRem);
            }
          }
        }
      }
    } catch (Exception $e) {
      // Log error
      error_log("Webhook Verification Error: " . $e->getMessage());
      http_response_code(400);
      exit();
    }
  }
}

http_response_code(200);
echo 'OK';
?>