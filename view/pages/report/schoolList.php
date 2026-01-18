<script>
    $(document).ready(function () {
        $('#example1').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 10,
            "order": [[0, "asc"]], // Sorting by S/N
            "buttons": ["copy", "csv", "excel", "pdf", "print"] // Export buttons
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
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
                                    <th>Zone</th>
                                    <th>Centre Number</th>
                                    <th>School Name</th>
                                    <th>School Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                if (!empty($allocatedSchools)) {
                                    foreach ($allocatedSchools as $data) {
                                ?>
                                        <tr>
                                            <td>
                                                <?php echo $count++; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php echo '<b>' . $data['lga'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' .  $data['schoolCode']  . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php echo '<b>' . $data['SchoolName'] . '</b>'; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (intval($data['schType']) === 1) {
                                                    echo " Public ";
                                                } elseif (intval($data['schType']) === 2) {
                                                    echo " Private ";
                                                } else {
                                                    echo " Unspecified ";
                                                }
                                                ?>
                                            </td>

                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo 'No School Allocated for the Active Exam Year ::  ' . $_SESSION['examYear'];;
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