<?php

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

session_start();

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/Auth.php';
require_once dirname(__DIR__) . '/core/CSRF.php';
require_once dirname(__DIR__) . '/core/Paginator.php';
require_once dirname(__DIR__) . '/core/helpers.php';

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

    case 'users':
        Auth::requireRole('admin');
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();

        if ($action === 'create') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->create();
            } else {
                $pageTitle = 'Create User';
                require_once __DIR__ . '/../views/users/create.php';
            }
        } elseif ($action === 'edit') {
            $controller->edit();
        } elseif ($action === 'toggleActive') {
            $controller->toggleActive();
        } else {
            $controller->index(); // calling index method to handle pagination and listing
        }
        break;

    case 'doctors':
        Auth::requireRole('admin');
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();

        if ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit') {
            $controller->edit();
        } else {
            $controller->index();
        }
        break;

    case 'specializations':
        Auth::requireRole('admin');
        require_once __DIR__ . '/../controllers/SpecializationController.php';
        $controller = new SpecializationController();
        if ($action === 'create') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->create();
            } else {
                $pageTitle = 'Create Specialization';
                require_once __DIR__ . '/../views/doctors/specialization_form.php';
            }
        } elseif ($action === 'delete') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->delete();
            } else {
                header('Location: index.php?page=specializations');
                exit;
            }
        } else {
            $controller->index();
        }
        break;

    case 'appointments':
        Auth::requireRole('admin', 'doctor', 'patient');
        require_once __DIR__ . '/../controllers/AppointmentController.php';
        $controller = new AppointmentController();
        if ($action === 'book') {
            $controller->book();
        } elseif ($action === 'cancel') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Auth::requireRole('patient');
                $controller->cancel();
            } else {
                header('Location: index.php?page=appointments');
                exit;
            }
        } elseif ($action === 'updateStatus') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Auth::requireRole('doctor', 'admin');
                $controller->updateStatus();
            } else {
                header('Location: index.php?page=appointments');
                exit;
            }
        } else {
            $controller->index();
            $page_num = $_GET['page_num'] ?? 1;
            $pageTitle = 'Appointments';
            require_once __DIR__ . '/../views/appointments/index.php';
        }
        break;

    case 'prescriptions':
        Auth::requireRole('admin', 'doctor', 'patient');
        require_once __DIR__ . '/../controllers/PrescriptionController.php';
        $controller = new PrescriptionController();
        if ($action === 'add') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Auth::requireRole('doctor', 'admin');
                $controller->add();
            } else {
                Auth::requireRole('doctor', 'admin');
                $_GET['appointment_id'] = $_GET['appointment_id'] ?? null;
                $pageTitle = 'Add Prescription';
                require_once __DIR__ . '/../views/prescriptions/add.php';
            }
        } elseif ($action === 'view') {
            $prescriptionId = $_GET['id'] ?? null;
            if (!$prescriptionId) {
                header('Location: index.php?page=prescriptions');
                exit;
            }
            $pageTitle = 'Prescription Detail';
            require_once __DIR__ . '/../views/prescriptions/view.php';
        } elseif ($action === 'download') {
            $prescriptionId = $_GET['id'] ?? null;
            if ($prescriptionId) {
                $controller->download($prescriptionId);
            } else {
                header('Location: index.php?page=prescriptions');
                exit;
            }
        } else {
            $page_num = $_GET['page_num'] ?? 1;
            $pageTitle = 'Prescriptions';
            require_once __DIR__ . '/../views/prescriptions/index.php';
        }
        break;

    case 'reports':
        Auth::requireRole('admin');
        require_once __DIR__ . '/../controllers/ReportController.php';
        $controller = new ReportController();
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
