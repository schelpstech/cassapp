<nav class="main-header navbar navbar-expand navbar-ogun">


    <!-- Left navbar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo route('consultantDashboard'); ?>" class="nav-link font-weight-bold">
                WAEC CASS III Clearance Portal
            </a>
        </li>
    </ul>

    <!-- Right navbar -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Exam Year Badge -->
        <li class="nav-item mr-3">
            <span class="badge badge-success p-2">
                Exam Year: <?php echo htmlspecialchars($_SESSION['examYear']); ?>
            </span>
        </li>

        <!-- Notifications -->
        <?php if (isset($_SESSION['msg'])): ?>
            <li class="nav-item mr-2">
                <span class="text-danger font-weight-bold">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </span>
            </li>
        <?php endif; ?>

        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
            <a class="nav-link text-danger" data-toggle="modal" data-target="#sign_out" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>


