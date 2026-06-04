<?php

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/Paginator.php';
require_once __DIR__ . '/../core/helpers.php';

$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

if (!Auth::check() && $page !== 'login' && $page !== 'error') {
    header('Location: index.php?page=login');
    exit;
}

if (Auth::check() && $page === 'login') {
    header('Location: index.php?page=dashboard');
    exit;
}

switch ($page) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->login();
        } else {
            $pageTitle = 'Login';
            require_once __DIR__ . '/../views/auth/login.php';
        }
        break;

    case 'logout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->logout();
        }
        header('Location: index.php?page=login');
        exit;

    case 'dashboard':
        Auth::requireRole('admin', 'doctor', 'patient');
        require_once __DIR__ . '/../controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'error':
        $code = $_GET['code'] ?? '404';
        $pageTitle = 'Error ' . $code;
        require_once __DIR__ . '/../views/errors/' . $code . '.php';
        break;

    default:
        header('Location: index.php?page=dashboard');
        exit;
}
