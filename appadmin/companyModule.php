<?php
include './adminquery.php';

// Validate session and module access
if (!isset($_SESSION['pageid']) || $_SESSION['pageid'] !== 'consultantRecord') {
    $utility->redirectWithNotification('dark', 'Sorry, we cannot understand your request.', 'consoleDashboard');
    exit;
}

// Ensure valid form submission
if (!isset($_POST['profile_company_details']) || $utility->inputDecode($_POST['profile_company_details']) !== "company_profile_creator_form") {
    $utility->redirectWithNotification('danger', 'Invalid request submission.', 'consultantRecord');
    exit;
}

// Ensure the user is authenticated
if (!isset($_SESSION['activeAdmin']) || empty($_SESSION['activeAdmin'])) {
    $utility->redirectWithNotification('danger', 'Unauthorized access. Please log in.', 'login');
    exit;
}

// Define required fields
$requiredFields = ['usercode', 'companyName', 'companyAddress', 'contactPhone', 'contactEmail'];

// Check for missing or empty fields
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        $utility->redirectWithNotification('danger', ucfirst($field) . ' is required.', 'consultantRecord');
        exit;
    }
}

$tblName = 'book_of_life';

// Check if the company record exists
$conditions = [
    'where' => ['user_name' => $_POST['usercode']],
    'return_type' => 'count',
];

$ifExist = $model->getRows($tblName, $conditions);

if ($ifExist >= 1) {
    $utility->redirectWithNotification('danger', 'Duplicate company record exists within this request.', 'consultantRecord');
    exit;
}
$userID = $cntUsers + 2;
// Sanitize and validate input data
$tblNameA = 'tbl_consultantdetails';
$companyData = [
    'companyName' =>  preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['companyName']),
    'companyAddress' => preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['companyAddress']),
    'contactPhone' => filter_var($_POST['contactPhone'], FILTER_SANITIZE_NUMBER_INT),
    'contactEmail' => filter_var($_POST['contactEmail'], FILTER_VALIDATE_EMAIL),
    'userId' => $userID
];

$tblNameB = 'book_of_life';
$bookData = [
    'user_name'    => filter_var($_POST['usercode'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
    'user_password' =>  $utility->encodePassword('school'),
    'access_status'   => 1, // Keep only numbers
    'activeStatus'   => 0 // Keep only numbers
];
// Validate email format
if (!$companyData['contactEmail']) {
    $utility->redirectWithNotification('danger', 'Invalid email address provided.', 'consultantRecord');
    exit;
}

// Validate phone number (must be exactly 11 digits)
if (!preg_match('/^\d{11}$/', $companyData['contactPhone'])) {
    $utility->redirectWithNotification('danger', 'Phone number must be exactly 11 digits.', 'consultantRecord');
    exit;
}

try {
    // Insert into tbl_remittance
    $createProfile = $model->insert_data($tblNameA, $companyData);
    if (!$createProfile) {
        throw new Exception('Failed to create the company profile.');
    }

    //Create Login
    $createLogin = $model->insert_data($tblNameB, $bookData);
    if (!$createLogin) {
        throw new Exception('Failed to create the company login details.');
    }
    // Record the log
    $user->recordLog(
        $_SESSION['activeAdmin'],
        'Consultant Profile Created',
        sprintf('User ID: %d created consultant profile with usercode: %s.', $_SESSION['activeAdmin'], $_POST['usercode'])
    );

    // Redirect with success notification
    $utility->redirectWithNotification('success', 'Consultant company profile has been created for ' . $_POST['companyName'] . ' with usercode :: ' . $_POST['usercode'], 'consultantRecord');
} catch (Exception $e) {
    // Log the error for debugging purposes
    error_log("Database Insert Error: " . $e->getMessage() . " | Data: " . json_encode($companyData));

    // Redirect with error notification
    $utility->redirectWithNotification('danger', 'An error occurred while creating the consultant details.', 'consultantRecord');
}
