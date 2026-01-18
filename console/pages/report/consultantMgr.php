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
                [0, "asc"]
            ], // Sorting by S/N
            "buttons": ["copy", "csv", "excel", "pdf", "print"] // Export buttons
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 offset-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> List of Allocated Schools
                            <?php echo date("d-m-Y") ?>
                        </h3>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>UserCode</th>
                                    <th>Company Name</th>
                                    <th>Contact Details</th>
                                    <th>Contact Address</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                if (!empty($userlists)) {
                                    foreach ($userlists as $data) {
                                ?>
                                        <tr>
                                            <td>
                                                <?php echo $count++; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php echo '<b>' . $data['user_name'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' .  $data['companyName']  . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $data['contactPhone'] . '<br>' . $data['contactEmail'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $data['companyAddress'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (intval($data['activeStatus']) === 1) {
                                                    echo ' <button class="btn btn-success btn-block">
                                                                Activated
                                                            </button> ';
                                                } elseif (intval($data['activeStatus']) === 0) {
                                                    echo ' <button class="btn btn-danger btn-block">
                                                                Inactive
                                                            </button> ';
                                                } else {
                                                    echo " Unspecified ";
                                                }
                                                ?>
                                            </td>

                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo ':: No Consultant Profile Created Yet ::  ';
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