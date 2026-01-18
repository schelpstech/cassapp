<div class="modal fade" id="sign_out">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Sign Out </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You are about to log out your existing session </p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <form action="../../appadmin/authenticator.php" method="post">
                    <button type="submit" name="log_out_user" value="<?php echo base64_encode('log_out_user_form'); ?>" class="btn btn-outline-light">Proceed</button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y") ?> <a href="https://assoec.com">Association of Educational Consultants - Ogun State Chapter </a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
    </div>
</footer>

<aside class="control-sidebar control-sidebar-dark">

</aside>

</div>


<script src="../../view/plugins/jquery/jquery.min.js"></script>

<script src="../../view/plugins/jquery-ui/jquery-ui.min.js"></script>

<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>

<script src="../../view/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../../view/plugins/chart.js/Chart.min.js"></script>


<script src="../../view/plugins/sparklines/sparkline.js"></script>
<script src="../../view/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../../view/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="../../view/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="../../view/plugins/moment/moment.min.js"></script>
<script src="../../view/plugins/daterangepicker/daterangepicker.js"></script>
<script src="../../view/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../../view/plugins/summernote/summernote-bs4.min.js"></script>
<script src="../../view/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../view/dist/js/adminlte2167.js?v=3.2.0"></script>
<script src="../../view/dist/js/demo.js"></script>
<script src="../../view/dist/js/pages/dashboard.js"></script>

<script src="../../view/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../view/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../view/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../view/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../view/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../view/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../view/plugins/jszip/jszip.min.js"></script>
<script src="../../view/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../view/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../view/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../view/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../view/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="../../view/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<script src="../../view/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="../../view/plugins/select2/js/select2.full.min.js"></script>
<script src="../../view/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="../../view/plugins/custom/utility.js"></script>
<script src="../../view/plugins/custom/fetcher.js"></script>

</body>

</html>