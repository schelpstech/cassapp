<?php
include './adminquery.php';

/* =========================================
   GLOBAL SECURITY CHECKS
========================================= */

// Ensure admin is logged in
if (!isset($_SESSION['activeAdmin']) || empty($_SESSION['activeAdmin'])) {
    $utility->redirectWithNotification('danger', 'Unauthorized access. Please log in.', 'login');
    exit;
}


/* =========================================
   1. CREATE CONSULTANT PROFILE
========================================= */

if (isset($_POST['profile_company_details']) &&
    $utility->inputDecode($_POST['profile_company_details']) === "company_profile_creator_form") {

    // Validate module access
    if (!isset($_SESSION['pageid']) || $_SESSION['pageid'] !== 'consultantRecord') {
        $utility->redirectWithNotification('dark', 'Sorry, we cannot understand your request.', 'consoleDashboard');
        exit;
    }

    $requiredFields = ['usercode', 'companyName', 'companyAddress', 'contactPhone', 'contactEmail'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $utility->redirectWithNotification('danger', ucfirst($field) . ' is required.', 'consultantRecord');
            exit;
        }
    }

    $tblName = 'book_of_life';

    $conditions = [
        'where' => ['user_name' => $_POST['usercode']],
        'return_type' => 'count',
    ];

    $ifExist = $model->getRows($tblName, $conditions);

    if ($ifExist >= 1) {
        $utility->redirectWithNotification('danger', 'Duplicate company record exists.', 'consultantRecord');
        exit;
    }

    $userID = $cntUsers + 1;

    $tblNameA = 'tbl_consultantdetails';
    $companyData = [
        'companyName' => preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['companyName']),
        'companyAddress' => preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['companyAddress']),
        'contactPhone' => filter_var($_POST['contactPhone'], FILTER_SANITIZE_NUMBER_INT),
        'contactEmail' => filter_var($_POST['contactEmail'], FILTER_VALIDATE_EMAIL),
        'userId' => $userID
    ];

    $tblNameB = 'book_of_life';
    $bookData = [
        'user_name' => filter_var($_POST['usercode'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'user_password' => $utility->encodePassword('school'),
        'access_status' => 1,
        'activeStatus' => 0
    ];

    if (!$companyData['contactEmail']) {
        $utility->redirectWithNotification('danger', 'Invalid email address.', 'consultantRecord');
        exit;
    }

    if (!preg_match('/^\d{11}$/', $companyData['contactPhone'])) {
        $utility->redirectWithNotification('danger', 'Phone number must be 11 digits.', 'consultantRecord');
        exit;
    }

    try {
        $createProfile = $model->insert_data($tblNameA, $companyData);
        if (!$createProfile) throw new Exception('Profile creation failed.');

        $createLogin = $model->insert_data($tblNameB, $bookData);
        if (!$createLogin) throw new Exception('Login creation failed.');

        $user->recordLog(
            $_SESSION['activeAdmin'],
            'Consultant Profile Created',
            "Usercode: {$_POST['usercode']}"
        );

        $utility->redirectWithNotification(
            'success',
            'Consultant profile created successfully.',
            'consultantRecord'
        );

    } catch (Exception $e) {
        error_log($e->getMessage());
        $utility->redirectWithNotification('danger', 'Error creating profile.', 'consultantRecord');
    }

    exit;
}


/* =========================================
   2. APPROVE CLEARANCE (300 → 200)
========================================= */

if (isset($_POST['approve_clearance']) &&
    $utility->inputDecode($_POST['approve_clearance']) === 'approve_clearance_form') {

    $consultant = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['consultant']);

    $updateData = ['workStatus' => 200];
    $condition = ['userId' => $consultant];

    if ($model->upDate('tbl_consultantdetails', $updateData, $condition)) {

        $user->recordLog(
            $_SESSION['activeAdmin'],
            'Clearance Approved',
            "Consultant {$consultant} approved."
        );

        $utility->redirectWithNotification(
            'success',
            'Consultant clearance approved.',
            'consultantRecord'
        );
    }

    exit;
}


/* =========================================
   3. REJECT CLEARANCE (300 → 100)
========================================= */

if (isset($_POST['reject_clearance']) &&
    $utility->inputDecode($_POST['reject_clearance']) === 'reject_clearance_form') {

    $consultant = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['consultant']);

    $updateData = ['workStatus' => 100, 'signedLetter' => 100];
    $condition = ['userId' => $consultant];

    if ($model->upDate('tbl_consultantdetails', $updateData, $condition)) {

        $user->recordLog(
            $_SESSION['activeAdmin'],
            'Clearance Rejected',
            "Consultant {$consultant} rejected."
        );

        $utility->redirectWithNotification(
            'danger',
            'Consultant clearance rejected.',
            'consultantRecord'
        );
    }

    exit;
}