<?php

include '../appadmin/adminquery.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ASSOEC Manager - CASS III Clearance Portal</title>
    <link rel="icon" type="image/x-icon" href="../storage/app/ogun.png">
    <link rel="stylesheet" href="../view/dist/css/font.css">

    <link rel="stylesheet" href="../view/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="../view/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="../view/dist/css/adminlte.min2167.css?v=3.2.0">

</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-warning">
            <div class="card-header text-center">
                <img src="../storage/app/moest.jpg" width="200"/>
                <?php
                    if (isset($_SESSION['msg'])) {
                        printf('<b>%s</b>', $_SESSION['msg']);
                        unset($_SESSION['msg']);
                    }
                ?>
            </div>
            <div class="card-body">
                <p class="login-box-msg"><strong>ASSOEC Manager - Clearance Portal</strong></p>
                <form action="../appadmin/authenticator.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="username" autocomplete="new-password" required="yes">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" required="yes">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">

                        </div>

                        <div class="col-4">
                            <button type="submit" name="login_button" value="do_login" class="btn btn-default btn-block"><strong>Sign In</strong> </button>
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