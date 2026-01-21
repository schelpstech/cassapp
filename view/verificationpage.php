
<!DOCTYPE html>
<html lang="en">
<?php
include '../app/query.php';

if(isset( $_SESSION['referencedSchoolForVerification']) && !empty( $_SESSION['referencedSchoolForVerification'])){
    $tblName = 'tbl_remittance';
    $conditions = [
        'where' => [
            'recordSchoolCode' => $_SESSION['referencedSchoolForVerification'], // Match the school being queried
            'clearanceStatus' => 200, // Ensure clearance status is '200' (approved)
        ],
        'joinl' => [
            'tbl_schoollist' => ' ON tbl_schoollist.centreNumber = tbl_remittance.recordSchoolCode', // Join to school list table
            'lga_tbl' => ' ON lga_tbl.waecCode = tbl_schoollist.lgaCode', // Join to local government table
            'tbl_consultantdetails' => ' ON tbl_consultantdetails.userid = tbl_remittance.submittedby', // Join to consultant details
        ],
        'return_type' => 'single', // Expect a single result
    ];
}else{
    $utility->setNotification('alert-danger', 'icon fas fa-ban', 'Invalid! Clearance ID does not exist in our record.');
    $utility->redirect('../view/verifyClearance.php');
}

$verifyClearanceInfo = $model->getRows($tblName, $conditions);

?>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clearance Verification | OGMoEST</title>

    <link rel="icon" type="image/x-icon" href="../storage/app/ogun.png">
    <link rel="stylesheet" href="./dist/css/font.css">
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./dist/css/adminlte.min2167.css?v=3.2.0">

    <style>
        body {
            background: #f4f6f9;
        }

        .certificate-card {
            position: relative;
            border: none;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 4.5rem;
            font-weight: 800;
            color: rgba(0, 135, 81, 0.12);
            white-space: nowrap;
            pointer-events: none;
            z-index: 1;
        }

        .candidate-count {
            font-size: 3rem;
            font-weight: 800;
            color: #008751;
        }

        .status-badge {
            font-size: 1.6rem;
            font-weight: 700;
            padding: 18px;
        }

        .table th {
            width: 35%;
        }
    </style>
</head>

<body class="hold-transition">

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card shadow-lg certificate-card">
                        <div class="card-body p-5">

                            <!-- Watermark -->
                            <div class="watermark">
                                <?php echo ($verifyClearanceInfo['clearanceStatus'] == 200) ? 'VERIFIED' : 'INVALID'; ?>
                            </div>

                            <!-- Header -->
                            <div class="text-center mb-4 position-relative" style="z-index:2;">
                                <img src="../storage/app/moest.jpg" alt="OGMoEST Logo" style="max-width:120px;" class="mb-3">
                                <h4 class="font-weight-bold text-uppercase">
                                    Ogun State Ministry of Education, Science & Technology
                                </h4>
                                <p class="mb-0">
                                    Biometrics & CASS 3 Clearance Verification
                                </p>
                                <small class="text-muted">WASSCE 2026</small>
                            </div>

                            <!-- Identity Strip -->
                            <div class="d-flex mb-4">
                                <div style="height:6px;background:#f7941d;flex:1;"></div>
                                <div style="height:6px;background:#ffd200;flex:1;"></div>
                                <div style="height:6px;background:#008751;flex:1;"></div>
                            </div>

                            <!-- Content -->
                            <div class="row position-relative" style="z-index:2;">
                                <div class="col-md-8">

                                    <table class="table table-borderless table-lg">
                                        <tr>
                                            <th>Clearance ID</th>
                                            <td class="font-weight-bold text-primary">
                                                <?php echo $verifyClearanceInfo['Rem_uniquereference']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Centre Number</th>
                                            <td><?php echo $verifyClearanceInfo['centreNumber']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>School Name</th>
                                            <td><?php echo $verifyClearanceInfo['SchoolName']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>School Zone (LGA)</th>
                                            <td><?php echo $verifyClearanceInfo['lga']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>School Type</th>
                                            <td>
                                                <?php echo ($verifyClearanceInfo['schType'] == 1) ? 'Public School' : 'Private School'; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Consultant</th>
                                            <td><?php echo $verifyClearanceInfo['companyName']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date Issued</th>
                                            <td><?php echo $verifyClearanceInfo['clearanceDate']; ?></td>
                                        </tr>
                                    </table>

                                </div>

                                <!-- Status Panel -->
                                <div class="col-md-4 text-center">
                                    <div class="candidate-count">
                                        <?php echo $utility->inputDecode($verifyClearanceInfo['numberCaptured']); ?>
                                    </div>
                                    <div class="text-muted mb-3">
                                        Candidates Cleared
                                    </div>

                                    <div class="badge badge-success status-badge">
                                        CLEARED & VERIFIED
                                    </div>

                                    <p class="mt-3 text-muted small">
                                        This clearance has been successfully verified in the OGMoEST system.
                                    </p>
                                </div>
                            </div>

                            <!-- Footer -->
                            <hr>
                            <div class="text-center small text-muted">
                                This verification result is system-generated and cannot be altered or forged.
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
<div id="warningMessage" style="display:none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; background-color: rgba(255, 0, 0, 0.7); color: white; font-size: 18px; border-radius: 10px;">
        Warning: Screenshot capture detected!
    </div>
    <script src="./plugins/jquery/jquery.min.js"></script>
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./dist/js/adminlte.min2167.js?v=3.2.0"></script>
    <script src="./plugins/custom/secure.js"></script>

</body>

</html>

