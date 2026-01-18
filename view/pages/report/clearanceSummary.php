<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg position-relative">
                    <!-- Watermark -->
                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="opacity: 0.1; font-size: 4rem; transform: rotate(-30deg);">
                        <strong>Originally Generated Report</strong>
                    </div>

                    <!-- Consultant Company Logo -->
                    <div class="card-header text-center">
                        <img id="consultantLogo" src="../../storage/app/moest.jpg" width="300" alt="MOEST Logo" />
                        <br>
                        <h3 class="mt-3"><b>Summary of WASSCE 2025 Biometrics and e-Registration Data Capturing Exercise</b></h3>
                    </div>

                    <div class="card-body">
                        <?php if (!empty($clearedSchoolRecords)) { ?>
                            <div class="card-header text-left">
                                <h4><strong>Consultant Company:</strong> <span id="consultantName"><?php echo htmlspecialchars($consultantDetails['companyName']); ?></span></h4>
                                <h4><strong>Number of Cleared Schools:</strong> <span id="clearedSchoolCount"><?php echo intval($numclearedSchoolRecords); ?></span></h4>
                                <h4><strong>Total Number of Candidates:</strong> <span id="totalCandidates"></span></h4>
                            </div>

                            <div class="table-responsive mt-3">
                                <table id="example" class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>S/N</th>
                                            <th>Zone</th>
                                            <th>Centre Number</th>
                                            <th>School Name</th>
                                            <th>School Type</th>
                                            <th>Number of Candidates</th>
                                            <th>Clearance Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        $totalCandidates = 0;
                                        foreach ($clearedSchoolRecords as $data) {
                                            $totalCandidates += $utility->inputDecode($data['numberCaptured']);
                                            $totalCand = $utility->number($totalCandidates);
                                        ?>

                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td class="text-center"><b><?php echo htmlspecialchars($data['lga']); ?></b></td>
                                                <td><b><?php echo htmlspecialchars($data['centreNumber']); ?></b></td>
                                                <td><b><?php echo htmlspecialchars($data['SchoolName']); ?></b></td>
                                                <td>
                                                    <?php
                                                    echo (intval($data['schType']) === 1) ? "Public" : ((intval($data['schType']) === 2) ? "Private" : "Unspecified");
                                                    ?>
                                                </td>
                                                <td class="text-center"><b><?php echo $utility->inputDecode($data['numberCaptured']); ?></b></td>
                                                <td>
                                                    <?php
                                                    echo ($data['clearanceStatus'] == 200) ? '<span class="badge bg-success">Cleared</span>' : (($data['clearanceStatus'] == 100) ? '<span class="badge bg-danger">Not Cleared</span>' :
                                                        '<span class="badge bg-warning">Unknown</span>');
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Full Name and Signature Section -->
                            <div class="mt-5 text-center">
                                <p><strong>Full Name:</strong> _________________________________________________________________________</p>
                                <br>
                                <p><strong>Designation:</strong> _________________________________________________________________________</p>
                                <br>
                                <p><strong>Signature:</strong> _________________________________________________________________________</p>
                                <p><strong>Printed: <?php echo date("d-m-Y"); ?></strong></p>
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

<script>
    document.getElementById("totalCandidates").textContent = "<?php echo $totalCand; ?>";

    $(document).ready(function() {
        $('#example').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 300,
            "order": [
                [0, "asc"]
            ],
            "dom": 'Bfrtip',
            "buttons": [{
                extend: 'print',
                text: 'Print Report',
                className: 'btn btn-info',
                customize: function(win) {
                    $(win.document.body).prepend(
                        '<div style="text-align: center; margin-bottom: 20px;">' +
                        '<img src="' + document.getElementById("consultantLogo").src + '" width="200"><br>' +
                        '<br><h3>Summary of WASSCE 2025 Biometrics and e-Registration Data Capturing Exercise</h3><br>' +
                        '<h4 style="text-align:left;">Consultant Company: ' + document.getElementById("consultantName").textContent + '</h4>' +
                        '<h4 style="text-align:left;">Number of Cleared Schools: ' + document.getElementById("clearedSchoolCount").textContent + '</h4>' +
                        '<h4 style="text-align:left;">Total Number of Candidates: ' + document.getElementById("totalCandidates").textContent + '</h4>' +
                        '</div>'
                    );
                    $(win.document.body).append(
                        '<br><p style="text-align:center;"><strong>Full Name:</strong> _____________________________________</p><br>' +
                        '<p style="text-align:center;"><strong>Designation:</strong> _____________________________________</p><br>' +
                        '<p style="text-align:center;"><strong>Signature:</strong> _____________________________________</p><br>' +
                        '<p style="text-align: center; font-size: 12px; margin-top: 20px;">Printed on: ' + new Date().toLocaleString() + '</p>'
                    );
                }
            }]
        }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
    });
</script>