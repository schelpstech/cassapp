<?php
include './adminquery.php';

// Validate session
if (!isset($_SESSION['activeAdmin']) || empty($_SESSION['activeAdmin'])) {
  $utility->redirectWithNotification('danger', 'Unauthorized access. Please log in.', '../console/index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_settings') {

  $publicKey = filter_var($_POST['public_key'], FILTER_SANITIZE_STRING);
  $secretKey = filter_var($_POST['secret_key'], FILTER_SANITIZE_STRING); // Don't full sanitization as keys might have special chars, but basic strip tags
  $environment = ($_POST['environment'] === 'test') ? 'test' : 'live';
  $isActive = isset($_POST['is_active']) ? 1 : 0;

  if (empty($publicKey) || empty($secretKey)) {
    $utility->redirectWithNotification('danger', 'Public and Secret keys are required.', 'paymentSettings');
    exit;
  }

  $tblName = 'tbl_payment_settings';

  // Check if row exists (it should, from setup, but handle case)
  $stmt = $db_conn->query("SELECT id FROM $tblName LIMIT 1");
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    // Update
    $data = [
      'public_key' => $publicKey,
      'secret_key' => $secretKey,
      'environment' => $environment,
      'is_active' => $isActive
    ];
    $conditions = ['id' => $row['id']];

    $update = $model->upDate($tblName, $data, $conditions);

    if ($update !== false) { // update returns row count or false. 0 rows affected is not false.
      $utility->redirectWithNotification('success', 'Payment settings updated successfully.', 'paymentSettings');
    } else {
      $utility->redirectWithNotification('danger', 'Failed to update settings.', 'paymentSettings');
    }

  } else {
    // Insert
    $data = [
      'public_key' => $publicKey,
      'secret_key' => $secretKey,
      'environment' => $environment,
      'is_active' => $isActive
    ];
    $insert = $model->insert_data($tblName, $data);
    if ($insert) {
      $utility->redirectWithNotification('success', 'Payment settings created successfully.', 'paymentSettings');
    } else {
      $utility->redirectWithNotification('danger', 'Failed to create settings.', 'paymentSettings');
    }
  }

} else {
  $utility->redirectWithNotification('danger', 'Invalid request.', 'paymentSettings');
}
?>