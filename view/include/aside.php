<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand -->
    <a href="<?php echo route('consultantDashboard'); ?>" class="brand-link">
        <img src="../../storage/app/ogun.png"
            alt="OGMoEST Logo"
            class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">OG-MoEST</span>
    </a>

    <div class="sidebar">

        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../storage/app/consultant.png"
                    class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?php echo htmlspecialchars($_SESSION['active'] ?? 'Consultant', ENT_QUOTES, 'UTF-8'); ?>
                </a>
                <small class="text-muted">Consultant</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo route('consultantDashboard'); ?>"
                        class="nav-link <?php echo ($pageId === 'consultantDashboard') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p><strong>Dashboard</strong></p>
                    </a>
                </li>

                <!-- Clearance -->
                <li class="nav-item has-treeview <?php echo in_array($pageId, ['capturingRecord', 'transactionHistories']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>
                            <strong>Clearance</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo route('capturingRecord'); ?>"
                                class="nav-link <?php echo ($pageId === 'capturingRecord') ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p><strong>Process Clearance</strong></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo route('transactionHistories'); ?>"
                                class="nav-link <?php echo ($pageId === 'transactionHistories') ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p><strong>Transaction History</strong></p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Reports -->
                <li class="nav-item has-treeview <?php echo in_array($pageId, ['schoolsAllocatedList', 'consultantClearedSchools']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            <strong>Reports</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo route('schoolsAllocatedList'); ?>"
                                class="nav-link <?php echo ($pageId === 'schoolsAllocatedList') ? 'active' : ''; ?>">
                                <i class="fas fa-school nav-icon"></i>
                                <p><strong>Allocated Schools</strong></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo route('consultantClearedSchools'); ?>"
                                class="nav-link <?php echo ($pageId === 'consultantClearedSchools') ? 'active' : ''; ?>">
                                <i class="fas fa-check-circle nav-icon"></i>
                                <p><strong>Clearance Summary</strong></p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Credentials -->
                <li class="nav-item">
                    <a href="<?php echo route('consultantpwdMgr'); ?>"
                        class="nav-link <?php echo ($pageId === 'consultantpwdMgr') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p><strong>Credentials</strong></p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>