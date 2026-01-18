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
                            Transaction History as at <?php echo date("d-m-Y"); ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($transHistory)) { ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Transaction Date</th>
                                        <th>Transaction Reference</th>
                                        <th>Transaction Type</th>
                                        <th>Transaction Amount</th>
                                        <th>Transaction Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($transHistory as $data) {
                                        $transactionDate = isset($data['transRectime']) ? date("d-m-Y H:i:s", strtotime($data['transRectime'])) : 'N/A';
                                        $transactionRef = $data['transactionRef'] ?? 'N/A';
                                        $transactionType = $data['transactionType'] ?? 'N/A';
                                        $transactionAmount = isset($data['transAmount'])
                                            ? $utility->money($utility->inputDecode($data['transAmount']))
                                            : 'N/A';
                                        $paymentStatus = intval($data['transStatus'] ?? -1);
                                    ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo '<b>' . $transactionDate . '</b>'; ?></td>
                                            <td><?php echo '<b>' . $transactionRef . '</b>'; ?></td>
                                            <td><?php echo '<b>' . $transactionType . '</b>'; ?></td>
                                            <td class="text-center"><?php echo '<b>' . $transactionAmount . '</b>'; ?></td>
                                            <td>
                                                <?php
                                                // Display status as buttons
                                                switch ($paymentStatus) {
                                                    case 1: // Success
                                                        echo '<span class="badge badge-success d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-check-circle me-2"></i> Success</span>';
                                                        break;
                                                    case 0: // Pending
                                                        echo '<span class="badge badge-danger d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-times-circle me-2"></i> Failed</span>';
                                                        break;
                                                    default: // Unknown or failed status
                                                        echo '<span class="badge badge-danger d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-times-circle me-2"></i> Failed</span>';
                                                        break;
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
                                You have not made any transactions in the active exam year:
                                <strong><?php echo htmlspecialchars($_SESSION['examYear']); ?></strong>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>