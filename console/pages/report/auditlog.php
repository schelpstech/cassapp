
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
                            Activity Logs as at <?php echo date("d-m-Y"); ?>
                        </h3>
                    </div>

                    <div class="card-body">
                        <?php if (!empty($logHistory)) { ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Date & Time</th>
                                        <th>User</th>
                                        <th>IP Address</th>
                                        <th>Activity</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($logHistory as $log) {
                                        $logDate = date("d-m-Y H:i:s", strtotime($log['rectime']));
                                        $userName = htmlspecialchars($log['user_name']);
                                        $userIp = htmlspecialchars($log['uip']);
                                        $activity = htmlspecialchars($log['activity']);
                                        $description = htmlspecialchars($log['description']);
                                    ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><b><?php echo $logDate; ?></b></td>
                                            <td><b><?php echo $userName; ?></b></td>
                                            <td><?php echo $userIp; ?></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo $activity; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $description; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <div class="alert alert-info text-center">
                                No activity logs found.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
