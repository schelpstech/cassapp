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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Page - CASS 3 Clearance Portal</title>
    <link rel="icon" type="image/x-icon" href="../storage/app/ogun.png">
    <link rel="stylesheet" href="./dist/css/font.css">
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="./dist/css/adminlte.min2167.css?v=3.2.0">
    <style>
        /* Watermark Styles */
        .watermark {
            position: fixed; /* Fixed position so it stays in place */
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Adjust to exact center */
            z-index: 9999; /* Ensure it appears above content */
            font-size: 5rem; /* Large text */
            color: rgba(255, 0, 0, 0.2); /* Light red with transparency */
            font-weight: bold; /* Make the text bold */
            pointer-events: none; /* Ensure the watermark does not block interactions */
            user-select: none; /* Disable text selection */
            white-space: nowrap;
            text-transform: uppercase; /* Make the text uppercase */
        }
    </style>
</head>

<body class="hold-transition login-page">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-secondary card-outline">
                        <div class="card-body box-profile">
                            <!-- Watermark added here -->
                            <div class="watermark"><?php echo ($verifyClearanceInfo['clearanceStatus'] == 200) ? 'VALID CASS 3 CLEARANCE ' : (($verifyClearanceInfo['clearanceStatus'] == 100) ? 'PENDING' : 'Unknown'); ?></div>

                            <!-- Header Section -->
                            <div class="row">
                                <div class="col-lg-4 offset-lg-4 text-center">
                                    <img src="../storage/app/moest.jpg" alt="Logo" class="img-fluid">
                                </div>
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="text-center">
                                        <br>
                                        <h2><strong>Ogun State Ministry of Education, Science & Technology</strong></h2>
                                        <h3><strong>Biometrics & CASS 3 Clearance for WASSCE 2025</strong></h3>
                                        <br><h2><strong>Clearance Verification Page</strong></h2>
                                    </div>
                                </div>
                            </div>

                            <!-- Color Dividers -->
                            <hr style="background-color: orange; height: 5px; margin: 0;">
                            <hr style="background-color: yellow; height: 5px; margin: 0;">
                            <hr style="background-color: green; height: 5px; margin: 0;">
                            <br>

                            <!-- Clearance Details -->
                            <ul class="list-group list-group-unbordered mb-3 mt-4">
                                <li class="list-group-item">
                                    <b>Clearance ID:</b>
                                    <span class="float-right">
                                        <h2><strong><?php echo $verifyClearanceInfo['Rem_uniquereference']; ?></strong></h2>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Centre Number:</b>
                                    <span class="float-right">
                                        <h4><strong><?php echo $verifyClearanceInfo['centreNumber']; ?> </strong></h4>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>School Name:</b>
                                    <span class="float-right">
                                        <h4><strong><?php echo $verifyClearanceInfo['SchoolName']; ?></strong></h4>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>School Zone:</b>
                                    <span class="float-right">
                                        <h4><strong><?php echo $verifyClearanceInfo['lga']; ?></strong></h4>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>School Type:</b>
                                    <span class="float-right">
                                        <h4><strong>
                                                <?php echo ($verifyClearanceInfo['schType'] == 1) ? 'Public School' : (($verifyClearanceInfo['schType'] == 2) ? 'Private School' : 'Unknown'); ?>
                                            </strong></h4>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Number of Candidates Cleared:</b>
                                    <span class="float-right">
                                        <h4><strong><?php echo $utility->inputDecode($verifyClearanceInfo['numberCaptured']); ?></strong></h4>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Consultant Name:</b>
                                    <span class="float-right">
                                        <h4><strong><?php echo $verifyClearanceInfo['companyName']; ?></strong></h4>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Date Issued:</b>
                                    <span class="float-right">
                                        <h4><strong><?php echo $verifyClearanceInfo['clearanceDate']; ?></strong></h4>
                                    </span>
                                </li>
                            </ul>

                            <!-- Officer Details -->
                            <ul class="list-group list-group-unbordered">

                                <li class="list-group-item">
                                    <b>Clearance Status:</b>
                                    <span class="float-right">
                                        <h1><strong>
                                                <?php echo ($verifyClearanceInfo['clearanceStatus'] == 200) ? 'CLEARED' : (($verifyClearanceInfo['clearanceStatus'] == 100) ? 'PENDING' : 'Unknown'); ?>
                                            </strong></h1>
                                    </span>
                                </li>
                            </ul>
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
