<?php
if (file_exists('../../controller/start.inc.php')) {
    include '../../controller/start.inc.php';
} elseif (file_exists('../controller/start.inc.php')) {
    include '../controller/start.inc.php';
} else {
    include './controller/start.inc.php';
};


if (!empty($_SESSION['activeAdmin']) && isset($_SESSION['activeAdmin'])) {
    $tblName = 'book_of_life_admin';
    $conditions = [
        'return_type' => 'count',
        'where' => ['user_name' => $_SESSION['activeAdmin']]
    ];
    $userExists = $model->getRows($tblName, $conditions);
    if ($userExists === 1) {
        $conditions = [
            'return_type' => 'single',
            'where' => ['user_name' => $_SESSION['activeAdmin']],
        ];
        $consultantDetails = $model->getRows($tblName, $conditions);
        $_SESSION['activeAdmin'] = $consultantDetails['user_name'];
    } else {
        $model->log_out_user();
        session_start();
        $utility->setNotification('alert-success', 'icon fas fa-check', 'OOps. Sign in Again');
        $utility->redirect('../console/index.php');
    }
    //Select Active Exam Year

    $tblName = 'examyear';
    $conditions = [
        'return_type' => 'single',
        'where' => ['activeStatus' => 1]
    ];
    $examYear = $model->getRows($tblName, $conditions);
    $_SESSION['examYear'] =  $examYear['year'];

    //Count the number of Consultants to generate new usercode
    $tblName = 'tbl_consultantdetails';
    $conditions = [
        'return_type' => 'count'
    ];
    $cntUsers = $model->getRows($tblName, $conditions);

    //Select all Zones
    $tblName = 'lga_tbl';
    $conditions = [
        'orderby' => 'waecCode ASC',
    ];
    $zonelist = $model->getRows($tblName, $conditions);

    $tblName = 'book_of_life';
    $conditions = [
        'joinl' => [
            'tbl_consultantdetails' => ' on book_of_life.userid = tbl_consultantdetails.userId'
        ]
    ];
    $consultantList = $model->getRows($tblName, $conditions);


    if (
        !empty($_SESSION['module']) && isset($_SESSION['module']) &&
        ($_SESSION['module'] == 'Dashboard' || $_SESSION['module'] == 'Clearance')
    ) {


        //Dashboard One
        // Fetch profiled school count
        $tblName = 'tbl_schoollist';
        $conditions = [
            'return_type' => 'count',
        ];
        $profileSchools = $model->getRows($tblName, $conditions);

        // Fetch allocated school count
        $tblName = 'tbl_schoolallocation';
        $conditions = [
            'where' => [
                'examYear' => $examYear['id'],
            ],
            'return_type' => 'count',
        ];
        $allocatedSchoolNum = $model->getRows($tblName, $conditions);


        // Fetch cleared school count
        $clearedSchoolNum = $model->getRows('tbl_remittance', [
            'where' => [
                'examYearRef' => $examYear['id'],
                'clearanceStatus' => 200,
            ],
            'return_type' => 'count',
        ]);

        // Fetch cleared school count
        $unclearedSchoolNum = $model->getRows('tbl_remittance', [
            'where' => [
                'examYearRef' => $examYear['id'],
                'clearanceStatus' => 100,
            ],
            'return_type' => 'count',
        ]);


        //Dashboard Two       
        // Sum up the total remittance paid
        // Retrieve remittance paid
        $totalRemittancePaid = $model->getRows('tbl_remittance', [
            'where' => [
                'examYearRef' => $examYear['id'],
                'clearanceStatus' => 200
            ],
        ]);

        // Initialize the total figure
        $totalRemittedfigure = 0;
        $totalRemittedNumber = 0;

        // Calculate the total figure if remittance paid exists
        if (!empty($totalRemittancePaid)) {
            foreach ($totalRemittancePaid as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $amount = floatval($utility->inputDecode($row['amountdue']));
                $totalRemittedfigure += $amount;
            }
        }
        if (!empty($totalRemittancePaid)) {
            foreach ($totalRemittancePaid as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $numberRemitted = floatval($utility->inputDecode($row['numberCaptured']));
                $totalRemittedNumber += $numberRemitted;
            }
        }



        // Retrieve remittance dues
        $remittanceDue = $model->getRows('tbl_remittance', [
            'where' => [
                'examYearRef' => $examYear['id']
            ]
        ]);

        // Initialize the total figure
        $totalfigure = 0;
        $totalCandidate = 0;

        // Calculate the total figure if remittance due exists
        if (!empty($remittanceDue)) {
            foreach ($remittanceDue as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $amount = floatval($utility->inputDecode($row['amountdue']));
                $totalfigure += $amount;
            }
        }
        // Calculate the total numberCaptured if remittance due exists
        if (!empty($remittanceDue)) {
            foreach ($remittanceDue as $row) {
                // Decode 'numberCaptured' and ensure it's a valid float
                $Candidate = floatval($utility->inputDecode($row['numberCaptured']));
                $totalCandidate += $Candidate;
            }
        }

        // Retrieve School Demography dues
        $numberPrivate = $model->getRows('tbl_remittance', [
            'where' => [
                'examYearRef' => $examYear['id'],
                'clearanceStatus' => 200,
                'schType' => 2
            ],
            'joinl' => [
                'tbl_schoollist' => ' on tbl_schoollist.centreNumber = tbl_remittance.recordSchoolCode'
            ]
        ]);
        // Initialize the total figure
        $totalNumberPrivate = 0;
        $totalamountPrivate = 0;
        // Calculate the total figure if remittance due exists
        if (!empty($numberPrivate)) {
            foreach ($numberPrivate as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $privateNum = floatval($utility->inputDecode($row['numberCaptured']));
                $totalNumberPrivate += $privateNum;
            }
        }
        // Calculate the total figure if remittance due exists
        if (!empty($numberPrivate)) {
            foreach ($numberPrivate as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $privateAmount = floatval($utility->inputDecode($row['amountdue']));
                $totalamountPrivate += $privateAmount;
            }
        }

        // Retrieve School Demography dues
        $numberPublic = $model->getRows('tbl_remittance', [
            'where' => [
                'examYearRef' => $examYear['id'],
                'clearanceStatus' => 200,
                'schType' => 1
            ],
            'joinl' => [
                'tbl_schoollist' => ' on tbl_schoollist.centreNumber = tbl_remittance.recordSchoolCode'
            ]
        ]);
        // Initialize the total figure
        $totalNumberPublic = 0;
        $totalamountPublic = 0;
        // Calculate the total figure if remittance due exists
        if (!empty($numberPublic)) {
            foreach ($numberPublic as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $publicNum = floatval($utility->inputDecode($row['numberCaptured']));
                $totalNumberPublic += $publicNum;
            }
        }
        if (!empty($numberPublic)) {
            foreach ($numberPublic as $row) {
                // Decode 'amountdue' and ensure it's a valid float
                $publicAmount = floatval($utility->inputDecode($row['amountdue']));
                $totalamountPublic += $publicAmount;
            }
        }
    }

    //Summary of CLearance By Schools
    $tblName = 'tbl_remittance';
    $conditions = [
        'where' => [
            'examYearRef' => $examYear['id'],
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
            'clearanceStatus' => 200
        ],
        'return_type' => 'count',
    ];
    $numclearedSchoolRecords = $model->getRows($tblName, $conditions);
    // SUmmary Ends
    if (
        isset($_SESSION['module']) &&
        ($_SESSION['module'] == 'Report' || $_SESSION['module'] == 'Clearance')
    ) {
        $tblName = 'book_of_life';
        $conditions = [
            'joinl' => [
                'tbl_consultantdetails' => ' on book_of_life.userid = tbl_consultantdetails.userId'
            ]
        ];
        $userlists = $model->getRows($tblName, $conditions);

        if (!empty($userlists)) {
            // Collect all consultant IDs
            $consultantIds = array_map(fn($row) => $row['userid'], $userlists);

            // Fetch school allocation counts
            $tblName = 'tbl_schoolallocation';
            $conditions = [
                'where_in' => ['consultantID' => $consultantIds],
                'where' => ['examYear' => $examYear['id']],
                'group_by' => 'consultantID',
                'select' => 'consultantID, COUNT(*) AS allocations_count',
            ];
            $allocationCounts = $model->getRows($tblName, $conditions);

            // Fetch allocation details
            $conditions = [
                'where_in' => ['consultantID' => $consultantIds],
                'where' => ['examYear' => $examYear['id']],
                'joinl' => [
                    'tbl_schoollist' => ' on tbl_schoollist.centreNumber = tbl_schoolallocation.schoolCode',
                    'lga_tbl' => ' on lga_tbl.waecCode = tbl_schoollist.lgaCode'
                ],
                'select' => 'tbl_schoolallocation.consultantID, lga_tbl.waecCode AS waecCode, lga_tbl.lga AS allocated_zone, tbl_schoollist.schType AS allocated_type',
            ];
            $allocationDetails = $model->getRows($tblName, $conditions);

            // Fetch cleared count
            $tblName = 'tbl_remittance';
            $conditions = [
                'where_in' => ['submittedby' => $consultantIds],
                'where' => ['examYearRef' => $examYear['id'], 'clearanceStatus' => 200],
                'group_by' => 'submittedby',
                'select' => 'submittedby, COUNT(*) AS cleared_count',
            ];
            $clearedCount = $model->getRows($tblName, $conditions);

            // Fetch remittance amounts
            $conditions = [
                'where_in' => ['submittedby' => $consultantIds],
                'where' => ['examYearRef' => $examYear['id'], 'clearanceStatus' => 200],
                'select' => 'submittedby as remitted_submittedby, amountdue as remitted_amountdue, numberCaptured as remitted_numberCaptured ', // Fetch raw amountdue for decoding
            ];
            $remittanceAmount = $model->getRows($tblName, $conditions);

            // Fetch pending remittance amounts
            $conditions = [
                'where_in' => ['submittedby' => $consultantIds],
                'where' => ['examYearRef' => $examYear['id'], 'clearanceStatus' => 100],
                'select' => 'submittedby, amountdue, numberCaptured', // Fetch raw amountdue for decoding
            ];
            $unremittedAmount = $model->getRows($tblName, $conditions);

            // Process and map data to consultants
            foreach ($userlists as &$row) {
                $row['allocated_candidates'] = 0;
                $row['cleared_count'] = 0;
                $row['allocated_zone'] = '';
                $row['allocated_type'] = '';
                $row['waecCode'] = '';
                $row['amount_remitted'] = 0; // Initialize
                $row['number_captured'] = 0; // Initialize
                $row['unremitted_amount'] = 0; // Initialize
                $row['unremitted_num'] = 0; // Initialize
                if (!empty($allocationCounts)) {
                    // Map allocation count
                    foreach ($allocationCounts as $allocation) {
                        if ($allocation['consultantID'] == $row['userid']) {
                            $row['allocated_candidates'] = $allocation['allocations_count'];
                            break;
                        }
                    }
                }
                if (!empty($clearedCount)) {
                    // Map cleared count
                    foreach ($clearedCount as $clearance) {
                        if ($clearance['submittedby'] == $row['userid']) {
                            $row['cleared_count'] = $clearance['cleared_count'];
                            break;
                        }
                    }
                }
                if (!empty($allocationDetails)) {
                    // Map allocation details
                    foreach ($allocationDetails as $allocationDtl) {
                        if ($allocationDtl['consultantID'] == $row['userid']) {
                            $row['allocated_zone'] = $allocationDtl['allocated_zone'];
                            $row['allocated_type'] = $allocationDtl['allocated_type'];
                            $row['waecCode'] = $allocationDtl['waecCode'];
                            break;
                        }
                    }
                }
                if (!empty($remittanceAmount)) {
                    // Decode and sum remittance amounts
                    foreach ($remittanceAmount as $remit) {
                        if ($remit['remitted_submittedby'] == $row['userid']) {
                            $decodedAmount = intval($utility->inputDecode($remit['remitted_amountdue'] ?? '0'));
                            $decodednumber = intval($utility->inputDecode($remit['remitted_numberCaptured'] ?? '0'));
                            $row['amount_remitted'] += $decodedAmount;
                            $row['number_captured'] += $decodednumber;
                        }
                    }
                }
                if (!empty($unremittedAmount)) {
                    // Decode and sum UNremitted amount and number
                    foreach ($unremittedAmount as $noremit) {
                        if ($noremit['submittedby'] == $row['userid']) {
                            $decodedAmount_no = intval($utility->inputDecode($noremit['amountdue'] ?? '0'));
                            $decodednumber_no = intval($utility->inputDecode($noremit['numberCaptured'] ?? '0'));
                            $row['unremitted_amount'] += $decodedAmount_no;
                            $row['unremitted_num'] += $decodednumber_no;
                        }
                    }
                }
            }
        }
    }


    //Transaction History
    if (!empty($_SESSION['pageid']) && $_SESSION['pageid'] == 'reportTransactionHistory') {
        // Check Payment History
        $tblName = 'tbl_transaction';
        $conditions = [
            'where' => [
                'transExamYear' => 1 // Specific exam year reference
            ],
            'order_by' => 'transRectime DESC',
        ];
        $transHistory = $model->getRows($tblName, $conditions);
    }

    //List of Schools
    if (!empty($_SESSION['pageid']) && $_SESSION['pageid'] == 'manageschoollist') {
        // Check Payment History
        $tblName = 'tbl_schoollist';
        $conditions = [
            'joinl' => [
                'lga_tbl' => ' on lga_tbl.waecCode = tbl_schoollist.lgaCode'
            ],
            'order_by' => 'lgaCode ASC',
        ];
        $listofSchools = $model->getRows($tblName, $conditions);
    }

    // Fetch the list of schools allocated for each consultant in one query
    $tblName = 'tbl_schoolallocation';
    $conditions = [
        'where' => [
            'examYear' => $examYear['id'],
        ],
        'joinl' => [
            'tbl_consultantdetails' => ' on tbl_consultantdetails.userId = tbl_schoolallocation.consultantID',
            'tbl_schoollist' => ' on tbl_schoollist.centreNumber = tbl_schoolallocation.schoolCode',
            'lga_tbl' => ' on lga_tbl.waecCode = tbl_schoollist.lgaCode',
            'tbl_remittance' => ' on tbl_remittance.recordSchoolCode = tbl_schoolallocation.schoolCode'
        ],
        'order_by' => 'lgaCode ASC, centreNumber ASC',
    ];
    // Get the school allocation types for each consultant
    $listofallocationDetails = $model->getRows($tblName, $conditions);
}
