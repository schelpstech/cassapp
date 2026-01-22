<?php
include './adminquery.php';

/* -------------------- SECURITY CHECKS -------------------- */
if (!isset($_SESSION['activeAdmin']) || empty($_SESSION['activeAdmin'])) {
    $utility->redirectWithNotification('danger', 'Unauthorized access. Please log in.', 'login');
    exit;
}

if (!isset($_SESSION['pageid']) || $_SESSION['pageid'] !== 'manageschoolallocation') {
    $utility->redirectWithNotification('dark', 'Sorry, we cannot understand your request.', 'consoleDashboard');
    exit;
}

/* -------------------- FORM VALIDATION -------------------- */
if (
    isset($_POST['schoolAllocator']) &&
    $utility->inputDecode($_POST['schoolAllocator']) !== 'school_profile_allocator_form'
) {


    $requiredFields = ['schoolZone', 'schoolCode', 'consultantId'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $utility->redirectWithNotification(
                'danger',
                ucfirst($field) . ' is required.',
                'manageschoolallocation'
            );
            exit;
        }
    }

    /* -------------------- NORMALIZE INPUT -------------------- */
    $schoolCodes  = (array) $_POST['schoolCode']; // force array
    $consultantId = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['consultantId']);
    $examYearId   = $examYear['id'];
    $tblName      = 'tbl_schoolallocation';

    $allocated   = [];
    $duplicates  = [];

    try {
        $model->beginTransaction();

        foreach ($schoolCodes as $schoolCode) {

            $schoolCode = trim($schoolCode);

            /* Validate school code format */
            if (strlen($schoolCode) !== 7) {
                throw new Exception("Invalid School Centre Code detected: {$schoolCode}");
            }

            /* Check duplicate allocation */
            $conditions = [
                'where' => [
                    'schoolCode' => $schoolCode,
                    'examYear'   => $examYearId
                ],
                'return_type' => 'count'
            ];

            if ($model->getRows($tblName, $conditions) > 0) {
                $duplicates[] = $schoolCode;
                continue;
            }

            /* Insert allocation */
            $allocationData = [
                'schoolCode'   => $schoolCode,
                'examYear'     => $examYearId,
                'consultantID' => $consultantId
            ];

            if (!$model->insert_data($tblName, $allocationData)) {
                throw new Exception("Failed to allocate school {$schoolCode}");
            }

            $allocated[] = $schoolCode;
        }

        $model->commit();

        /* -------------------- ACTIVITY LOG -------------------- */
        if (!empty($allocated)) {
            $user->recordLog(
                $_SESSION['activeAdmin'],
                'Bulk School Allocation',
                sprintf(
                    'Consultant ID %s allocated %d school(s). Schools: %s',
                    $consultantId,
                    count($allocated),
                    implode(', ', $allocated)
                )
            );
        }

        /* -------------------- USER FEEDBACK -------------------- */
        $message = '';

        if (!empty($allocated)) {
            $message .= count($allocated) . ' school(s) allocated successfully. ';
        }

        if (!empty($duplicates)) {
            $message .= 'Duplicate skipped: ' . implode(', ', $duplicates);
        }

        $utility->redirectWithNotification('success', trim($message), 'manageschoolallocation');
    } catch (Exception $e) {

        $model->rollBack();

        error_log(
            "School Allocation Error: " . $e->getMessage() .
                " | Data: " . json_encode($_POST)
        );

        $utility->redirectWithNotification(
            'danger',
            'An error occurred while allocating schools. Operation rolled back.',
            'manageschoolallocation'
        );
    }
} elseif (
    isset($_POST['schoolunallocator']) ||
    $utility->inputDecode($_POST['schoolunallocator']) !== 'school_profile_unallocator_form'
) {
    $schoolCode = $_POST['schoolCode'];
    $examYear = $_POST['examYear'];
    /* Delete allocation */
    $deleteConditions = [
            'schoolCode' => $schoolCode,
            'examYear'   => $examYear
    ];

    if ($model->delete($tblName, $deleteConditions)) {
        /* -------------------- ACTIVITY LOG -------------------- */
        $user->recordLog(
            $_SESSION['activeAdmin'],
            'School Unallocation',
            "School code: {$schoolCode} unallocated successfully."
        );

        $utility->redirectWithNotification(
            'success',
            "School code: {$schoolCode} unallocated successfully.",
            'manageschoolallocation'
        );
    } else {
        $utility->redirectWithNotification('danger', "Failed to unallocate School code: {$schoolCode}.", 'manageschoolallocation');
    }
} else {
    $utility->redirectWithNotification('danger', 'Invalid request submission.', 'manageschoolallocation');
    exit;
}
