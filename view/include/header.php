<?php
include '../../app/query.php';
if (!isset($_SESSION['active'])) {
    $utility->setNotification('alert-danger', 'icon fas fa-ban', 'No Log in session found. Try again.');
    $utility->redirect('../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?php
        if (isset($_SESSION['clearedSchool']) && $_SESSION['pageid'] === 'clearancePage') {
            echo $printClearanceInfo['centreNumber'] . ' - ' .
                $printClearanceInfo['SchoolName'] . ' - CASS 3 Clearance';
        } else {
            echo $_SESSION['active'] . ' - CASS 3 Clearance Portal';
        }
        ?>
    </title>

    <link rel="icon" type="image/x-icon" href="../../storage/app/ogun.png">

    <!-- FontAwesome (Local – stable) -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">

    <!-- AdminLTE Core -->
    <link rel="stylesheet" href="../dist/css/adminlte.min2167.css?v=3.2.0">

    <!-- AdminLTE Plugins -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
    <link rel="stylesheet" href="../dist/css/ogun-theme.css">

    <!-- jQuery (CDN – pinned & secure) -->
    <script src="../dist/js/jquery.min.js"></script>
    <script src="../dist/js/html2canvas.min.js"></script>
    <script src="../dist/js/jspdf.umd.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">