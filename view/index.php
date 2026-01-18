<?php
include '../app/query.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OGMoEST – CASS III Clearance Portal</title>

    <link rel="icon" type="image/x-icon" href="../storage/app/ogun.png">

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="./dist/css/font.css">
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="./dist/css/adminlte.min2167.css?v=3.2.0">

    <!-- Custom Overrides -->
 <style>
    body {
        background: linear-gradient(
            135deg,
            #008753 0%,   /* Ogun Green */
            #FFDD00 100%  /* State Yellow */
        );
    }
    .login-box {
        width: 420px;
    }
    .login-card {
        border-radius: 14px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .brand-logo {
        width: 120px;
        margin-bottom: 10px;
    }
    .login-title {
        font-weight: 600;
        font-size: 16px;
        color: #1F2937;
    }
    .btn-primary {
        background-color: #008753;  /* Ogun Green */
        border-color: #008753;
    }
    .btn-primary:hover {
        background-color: #006f45;
    }
    .form-control {
        border-radius: 8px;
    }
    .input-group-text {
        background-color: #F8FAFC;
    }
    .alert-msg {
        font-size: 14px;
        color: #B91C1C;
    }
</style>

</head>

<body class="hold-transition login-page">

<div class="login-box">
    <div class="card login-card">
        <div class="card-body text-center">

            <img src="../storage/app/moest.jpg" class="brand-logo" alt="OGMoEST Logo">

            <p class="login-title mb-3">OGMoEST – CASS III Clearance Portal</p>

            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert-msg mb-2">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            <?php endif; ?>

            <form action="../app/authenticator.php" method="post">

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" name="login_button" value="do_login" class="btn btn-primary btn-block">
                    <strong>Sign In</strong>
                </button>
            </form>

            <hr>
            <small class="text-muted">Secure Academic Clearance System</small>

        </div>
    </div>
</div>

<script src="./plugins/jquery/jquery.min.js"></script>
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./dist/js/adminlte.min2167.js?v=3.2.0"></script>

</body>
</html>