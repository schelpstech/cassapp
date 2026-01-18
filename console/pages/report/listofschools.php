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
                            Profiled Schools as at <?php echo date("d-m-Y"); ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($listofSchools)) { ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Centre Number</th>
                                        <th>School Name</th>
                                        <th>School Type</th>
                                        <th>LGA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($listofSchools as $data) {
                                    ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo '<b>' . $data['centreNumber'] ?? 'N/A'. '</b>'; ?></td>
                                            <td><?php echo '<b>' . $data['SchoolName'] ?? 'N/A' . '</b>'; ?></td>
                                            <td>
                                                <?php
                                                // Display status as buttons
                                                switch ($data['schType'] ?? 'N/A') {
                                                    case 1: // Public
                                                        echo '<span class="badge badge-success d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-check-circle me-2"></i> Public</span>';
                                                        break;
                                                    case 2: // Private
                                                        echo '<span class="badge badge-info d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-check-circle me-2"></i> Private</span>';
                                                        break;
                                                    default: // Unknown 
                                                        echo '<span class="badge badge-danger d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-times-circle me-2"></i> UnSpecified</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo '<b>' . $data['lga'] ?? 'N/A' . '</b>'; ?></td>
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