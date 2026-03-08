<?php
include './query.php';

// Validate session module
if (!isset($_SESSION['module']) || $_SESSION['module'] !== 'Dashboard') {
    $utility->redirectWithNotification('dark', 'Sorry, we cannot understand your request.', 'consultantDashboard');
    exit;
}

// Validate user login
if (!isset($_SESSION['activeID']) || empty($_SESSION['activeID'])) {
    $utility->redirectWithNotification('danger', 'Unauthorized access. Please login.', 'login');
    exit;
}

// Validate form request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['signedLetter'])) {

    $verificationCode = $utility->sanitizeInput($_POST['verificationCode']);

    if (empty($verificationCode)) {
        $utility->redirectWithNotification('danger', 'Invalid verification code.', 'consultantDashboard');
        exit;
    }

    /* ===============================
       FILE UPLOAD SETTINGS
    =============================== */

    $allowedTypes = [
        'application/pdf',
        'image/jpeg',
        'image/png'
    ];

    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $uploadPath = '../storage/ancopps_letters';

    // Upload the file
    $uploadResult = $utility->handleUploadedFile(
        'signedLetter',
        $allowedTypes,
        $maxFileSize,
        $uploadPath
    );

    if ($uploadResult !== "success") {
        $utility->redirectWithNotification('danger', $uploadResult, 'consultantDashboard');
        exit;
    }

    $fileName = $_SESSION['fileName'];

    /* ===============================
       UPDATE DATABASE
    =============================== */

    $tblName = 'tbl_consultantdetails';

    $updateData = [
        'signedLetter' => $fileName,
        'workStatus' => 300
    ];

    $condition = [
        'userId' => $_SESSION['activeID']
    ];

    try {

        if (!$model->upDate($tblName, $updateData, $condition)) {
            throw new Exception('Unable to update record.');
        }

        /* ===============================
           RECORD LOG
        =============================== */

        $user->recordLog(
            $_SESSION['active'],
            'ANCOPPS Letter Uploaded',
            "Consultant uploaded signed ANCOPPS letter. Verification Code: {$verificationCode}"
        );

        $utility->redirectWithNotification(
            'success',
            'Signed letter uploaded successfully. Awaiting verification.',
            'consultantDashboard'
        );
    } catch (Exception $e) {

        error_log($e->getMessage());

        $utility->redirectWithNotification(
            'danger',
            'An error occurred while processing the upload.',
            'consultantDashboard'
        );
    }
}
