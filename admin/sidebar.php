<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
        <i class="fas fa-cogs"></i>
        <span class="brand-text font-weight-light">| LGU</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../includes/www.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Admin</a>
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
                    <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Manage Users -->
                <li class="nav-item">
                    <a href="manage_users.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Manage Users</p>
                    </a>
                </li>

                <!-- Department Position -->
                <li class="nav-item">
                    <a href="manage_d-p.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_d-p.php' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-exchange"></i>
                        <p>Department Position</p>
                    </a>
                </li>

                <!-- File Approval Dropdown -->
                <li class="nav-item has-treeview <?= in_array(basename($_SERVER['PHP_SELF']), ['pending_ordinance.php', 'pending_resolution.php']) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array(basename($_SERVER['PHP_SELF']), ['pending_ordinance.php', 'pending_resolution.php']) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            File Approval
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pending_ordinance.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pending_ordinance.php' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ordinance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pending_resolution.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pending_resolution.php' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Resolution</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
