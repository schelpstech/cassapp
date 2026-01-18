<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../app/router.php?pageid=<?php echo base64_encode('consultantDashboard');?>" class="nav-link"> <strong> Active Exam Year :: <?php echo $_SESSION['examYear']; ?> WAEC CASS III Clearance Portal</strong></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?php
            if (isset($_SESSION['msg'])) {
                printf('<b>%s</b>', $_SESSION['msg']);
                unset($_SESSION['msg']);
            }
            ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  data-toggle="modal" data-target="#sign_out" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>

