<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../../app/router.php?pageid=<?php echo $utility->inputEncode('consoleDashboard'); ?>" class="brand-link">
        <img src="../../storage/app/ogun.png" alt="Church Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">OG-MoEST</span>
    </a>

    <div class="sidebar">

        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../storage/app/consultant.png" class="img-box elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo htmlspecialchars($_SESSION['activeAdmin'], ENT_QUOTES, 'UTF-8'); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item ">
                    <a href="../../appadmin/router.php?pageid=<?php echo $utility->inputEncode('consoleDashboard'); ?>" class="nav-link <?php echo ($pageId == 'consultantDashboard') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-stream"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Consultant -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-address-card"></i>
                        <p>
                            Consultant
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../appadmin/router.php?pageid=<?php echo $utility->inputEncode('consultantRecord'); ?>" class="nav-link <?php echo ($pageId == 'consultantRecord') ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Consultants</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- School -->
                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            School
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../appadmin/router.php?pageid=<?php echo $utility->inputEncode('manageschoollist'); ?>" class="nav-link <?php echo ($pageId == 'manageschoollist') ? 'active' : ''; ?>">
                                <i class="fas fa-synagogue nav-icon"></i>
                                <p>Manage Schools</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../appadmin/router.php?pageid=<?php echo $utility->inputEncode('manageschoolallocation'); ?>" class="nav-link <?php echo ($pageId == 'manageschoolallocation') ? 'active' : ''; ?>">
                                <i class="fas fa-check-circle nav-icon"></i>
                                <p>School Allocation</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Clearance -->
                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Clearance
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../appadmin/router.php?pageid=<?php echo $utility->inputEncode('reportSchoolClearance'); ?>" class="nav-link <?php echo ($pageId == 'reportSchoolClearance') ? 'active' : ''; ?>">
                                <i class="fas fa-synagogue nav-icon"></i>
                                <p>All School Clearance Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../appadmin/router.php?pageid=<?php echo $utility->inputEncode('reportConsultantClearance'); ?>" class="nav-link <?php echo ($pageId == 'reportConsultantClearance') ? 'active' : ''; ?>">
                                <i class="fas fa-check-circle nav-icon"></i>
                                <p>Consultant Clearance Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../appadmin/router.php?pageid=<?php echo $utility->inputEncode('reportTransactionHistory'); ?>" class="nav-link <?php echo ($pageId == 'reportTransactionHistory') ? 'active' : ''; ?>">
                                <i class="fas fa-check-circle nav-icon"></i>
                                <p>Transaction History</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>