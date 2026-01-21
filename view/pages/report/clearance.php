<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card shadow-lg border-0 certificate-card">
                    <div class="card-body p-5">

                        <!-- Header -->
                        <div class="text-center mb-4">
                            <img src="../../storage/app/moest.jpg" alt="OGMoEST Logo" class="mb-3" style="max-width:120px;">
                            <h2 class="font-weight-bold text-uppercase">
                                Ogun State Ministry of Education, Science & Technology
                            </h2>
                            <h4 class="mb-0 font-weight-semibold">
                                Biometrics & CASS 3 Clearance Certificate
                            </h4>
                            <h6 class="text-muted">WASSCE 2026</h6>
                        </div>

                        <!-- Color Identity Strip -->
                        <div class="d-flex mb-4">
                            <div style="height:6px;background:#f7941d;flex:1;"></div>
                            <div style="height:6px;background:#ffd200;flex:1;"></div>
                            <div style="height:6px;background:#008751;flex:1;"></div>
                        </div>

                        <!-- Body -->
                        <div class="row">
                            <div class="col-md-8">

                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th>Clearance ID</th>
                                        <td class="font-weight-bold text-primary h5">
                                            <?php echo $printClearanceInfo['Rem_uniquereference']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Centre Number</th>
                                        <td><?php echo $printClearanceInfo['centreNumber']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>School Name</th>
                                        <td><?php echo $printClearanceInfo['SchoolName']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>School Zone (LGA)</th>
                                        <td><?php echo $printClearanceInfo['lga']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>School Type</th>
                                        <td>
                                            <?php
                                            echo ($printClearanceInfo['schType'] == 1)
                                                ? 'Public School'
                                                : 'Private School';
                                            ?>
                                        </td>
                                    </tr>

                                    <!-- Pronounced Candidate Count -->
                                    <tr>
                                        <th class="align-middle">Candidates Cleared</th>
                                        <td class="text-success font-weight-bold display-4">
                                            <?php echo $utility->inputDecode($printClearanceInfo['numberCaptured']); ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Consultant</th>
                                        <td><?php echo $printClearanceInfo['companyName']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date Issued</th>
                                        <td><?php echo $printClearanceInfo['clearanceDate']; ?></td>
                                    </tr>
                                </table>

                            </div>

                            <!-- QR + STATUS -->
                            <div class="col-md-4 text-center">

                                <!-- Larger QR Code -->
                                <img
                                    src="<?php echo $generator->generateQRCode(
                                                'https://ogmoestconsultants.com/app/clearanceModule.php?verify_submitted_clearance_ID=' .
                                                    $utility->inputEncode($printClearanceInfo['Rem_uniquereference'])
                                            ); ?>"
                                    class="img-fluid mb-4 border p-2"
                                    style="max-width:260px;">


                                <!-- Strong Clearance Badge -->
                                <div class="badge badge-success p-4" style="font-size:1.6rem;">
                                    <?php echo $utility->inputDecode($printClearanceInfo['numberCaptured']); ?><br>
                                    <span style="font-size:1rem;">Candidates</span><br>
                                    CLEARED
                                </div>

                                <p class="mt-3 text-muted small">
                                    Scan QR code to verify authenticity
                                </p>
                            </div>
                        </div>


                        <!-- Footer -->
                        <hr>
                        <div class="text-center small text-muted">
                            This clearance certificate is system-generated and electronically verifiable.
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
.certificate-card th {
    width: 220px;
    font-weight: 600;
}

.certificate-card .display-4 {
    line-height: 1;
}

.certificate-card img {
    background: #fff;
}
</style>
