<?php
session_start();

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';

$page = $_GET['page'] ?? 'login';

// Basic routing logic
switch ($page) {
    case 'login':
        require_once __DIR__ . '/../views/auth/login.php';
        break;
    case 'dashboard':
        Auth::requireRole($_SESSION['user_role']);
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/' . $_SESSION['user_role'] . '/dashboard.php';
        include __DIR__ . '/../views/layouts/footer.php';
        break;
    case 'logout':
        Auth::logout();
        header('Location: index.php?page=login');
        break;
    default:
        header('Location: index.php?page=login');
        break;
}
