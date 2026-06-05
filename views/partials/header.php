<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ClinicDesk | <?php echo htmlspecialchars($pageTitle ?? 'Management System', ENT_QUOTES, 'UTF-8'); ?></title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?php echo defined('ASSETS_URL') ? ASSETS_URL : '/ClinicDesk/public/assets/'; ?>adminlte/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo defined('ASSETS_URL') ? ASSETS_URL : '/ClinicDesk/public/assets/'; ?>adminlte/plugins/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php?page=dashboard" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user"></i>
                    <?php
                    $user = Auth::currentUser();
                    if ($user) {
                        echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
                    }
                    ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="index.php?page=profile" class="dropdown-item">
                        <i class="fas fa-user-circle mr-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="index.php?page=logout" class="dropdown-item">
                        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                        <button type="submit" class="btn btn-link p-0 text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php?page=dashboard" class="brand-link">
            <span class="brand-text font-weight-light"><i class="fas fa-hospital mr-2"></i>ClinicDesk</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="index.php?page=profile" class="d-block">
                        <?php
                        $user = Auth::currentUser();
                        if ($user) {
                            echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
                            echo '<br><small class="text-muted">' . htmlspecialchars(ucfirst($user['role']), ENT_QUOTES, 'UTF-8') . '</small>';
                        }
                        ?>
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php
                    $role = Auth::role();
                    $currentPage = $_GET['page'] ?? 'dashboard';
                    
                    if ($role === 'admin') {
                        // Dashboard
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=dashboard" class="nav-link ' . ($currentPage === 'dashboard' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-tachometer-alt"></i>';
                        echo '<p>Dashboard</p></a></li>';
                        
                        // Users
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=users" class="nav-link ' . ($currentPage === 'users' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-users"></i>';
                        echo '<p>User Management</p></a></li>';
                        
                        // Doctors
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=doctors" class="nav-link ' . ($currentPage === 'doctors' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-user-md"></i>';
                        echo '<p>Doctor Management</p></a></li>';
                        
                        // Specializations
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=specializations" class="nav-link ' . ($currentPage === 'specializations' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-stethoscope"></i>';
                        echo '<p>Specializations</p></a></li>';
                        
                        // Appointments
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=appointments" class="nav-link ' . ($currentPage === 'appointments' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-calendar-alt"></i>';
                        echo '<p>Appointments</p></a></li>';
                        
                        // Reports
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=reports" class="nav-link ' . ($currentPage === 'reports' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-chart-bar"></i>';
                        echo '<p>Reports</p></a></li>';
                        
                    } elseif ($role === 'doctor') {
                        // Dashboard
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=dashboard" class="nav-link ' . ($currentPage === 'dashboard' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-tachometer-alt"></i>';
                        echo '<p>Dashboard</p></a></li>';
                        
                        // My Schedule
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=appointments" class="nav-link ' . ($currentPage === 'appointments' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-calendar-check"></i>';
                        echo '<p>My Schedule</p></a></li>';
                        
                        // Prescriptions
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=prescriptions" class="nav-link ' . ($currentPage === 'prescriptions' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-prescription-bottle-alt"></i>';
                        echo '<p>Prescriptions</p></a></li>';
                        
                    } elseif ($role === 'patient') {
                        // Dashboard
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=dashboard" class="nav-link ' . ($currentPage === 'dashboard' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-tachometer-alt"></i>';
                        echo '<p>Dashboard</p></a></li>';
                        
                        // Book Appointment
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=appointments&action=book" class="nav-link ' . (($currentPage === 'appointments' && ($_GET['action'] ?? '') === 'book') ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-calendar-plus"></i>';
                        echo '<p>Book Appointment</p></a></li>';
                        
                        // My Appointments
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=appointments" class="nav-link ' . ($currentPage === 'appointments' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-calendar-check"></i>';
                        echo '<p>My Appointments</p></a></li>';
                        
                        // Prescriptions
                        echo '<li class="nav-item">';
                        echo '<a href="index.php?page=prescriptions" class="nav-link ' . ($currentPage === 'prescriptions' ? 'active' : '') . '">';
                        echo '<i class="nav-icon fas fa-prescription-bottle"></i>';
                        echo '<p>My Prescriptions</p></a></li>';
                    }
                    ?>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Optional) -->
        <?php displayFlash(); ?>
