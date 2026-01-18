<script>
    $(document).ready(function() {
        $('#example1').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 10,
            "order": [
                [0, "asc"]
            ], // Sorting by S/N
            "buttons": ["copy", "csv", "excel", "pdf", "print"] // Export buttons
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            List of Allocated Schools as at <?php echo date("d-m-Y"); ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($listofallocationDetails)) { ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>School Name</th>
                                        <th>School LGA & Type</th>
                                        <th>Clearance Status</th>
                                        <th>Consultant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($listofallocationDetails as $data) {
                                    ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo '<b>' .$data['schoolCode'] . " - ". $data['SchoolName'] ?? 'N/A' . '</b>'; ?></td>
                                            <td>
                                                <?php
                                                // Display status as buttons
                                                switch ($data['schType'] ?? 'N/A') {
                                                    case 1: // Public
                                                        echo $data['lga'].' Public';
                                                        break;
                                                    case 2: // Private
                                                        echo $data['lga'].' Private';
                                                        break;
                                                    default: // Unknown 
                                                        echo 'UnSpecified';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Display Clearance status as buttons
                                                switch ($data['clearanceStatus'] ?? 'N/A') {
                                                    case 200: // Cleared
                                                        echo '<span class="badge badge-success d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-check-circle me-2"></i> Cleared</span>';
                                                        break;
                                                    case 100: // Pending CLearance
                                                        echo '<span class="badge badge-warning d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-check-times me-2"></i> Pending</span>';
                                                        break;
                                                    default: // Not Recorded 
                                                        echo '<span class="badge badge-danger d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-times-circle me-2"></i> Not Recorded</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo '<b>' . $data['companyName'] ?? 'N/A' . '</b>'; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <!-- Display message if no transactions -->
                            <div class="alert alert-info text-center">
                                You have not registered any School:
                                <strong><?php echo htmlspecialchars($_SESSION['examYear']); ?></strong>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>