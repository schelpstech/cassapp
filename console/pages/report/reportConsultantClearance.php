<script>
    $(document).ready(function() {
        $('#example1').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 100,
            "order": [
                [1, "asc"]
            ], // Sorting by S/N
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"], // Export buttons
            "language": {
                "emptyTable": "No data available", // Custom message for empty table
                "info": "Showing _START_ to _END_ of _TOTAL_ records", // Pagination info
            }
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 offset-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Report of Clearance Issued by Consultants
                            <?php echo date("d-m-Y") ?>
                        </h3>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Allocated Zone</th>
                                    <th>Company Name</th>
                                    <th>Allocated Schools</th>
                                    <th>Cleared Schools</th>
                                    <th>Pending Schools</th>
                                    <th>Number Remitted</th>
                                    <th>Remittance Amount</th>
                                    <th>Number Unremitted</th>
                                    <th>Unremitted Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($userlists)) {
                                    foreach ($userlists as $data) {
                                        // Conditionally set the allocated_type text
                                        $count = 1;
                                        if ($data['allocated_type'] == 1) {
                                            $allocated_type = 'Public';
                                            $rowColor = '#d4edda'; // Green for Public
                                        } elseif ($data['allocated_type'] == 2) {
                                            $allocated_type = 'Private';
                                            $rowColor = '#f8d7da'; // Red for Private
                                        } else {
                                            $allocated_type = 'Unknown'; // In case of an unexpected value
                                            $rowColor = '#ffffff'; // Default row color
                                        }
                                ?>
                                        <tr style="background-color: <?php echo $rowColor; ?>;">

                                            <td>
                                                <?php echo '<b>' .substr($data['waecCode'], -2) . '</b>'; ?>
                                            </td>
                                            <td>
                                                <!-- Tooltip added for allocated type -->
                                                <span title="<?php echo $allocated_type; ?>">
                                                    <?php echo '<b>' . $data['allocated_zone'] . ' - ' . $allocated_type . '</b>'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $data['companyName']  . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $data['allocated_candidates'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $data['cleared_count'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' .$data['allocated_candidates'] - $data['cleared_count'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $utility->number($data['number_captured']) . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $utility->money($data['amount_remitted']) . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $utility->number($data['unremitted_num']) . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $utility->money($data['unremitted_amount']) . '</b>'; ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center"><strong>:: No Consultant Profile Created Yet ::</strong></td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>