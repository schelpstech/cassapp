<section class="content mt-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-12">

                <div class="card shadow border-0 position-relative">

                    <!-- WATERMARK -->
                    <div class="report-watermark"></div>

                    <div class="card-body">

                        <!-- HEADER -->
                        <div class="text-center mb-4">
                            <img id="consultantLogo" src="../../storage/app/moest.jpg" width="130" alt="MOEST Logo" />
                            <h4 class="mt-3 fw-bold text-uppercase">
                                Summary of WASSCE 2026 Biometrics & e-Registration Data Capturing Exercise
                            </h4>
                            <p class="text-muted mb-0">Official Consolidated Clearance Report</p>
                            <hr style="border-top:2px solid #000; width:80%; margin:15px auto;">
                        </div>

                        <?php if (!empty($clearedSchoolRecords)) { ?>

                            <?php
                                $count = 1;
                                $totalCandidates = 0;
                                foreach ($clearedSchoolRecords as $data) {
                                    $totalCandidates += $utility->inputDecode($data['numberCaptured']);
                                }
                                $totalCand = $utility->number($totalCandidates);
                            ?>

                            <!-- SUMMARY + QR -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Consultant Company:</th>
                                            <td><h4><?php echo htmlspecialchars($consultantDetails['companyName']); ?></h4></td>
                                        </tr>
                                        <tr>
                                            <th>Number of Cleared Schools:</th>
                                            <td><h4><?php echo intval($numclearedSchoolRecords); ?></h4></td>
                                        </tr>
                                        <tr>
                                            <th>Total Number of Candidates:</th>
                                            <td><h4><?php echo $totalCand; ?></h4></td>
                                        </tr>
                                        <tr>
                                            <th>Report Generated:</th>
                                            <td><?php echo date("d F Y"); ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-4 text-end">
                                    <div id="verificationQR"></div>
                                    <p class="small text-muted mt-2">Digital Verification Code</p>
                                </div>
                            </div>

                            <!-- PRINT BUTTON -->
                            <div class="text-end mb-3 no-print">
                                <button onclick="window.print()" class="btn btn-dark">
                                    Print Official Report
                                </button>
                            </div>

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered table-sm report-table">
                                    <thead>
                                        <tr class="text-center bg-white text-dark">
                                            <th>S/N</th>
                                            <th>Zone</th>
                                            <th>Centre Number</th>
                                            <th>School Name</th>
                                            <th>Type</th>
                                            <th>Candidates</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clearedSchoolRecords as $data) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $count++; ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['lga']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['centreNumber']); ?></td>
                                                <td><?php echo htmlspecialchars($data['SchoolName']); ?></td>
                                                <td class="text-center">
                                                    <?php
                                                        echo (intval($data['schType']) === 1) ? "Public" :
                                                            ((intval($data['schType']) === 2) ? "Private" : "Unspecified");
                                                    ?>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <?php echo $utility->inputDecode($data['numberCaptured']); ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                        echo ($data['clearanceStatus'] == 200)
                                                            ? '<span class="badge bg-success">CLEARED</span>'
                                                            : (($data['clearanceStatus'] == 100)
                                                                ? '<span class="badge bg-danger">NOT CLEARED</span>'
                                                                : '<span class="badge bg-warning">UNKNOWN</span>');
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-end">Grand Total Candidates</th>
                                            <th class="text-center"><?php echo $totalCand; ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- SIGNATURE -->
                            <div class="mt-5 pt-4 border-top">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <p><strong>Full Name</strong></p>
                                        <div class="signature-line"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Designation</strong></p>
                                        <div class="signature-line"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Signature </strong></p>
                                        <div class="signature-line"></div>
                                    </div>
                                </div>
                            </div>

                        <?php } else { ?>

                            <div class="alert alert-danger text-center">
                                No cleared schools found for the active exam year:
                                <strong><?php echo isset($_SESSION['examYear']) ? htmlspecialchars($_SESSION['examYear']) : 'N/A'; ?></strong>.
                            </div>

                        <?php } ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="page-number"></div>

<style>
.report-watermark{
    position:absolute;
    top:50%;
    left:50%;
    width:500px;
    height:500px;
    background:url('../../storage/app/moest.jpg') no-repeat center center;
    background-size:contain;
    opacity:0.05;
    transform:translate(-50%, -50%);
    z-index:0;
}
.card-body{ position:relative; z-index:2; }

.signature-line{
    border-bottom:1px solid #000;
    height:40px;
}

/* PRINT SETTINGS */
@media print {

    body{
        -webkit-print-color-adjust:exact;
    }

    @page{
        size:A4 portrait;
        margin:15mm;
    }

    .no-print{
        display:none !important;
    }

    .report-table{
        font-size:11px;
        width:100% !important;
        table-layout:fixed;
        word-wrap:break-word;
    }

    .page-number:after{
        counter-increment:page;
        content:"Page " counter(page);
        position:fixed;
        bottom:10mm;
        right:15mm;
        font-size:11px;
    }
    /* COLUMN WIDTH CONTROL */
.report-table th:nth-child(1),
.report-table td:nth-child(1) { width: 5%; }

.report-table th:nth-child(2),
.report-table td:nth-child(2) { width: 12%; }

.report-table th:nth-child(3),
.report-table td:nth-child(3) { width: 12%; }

.report-table th:nth-child(4),
.report-table td:nth-child(4) { width: 30%; }  /* SCHOOL NAME — BIGGER */

.report-table th:nth-child(5),
.report-table td:nth-child(5) { width: 10%; }

.report-table th:nth-child(6),
.report-table td:nth-child(6) { width: 11%; }

.report-table th:nth-child(7),
.report-table td:nth-child(7) { width: 10%; }
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
var verificationData = `
Official Clearance Report
Company: <?php echo addslashes($consultantDetails['companyName']); ?>
Total Candidates: <?php echo $totalCand; ?>
Generated: <?php echo date("d F Y"); ?>
`;

new QRCode(document.getElementById("verificationQR"), {
    text: verificationData,
    width:110,
    height:110
});
</script>