<?php
if (file_exists('../../controller/start.inc.php')) {
    include '../../controller/start.inc.php';
} elseif (file_exists('../controller/start.inc.php')) {
    include '../controller/start.inc.php';
} else {
    include './controller/start.inc.php';
};

function route($pageId)
{
    return '../../app/router.php?pageid=' . $GLOBALS['utility']->inputEncode($pageId);
}

if (!empty($_SESSION['active']) && isset($_SESSION['active'])) {
    $tblName = 'book_of_life';
    $conditions = [
        'return_type' => 'count',
        'where' => ['user_name' => $_SESSION['active']]
    ];
    $userExists = $model->getRows($tblName, $conditions);
    if ($userExists === 1) {
        $conditions = [
            'return_type' => 'single',
            'where' => ['user_name' => $_SESSION['active']],
            'joinl' => [
                'tbl_consultantdetails' => ' on tbl_consultantdetails.userid = book_of_life.userid',
            ]
        ];
        $consultantDetails = $model->getRows($tblName, $conditions);
        $_SESSION['activeID'] = $consultantDetails['userId'];
    }

    //Select Active Exam Year

    $tblName = 'examyear';
    $conditions = [
        'return_type' => 'single',
        'where' => ['activeStatus' => 1]
    ];
    $examYear = $model->getRows($tblName, $conditions);
    $_SESSION['examYear'] =  $examYear['year'];


// Select All Allocated Schools
$tblName = 'tbl_schoolallocation';
$conditions = [
    'where' => [
        'consultantID' => $_SESSION['activeID'],
    ],
    'where_raw' => "schoolCode NOT IN (
        SELECT recordSchoolCode FROM tbl_remittance 
        WHERE consultantID = " . $_SESSION['activeID'] . " 
        AND examYearRef = " . $examYear['id'] . "
    )",
    'joinl' => [
        'tbl_schoollist' => ' ON tbl_schoollist.centreNumber = tbl_schoolallocation.schoolCode',
        'examyear' => ' ON examyear.id = tbl_schoolallocation.examYear',
        'lga_tbl' => ' ON lga_tbl.waecCode = tbl_schoollist.lgaCode',
    ],
    'order_by' => 'SchoolName ASC',
];

