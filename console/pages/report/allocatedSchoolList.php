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
                                        <th>Action</th> <!-- New column for unallocate button -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($listofallocationDetails as $data) {
                                    ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo '<b>' . $data['schoolCode'] . " - " . $data['SchoolName'] ?? 'N/A' . '</b>'; ?></td>
                                            <td>
                                                <?php
                                                switch ($data['schType'] ?? 'N/A') {
                                                    case 1:
                                                        echo $data['lga'] . ' Public';
                                                        break;
                                                    case 2:
                                                        echo $data['lga'] . ' Private';
                                                        break;
                                                    default:
                                                        echo 'UnSpecified';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                switch ($data['clearanceStatus'] ?? 'N/A') {
                                                    case 200:
                                                        echo '<span class="badge badge-success d-flex align-items-center justify-content-center">
                                <i class="fas fa-check-circle me-2"></i> Cleared</span>';
                                                        break;
                                                    case 100:
                                                        echo '<span class="badge badge-warning d-flex align-items-center justify-content-center">
                                <i class="fas fa-check-times me-2"></i> Pending</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-danger d-flex align-items-center justify-content-center">
                                <i class="fas fa-times-circle me-2"></i> Not Recorded</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo '<b>' . $data['companyName'] ?? 'N/A' . '</b>'; ?></td>
                                            <td>
                                                <?php
                                                // Only show unallocate button if clearance is Not Recorded or Pending
                                                if (($data['clearanceStatus'] ?? 'N/A') === 'N/A' || ($data['clearanceStatus'] ?? 0) == 100) {
                                                ?>
                                                    <form method="post" action="../../appadmin/allocationModule.php" onsubmit="return confirm('Are you sure you want to unallocate this school?');">
                                                        <input type="hidden" name="schoolCode" value="<?php echo $data['schoolCode']; ?>">
                                                        <input type="hidden" name="examYear" value="<?php echo $data['examYear']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            name="schoolUnallocator"
                                                            value="<?php echo $utility->inputEncode('school_profile_unallocator_form'); ?>>
                                                            <i class=" fas fa-times-circle me-1"></i> Unallocate
                                                        </button>
                                                    </form>
                                                <?php
                                                } else {
                                                    echo '<span class="text-muted">Cannot Unallocate</span>';
                                                }
                                                ?>
                                            </td>

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