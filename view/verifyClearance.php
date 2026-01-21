<?php
include '../app/query.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CASS 3 Clearance Verification | OGMoEST</title>

    <link rel="icon" type="image/x-icon" href="../storage/app/ogun.png">
    <link rel="stylesheet" href="./dist/css/font.css">
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./dist/css/adminlte.min2167.css?v=3.2.0">

    <style>
        body {
            background: #f4f6f9;
        }

        .verify-card {
            border-top: 4px solid #f7941d;
        }

        .verify-header img {
            max-width: 140px;
        }

        .verify-title {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 1.1rem;
        }

        .verify-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .form-control-lg {
            height: 55px;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        .btn-verify {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .alert {
            margin-bottom: 0;
        }
    </style>
</head>

<body class="hold-transition login-page">

    <div class="login-box">

        <div class="card verify-card shadow-lg">

            <!-- Header -->
            <div class="card-header text-center verify-header bg-white">
                <img src="../storage/app/moest.jpg" alt="OGMoEST Logo" class="mb-2">
                <div class="verify-title">
                    Ogun State Ministry of Education
                </div>
                <div class="verify-subtitle">
                    CASS 3 Clearance Verification Portal
                </div>
            </div>

            <!-- Message -->
            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-info text-center">
                    <strong><?php echo $_SESSION['msg']; ?></strong>
                </div>
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>

            <!-- Body -->
            <div class="card-body">
                <p class="text-center text-muted mb-4">
                    Enter the Clearance ID to verify the authenticity of this certificate.
                </p>

                <form action="../app/clearanceModule.php" method="post">

                    <div class="input-group mb-4">
                        <input
                            type="text"
                            name="clearanceCode"
                            minlength="16"
                            maxlength="16"
                            class="form-control form-control-lg"
                            placeholder="Enter Clearance ID"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-qrcode"></i>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        name="verify_submitted_clearance"
                        value="<?php echo $utility->inputEncode('checkClearanceValidity'); ?>"
                        class="btn btn-warning btn-block btn-verify">
                        Verify Clearance
                    </button>

                </form>
            </div>

            <!-- Footer -->
            <div class="card-footer text-center text-muted small bg-white">
                This portal verifies only official CASS 3 clearance certificates.
            </div>

        </div>

    </div>

    <script src="./plugins/jquery/jquery.min.js"></script>
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./dist/js/adminlte.min2167.js?v=3.2.0"></script>

</body>

</html>