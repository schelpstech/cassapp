<?php
include './adminquery.php';

// Validate session and module access
if (!isset($_SESSION['pageid']) || $_SESSION['pageid'] !== 'manageschoollist') {
    $utility->redirectWithNotification('dark', 'Sorry, we cannot understand your request.', 'consoleDashboard');
    exit;
}

// Ensure valid form submission
if (!isset($_POST['profile_school_details']) || $utility->inputDecode($_POST['profile_school_details']) !== "school_profile_creator_form") {
    $utility->redirectWithNotification('danger', 'Invalid request submission.', 'manageschoollist');
    exit;
}

// Ensure the user is authenticated
if (!isset($_SESSION['activeAdmin']) || empty($_SESSION['activeAdmin'])) {
    $utility->redirectWithNotification('danger', 'Unauthorized access. Please log in.', 'login');
    exit;
}

// Define required fields
$requiredFields = ['schoolCode', 'schoolName', 'schoolZone', 'schoolType'];

// Check for missing or empty fields
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        $utility->redirectWithNotification('danger', ucfirst($field) . ' is required.', 'manageschoollist');
        exit;
    }
}

// Validate email format
if (strlen($_POST['schoolCode']) != 7) {
    $utility->redirectWithNotification('danger', 'Invalid School Centre Code Format.', 'manageschoollist');
    exit;
}
$tblName = 'tbl_schoollist';
// Check if the company record exists
$conditions = [
    'where' => ['centreNumber' => $_POST['schoolCode']],
    'return_type' => 'count',
];
$ifExist = $model->getRows($tblName, $conditions);
if ($ifExist >= 1) {
    $utility->redirectWithNotification('danger', 'Duplicate Centre Code :: School record exists already in the database.', 'manageschoollist');
    exit;
}
// Sanitize and validate input data
$schoolData = [
    'centreNumber' =>  preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['schoolCode']),
    'SchoolName' => preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['schoolName']),
    'lgaCode' => preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['schoolZone']),
    'schType' => preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['schoolType']),
];

try {
    // Insert into tbl_remittance
    $createProfile = $model->insert_data($tblName, $schoolData);
    if (!$createProfile) {
        throw new Exception('Failed to create the School profile.');
    }
    // Record the log
    $user->recordLog(
        $_SESSION['activeAdmin'],
        'School Profile Created',
        sprintf('User ID: %d created school profile with centre code :: %s.', $_SESSION['activeAdmin'], $_POST['schoolCode'])
    );

    // Redirect with success notification
    $utility->redirectWithNotification('success', 'School profile has been created for ' . $_POST['schoolName'] . ' with centre number :: ' . $_POST['schoolCode'], 'manageschoollist');
} catch (Exception $e) {
    // Log the error for debugging purposes
    error_log("Database Insert Error: " . $e->getMessage() . " | Data: " . json_encode($schoolData));

    // Redirect with error notification
    $utility->redirectWithNotification('danger', 'An error occurred while creating the school Proilfe.', 'manageschoollist');
}
