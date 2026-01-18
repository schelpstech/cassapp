<?php
include './adminquery.php';

// Ensure the user is authenticated
if (!isset($_SESSION['activeAdmin']) || empty($_SESSION['activeAdmin'])) {
    $utility->redirectWithNotification('danger', 'Unauthorized access. Please log in.', 'login');
    exit;
}
// Validate session and module access
if (!isset($_SESSION['pageid']) || $_SESSION['pageid'] !== 'manageschoolallocation') {
    $utility->redirectWithNotification('dark', 'Sorry, we cannot understand your request.', 'consoleDashboard');
    exit;
}
// Ensure valid form submission
if (isset($_POST['schoolAllocator']) || $utility->inputDecode($_POST['schoolAllocator']) == "school_profile_allocator_form") {


    // Define required fields
    $requiredFields = ['schoolZone', 'schoolCode', 'consultantId'];

    // Check for missing or empty fields
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $utility->redirectWithNotification('danger', ucfirst($field) . ' is required.', 'manageschoolallocation');
            exit;
        }
    }

    // Validate email format
    if (strlen($_POST['schoolCode']) != 7) {
        $utility->redirectWithNotification('danger', 'Invalid School Centre Code Format.', 'manageschoolallocation');
        exit;
    }
    $tblName = 'tbl_schoolallocation';
    // Check if the school allocation record exists
    $conditions = [
        'where' => [
            'schoolCode' => $_POST['schoolCode'],
            'examYear' => $examYear['id']
        ],
        'return_type' => 'count',
    ];
    $ifExist = $model->getRows($tblName, $conditions);
    if ($ifExist >= 1) {
        $utility->redirectWithNotification('danger', 'Duplicate Allocation :: School allocation record exists already in the database.', 'manageschoolallocation');
        exit;
    }
    // Sanitize and validate input data
    $allocationData = [
        'schoolCode' =>  preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['schoolCode']),
        'examYear' => $examYear['id'],
        'consultantID' => preg_replace('/[^a-zA-Z0-9\s&,\.\-\'()]/', '', $_POST['consultantId'])
    ];

    try {
        // Insert into tbl_remittance
        $allocateSchool = $model->insert_data($tblName, $allocationData);
        if (!$allocateSchool) {
            throw new Exception('Failed to allocate  School to Consultant.');
        }
        // Record the log
        $user->recordLog(
            $_SESSION['activeAdmin'],
            'School Allocation Recorded',
            sprintf('User ID: %d allocated to school  with centre code :: %s.', $_POST['consultantId'], $_POST['schoolCode'])
        );

        // Redirect with success notification
        $utility->redirectWithNotification('success', 'School with centre number ::  ' . $_POST['schoolCode'] . ' has been allocated successfully to consultant with ID :: ' . $_POST['consultantId'], 'manageschoolallocation');
    } catch (Exception $e) {
        // Log the error for debugging purposes
        error_log("Database Insert Error: " . $e->getMessage() . " | Data: " . json_encode($allocationData));

        // Redirect with error notification
        $utility->redirectWithNotification('danger', 'An error occurred while allocating the school to consultant.', 'manageschoolallocation');
    }
} else {
    $utility->redirectWithNotification('danger', 'Invalid request submission.', 'manageschoolallocation');
    exit;
}
