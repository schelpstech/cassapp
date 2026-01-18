<?php
include '../app/query.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Page - CASS 3 Clearance Portal</title>
    <link rel="icon" type="image/x-icon" href="../storage/app/ogun.png">
    <link rel="stylesheet" href="./dist/css/font.css">
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="./dist/css/adminlte.min2167.css?v=3.2.0">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-warning">
            <div class="card-header text-center">
                <img src="../storage/app/moest.jpg" width="200" />
                <?php
                if (isset($_SESSION['msg'])) {
                    printf('<b>%s</b>',$_SESSION['msg']);
                    unset($_SESSION['msg']);
                }
                ?>
            </div>
            <div class="card-body">
                <p class="login-box-msg"><strong>Verify CASS 3 Clearance </strong></p>
                <form action="../app/clearanceModule.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="clearanceCode" minlength="16" maxlength="16" class="form-control" placeholder="Clearance ID"  required="yes">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4">
                            <button type="submit" name="verify_submitted_clearance" value="<?php echo $utility->inputEncode("checkClearanceValidity"); ?>" class="btn btn-default btn-block"><strong>Verify</strong></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./plugins/jquery/jquery.min.js"></script>
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./dist/js/adminlte.min2167.js?v=3.2.0"></script>
</body>

</html>
