<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VIP Dashboard | AdminLTE</title>

    <!-- AdminLTE & Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .nav-sidebar .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .nav-sidebar .nav-item .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="dashboard.php" class="nav-link">Home</a>
        </li>
    </ul>
    
    <ul class="navbar-nav ml-auto" style="margin-right: 20px;">
        <li class="nav-item">
            <a class="nav-link text-danger" href="../../logout2.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</nav>


    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="dashboard.php" style="
        background-color: rgb(44, 48, 73); text-decoration: none;" class="brand-link text-center">
            <span class="brand-text font-weight-light">LGU Enrile <?= htmlspecialchars($user1['position'] ?? 'Admin') ?> </span>
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="img-circle elevation-2" alt="User Image" width="40">
                </div>
                <div class="info">
                    <a style="text-decoration: none;" href="#" class="d-block"><?= htmlspecialchars($user1['name'] ?? 'User') ?> <br> <?= htmlspecialchars($user1['position'] ?? 'Admin') ?></a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" id="sidebar-menu">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="memorandum.php" class="nav-link">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>Memorandum</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="view_memo.php" class="nav-link">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>View Memorandums</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="private_files.php" class="nav-link">
                            <i class="nav-icon fas fa-lock"></i>
                            <p>Private File</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>