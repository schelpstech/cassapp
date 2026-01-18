<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-secondary card-outline">
                    <div class="card-body box-profile">
                        <!-- Header Section -->
                        <div class="row">
                            <div class="col-lg-4 offset-lg-4 text-center">
                                <img src="../../storage/app/moest.jpg" alt="Logo" class="img-fluid">
                            </div>
                            <div class="col-lg-10 offset-lg-1">
                                <div class="text-center">
                                    <br>
                                    <h2><strong>Ogun State Ministry of Education, Science & Technology</strong></h2>
                                    <h3><strong>Biometrics & CASS 3 Clearance for WASSCE 2025</strong></h3>
                                </div>
                            </div>
                        </div>

                        <!-- Color Dividers -->
                        <hr style="background-color: orange; height: 5px; margin: 0;">
                        <hr style="background-color: yellow; height: 5px; margin: 0;">
                        <hr style="background-color: green; height: 5px; margin: 0;">
                        <br>

                        <!-- QR Code Section -->
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3 text-center">
                                <img src="<?php echo $generator->generateQRCode(
                                                'https://assoec.org/app/clearanceModule.php?verify_submitted_clearance_ID=' .
                                                    $utility->inputEncode($printClearanceInfo['Rem_uniquereference'])
                                            ); ?>" alt="QR Code" class="img-fluid">
                            </div>
                        </div>

                        <!-- Clearance Details -->
                        <ul class="list-group list-group-unbordered mb-3 mt-4">
                            <li class="list-group-item">
                                <b>Clearance ID:</b>
                                <span class="float-right">
                                    <h2><strong><?php echo $printClearanceInfo['Rem_uniquereference']; ?></strong></h2>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Centre Number:</b>
                                <span class="float-right">
                                    <h4><strong><?php echo $printClearanceInfo['centreNumber']; ?> </strong></h4>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>School Name:</b>
                                <span class="float-right">
                                    <h4><strong><?php echo $printClearanceInfo['SchoolName']; ?></strong></h4>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>School Zone:</b>
                                <span class="float-right">
                                    <h4><strong><?php echo $printClearanceInfo['lga']; ?></strong></h4>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>School Type:</b>
                                <span class="float-right">
                                    <h4><strong>
                                            <?php echo ($printClearanceInfo['schType'] == 1) ? 'Public School' : (($printClearanceInfo['schType'] == 2) ? 'Private School' : 'Unknown'); ?>
                                        </strong></h4>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Number of Candidates Cleared:</b>
                                <span class="float-right">
                                    <h4><strong><?php echo $utility->inputDecode($printClearanceInfo['numberCaptured']); ?></strong></h4>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Consultant Name:</b>
                                <span class="float-right">
                                    <h4><strong><?php echo $printClearanceInfo['companyName']; ?></strong></h4>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Date Issued:</b>
                                <span class="float-right">
                                    <h4><strong><?php echo $printClearanceInfo['clearanceDate']; ?></strong></h4>
                                </span>
                            </li>
                        </ul>

                        <!-- Officer Details -->
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item">
                                <b>Clearance Status:</b>
                                <span class="float-right">
                                    <h1><strong>
                                            <?php echo ($printClearanceInfo['clearanceStatus'] == 200) ? 'CLEARED' : (($printClearanceInfo['clearanceStatus'] == 100) ? 'PENDING' : 'Unknown'); ?>
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

<script>
    // Automatically trigger the print function when the page loads
    window.onload = function() {
        window.print();
    };
</script>