$allocatedSchools = $model->getRows($tblName, $conditions);


    //Select Capturing Records 
    if (!empty($_SESSION['activeID']) && isset($_SESSION['activeID'])) {
        $tblName = 'tbl_remittance';
        $conditions = [
            'where' => [
                'examYearRef' => $examYear['id'],
                'submittedby' => $_SESSION['activeID']
            ],
            'joinl' => [
                'tbl_schoollist' => ' on tbl_schoollist.centreNumber = tbl_remittance.recordSchoolCode',
                'lga_tbl' => ' on lga_tbl.waecCode = tbl_schoollist.lgaCode',
            ],
            'order_by' => 'remRecTime ASC',
        ];
        $CapturingRecords = $model->getRows($tblName, $conditions);
    }

    //Select Capturing Records 
    if (!empty($_SESSION['activeID']) && isset($_SESSION['activeID'])) {
        $tblName = 'tbl_remittance';
        $conditions = [
            'where' => [
                'examYearRef' => $examYear['id'],
                'submittedby' => $_SESSION['activeID'],
                'clearanceStatus' => 200
            ],
            'joinl' => [
                'tbl_schoollist' => ' on tbl_schoollist.centreNumber = tbl_remittance.recordSchoolCode',
                'lga_tbl' => ' on lga_tbl.waecCode = tbl_schoollist.lgaCode',
            ],
            'order_by' => 'centreNumber ASC',
        ];
        $clearedSchoolRecords = $model->getRows($tblName, $conditions);

        $conditions = [
            'where' => [
                'examYearRef' => $examYear['id'],
                'submittedby' => $_SESSION['activeID'],
                'clearanceStatus' => 200
            ],
            'return_type' => 'count',
        ];
        $numclearedSchoolRecords = $model->getRows($tblName, $conditions);
    }

    if (!empty($_SESSION['pageid']) && isset($_SESSION['pageid']) && $_SESSION['pageid'] == 'modifyCapturing' || $_SESSION['pageid'] == 'addCandidates') {

        $tblName = 'tbl_remittance';
        $conditions = [
            'where' => [
                'examYearRef' => $examYear['id'],
                'submittedby' => $_SESSION['activeID'],
                'recordSchoolCode' => $utility->inputDecode($_SESSION['reference'])
            ],
            'joinl' => [
                'tbl_schoollist' => ' on tbl_schoollist.centreNumber = tbl_remittance.recordSchoolCode',
            ],
            'return_type' => 'single',
        ];
        $SelectedCapturingRecords = $model->getRows($tblName, $conditions);
    }



    // Select Capturing Records
    if (!empty($_SESSION['clearedSchool'])) {

        // Check if the school has paid
        $tblName = 'tbl_transaction';
        $conditions = [
            'where' => [
                'transStatus' => 1, // Only transactions with status 1 (successful)
                'transInitiator' => $_SESSION['active'], // Match the current user's active session
                'transExamYear' => 1, // Specific exam year reference
                'transactionType' => ['Bulk Payment', 'Additional Candidate', 'Individual School'], // Multiple valid types
            ]
        ];
        $checkPayment = $model->getRows($tblName, $conditions);

        // Check if payment records exist
        if (!empty($checkPayment)) {

            // Retrieve clearance information
            $tblName = 'tbl_remittance';
            $conditions = [
                'where' => [
                    'examYearRef' => $examYear['id'], // Match the exam year reference
                    'submittedby' => $_SESSION['activeID'], // Match the logged-in user's ID
                    'recordSchoolCode' => $_SESSION['clearedSchool'], // Match the school being queried
                    'clearanceStatus' => 200, // Ensure clearance status is '200' (approved)
                ],
                'joinl' => [
                    'tbl_schoollist' => ' ON tbl_schoollist.centreNumber = tbl_remittance.recordSchoolCode', // Join to school list table
                    'lga_tbl' => ' ON lga_tbl.waecCode = tbl_schoollist.lgaCode', // Join to local government table
                    'tbl_consultantdetails' => ' ON tbl_consultantdetails.userid = tbl_remittance.submittedby', // Join to consultant details
                ],
                'return_type' => 'single', // Expect a single result
            ];
            $printClearanceInfo = $model->getRows($tblName, $conditions);

            // Optionally, add further processing for $printClearanceInfo here
        }
    }

    //Transaction History
    if (!empty($_SESSION['pageid']) && isset($_SESSION['pageid']) && $_SESSION['pageid'] == 'transactionHistories') {
        // Check Payment History
        $tblName = 'tbl_transaction';
        $conditions = [
            'where' => [
                'transInitiator' => $_SESSION['active'], // Match the current user's active session
                'transExamYear' => 1, // Specific exam year reference
                'transactionType' => ['Bulk Payment', 'Additional Candidate', 'Individual School'], // Multiple valid types
            ]
        ];
        $transHistory = $model->getRows($tblName, $conditions);
    }

    //Dashboard Panel

    if (
        !empty($_SESSION['module']) && isset($_SESSION['module']) &&
        ($_SESSION['module'] == 'Dashboard' || $_SESSION['module'] == 'Clearance')
    ) {

        // Fetch allocated school count
        $allocatedSchoolNum = $model->getRows('tbl_schoolallocation', [
            'where' => ['consultantID' => $_SESSION['activeID']],
            'return_type' => 'count',
        ]);

        // Fetch cleared school count
        $clearedSchoolNum = $model->getRows('tbl_remittance', [
            'where' => [
                'submittedby' => $_SESSION['activeID'],
                'clearanceStatus' => 200,
            ],
            'return_type' => 'count',
        ]);

        // Sum up the total remittance paid
        // Retrieve remittance paid
        $totalRemittancePaid = $model->getRows('tbl_transaction', [
            'where' => [
                'transInitiator' => $_SESSION['active'],
                'transExamYear' => $examYear['id'],
                'transStatus' => 1
            ],
        ]);

        // Initialize the total figure
        $totalRemittedfigure = 0;

        // Calculate the total figure if remittance paid exists
        if (!empty($totalRemittancePaid)) {
            foreach ($totalRemittancePaid as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $amount = intval($utility->inputDecode($row['transAmount']));
                $totalRemittedfigure += $amount;
            }
        }



        // Retrieve remittance dues
        $remittanceDue = $model->getRows('tbl_remittance', [
            'where' => [
                'submittedby' => $_SESSION['activeID']
            ],
        ]);

        // Initialize the total figure
        $totalfigure = 0;

        // Calculate the total figure if remittance due exists
        if (!empty($remittanceDue)) {
            foreach ($remittanceDue as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $amount = intval($utility->inputDecode($row['amountdue']));
                $totalfigure += $amount;
            }
        }
    }

}
