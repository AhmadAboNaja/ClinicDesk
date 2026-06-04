<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($pageTitle ?? 'ClinicDesk', ENT_QUOTES, 'UTF-8'); ?></title>
<link rel="stylesheet" href="/public/assets/adminlte/css/adminlte.min.css">
<link rel="stylesheet" href="/public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<style>
html { height: 100%; }
body { display: flex; flex-direction: column; height: 100vh; margin: 0; }
.wrapper { display: flex; flex: 1; }
.main-header { background: #343a40; color: #fff; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; }
.main-header .brand-text { font-size: 1.25rem; font-weight: bold; }
.main-sidebar { width: 220px; background: #007bff; min-height: calc(100vh - 56px); color: #fff; overflow-y: auto; padding: 1rem 0; }
.nav-link { color: #fff; display: block; padding: 0.75rem 1rem; text-decoration: none; }
.nav-link:hover, .nav-link.active { background: rgba(255,255,255,.1); }
.content-wrapper { flex: 1; padding: 1rem; overflow-y: auto; }
.main-footer { background: #343a40; color: #fff; padding: 0.75rem 1rem; text-align: center; border-top: 1px solid #dee2e6; }
.card { background: #fff; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 1rem; }
.card-header { padding: 0.75rem 1rem; background: #f7f7f7; border-bottom: 1px solid #dee2e6; font-weight: 500; }
.card-body { padding: 1rem; }
.alert { padding: 0.75rem 1rem; margin-bottom: 1rem; border-radius: 4px; }
.alert-success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
.alert-danger { background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
.alert-warning { background: #fff3cd; color: #664d03; border: 1px solid #ffecb5; }
.btn { display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: #fff; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; }
.btn:hover { background: #0056b3; }
.logout-btn { background: #dc3545; } .logout-btn:hover { background: #c82333; }
</style>
</head>
<body>
<div class="wrapper">
    <header class="main-header">
        <div class="brand-text">ClinicDesk</div>
        <div>
            <?php
            $user = Auth::currentUser();
            if ($user) {
                echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . ' (' . htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') . ')';
            }
            ?>
        </div>
    </header>
    <div class="wrapper">
        <aside class="main-sidebar">
            <nav>
                <ul>
                    <?php
                    $user = Auth::currentUser();
                    $role = Auth::role();
                    if ($role === 'admin') {
                        echo '<li><a href="index.php?page=dashboard" class="nav-link">Dashboard</a></li>';
                        echo '<li><a href="index.php?page=users" class="nav-link">Users</a></li>';
                        echo '<li><a href="index.php?page=doctors" class="nav-link">Doctors</a></li>';
                        echo '<li><a href="index.php?page=specializations" class="nav-link">Specializations</a></li>';
                        echo '<li><a href="index.php?page=appointments" class="nav-link">Appointments</a></li>';
                        echo '<li><a href="index.php?page=reports" class="nav-link">Reports</a></li>';
                    } elseif ($role === 'doctor') {
                        echo '<li><a href="index.php?page=dashboard" class="nav-link">Dashboard</a></li>';
                        echo '<li><a href="index.php?page=appointments" class="nav-link">My Schedule</a></li>';
                        echo '<li><a href="index.php?page=profile" class="nav-link">Profile</a></li>';
                    } elseif ($role === 'patient') {
                        echo '<li><a href="index.php?page=dashboard" class="nav-link">Dashboard</a></li>';
                        echo '<li><a href="index.php?page=appointments&action=book" class="nav-link">Book Appointment</a></li>';
                        echo '<li><a href="index.php?page=appointments" class="nav-link">My Appointments</a></li>';
                        echo '<li><a href="index.php?page=prescriptions" class="nav-link">Prescriptions</a></li>';
                        echo '<li><a href="index.php?page=profile" class="nav-link">Profile</a></li>';
                    }
                    ?>
                    <li style="position:absolute;bottom:10px;width:100%;"><form method="POST" action="index.php?page=logout" style="margin:0;"><input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>"><button type="submit" class="btn logout-btn" style="width:90%;margin-left:5%;">Logout</button></form></li>
                </ul>
            </nav>
        </aside>
        <div class="content-wrapper">
