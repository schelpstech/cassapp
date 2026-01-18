
<?php
include 'adminquery.php';
// Validate and decode pageid
$pageId = isset($_GET['pageid']) ? $utility->inputDecode($_GET['pageid']) : 'consoleDashboard';

// Define navigation settings
$navigationSettings = [
    'consoleDashboard' => [
        'pageid' => 'consoleDashboard',
        'page_name' => 'Dashboard',
        'module' => 'Dashboard',
        'reference' => null,
        'clearedSchool' => null
    ],
    'consultantRecord' => [
        'pageid' => 'consultantRecord',
        'page_name' => 'Approved Consultant List',
        'module' => 'Report',
        'reference' => null,
        'clearedSchool' => null
    ],
    'reportConsultantClearance' => [
        'pageid' => 'reportConsultantClearance',
        'page_name' => 'Report of Clearance Issued by Consultants',
        'module' => 'Report',
        'reference' => null,
        'clearedSchool' => null
    ],
    'consultantClearedSchools' => [
        'pageid' => 'consultantClearedSchools',
        'page_name' => 'Summary of Cleared Schools',
        'module' => 'Clearance',
        'reference' => null,
        'clearedSchool' => null
    ],
    'manageschoollist' => [
        'pageid' => 'manageschoollist',
        'page_name' => 'List of Schools',
        'module' => 'Schools',
        'reference' => null,
        'clearedSchool' => null
    ],
    'manageschoolallocation' => [
        'pageid' => 'manageschoolallocation',
        'page_name' => 'List of Allocated Schools',
        'module' => 'Schools',
        'reference' => null,
        'clearedSchool' => null
    ],
    'consultantpwdMgr' => [
        'pageid' => 'consultantpwdMgr',
        'page_name' => 'Change Login Credential',
        'module' => 'Dashboard',
        'reference' => null,
        'clearedSchool' => null
    ],
    'reportTransactionHistory' => [
        'pageid' => 'reportTransactionHistory',
        'page_name' => 'All Transaction History',
        'module' => 'Clearance',
        'reference' => null,
        'clearedSchool' => null
    ],
    'addCandidates' => [
        'pageid' => 'addCandidates',
        'page_name' => 'Generate Clearance for Additional Captured Candidates',
        'module' => 'Clearance',
        'reference' => !empty($_GET['reference']) ? $_GET['reference'] : null,
        'clearedSchool' => null
    ]
];

// Check if pageId exists in settings, otherwise default to 'consultantDashboard'
if (array_key_exists($pageId, $navigationSettings)) {
    $_SESSION['pageid'] = $navigationSettings[$pageId]['pageid'];
    $_SESSION['page_name'] = $navigationSettings[$pageId]['page_name'];
    $_SESSION['module'] = $navigationSettings[$pageId]['module'];
    $_SESSION['reference'] = $navigationSettings[$pageId]['reference'];
    $_SESSION['clearedSchool'] = $navigationSettings[$pageId]['clearedSchool'];

} else {
    // Default to 'consultantDashboard'
    $_SESSION['pageid'] = $navigationSettings['consoleDashboard']['pageid'];
    $_SESSION['page_name'] = $navigationSettings['consoleDashboard']['page_name'];
    $_SESSION['module'] = $navigationSettings['consoleDashboard']['module'];
}

// Redirect to the appropriate page
$utility->redirect('../console/pages/viewer.php?pageid=' . $utility->inputEncode($pageId));



?>