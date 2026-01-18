<?php
include '../../appadmin/adminquery.php';
if (!isset($_SESSION['activeAdmin'])) {
    $utility->setNotification('alert-danger', 'icon fas fa-ban', 'No Log in session found. Try again.');
    $utility->redirect('../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> CASS 3 Clearance Portal</title>
    <link rel="icon" type="image/x-icon" href="../../storage/app/ogun.png">
    <link rel="stylesheet" href="../../view/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../view/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../view/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../../view/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../view/plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../../view/dist/css/adminlte.min2167.css?v=3.2.0">
    <link rel="stylesheet" href="../../view/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="../../view/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../../view/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../../view/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="../../view/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../view/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../view/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../../view/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../view/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="../../view/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="./../view/plugins/bs-stepper/css/bs-stepper.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
</head>