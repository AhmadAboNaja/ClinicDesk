<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../core/Paginator.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        Auth::requireRole('admin');
        $page = (int) ($_GET['page_num'] ?? 1);
        $role = $_GET['role'] ?? '';
        $search = $_GET['search'] ?? '';

        $total = $this->userModel->countAll($role);
        $paginator = new Paginator($total, ITEMS_PER_PAGE, $page);
        $users = $this->userModel->getAllPaginated($page, $role);

        $pageTitle = 'User Management';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/users/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function create(): void
    {
        Auth::requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=users');
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => password_hash($_POST['temp_password'] ?? '', PASSWORD_BCRYPT),
                'role' => $_POST['role'] ?? 'patient',
                'phone' => $_POST['phone'] ?? '',
                'is_active' => 1,
                'first_login' => 1,
            ];

            $userId = $this->userModel->create($data);
            flash('success', 'User created successfully.');
            redirect('index.php?page=users');
        }

        $pageTitle = 'Create User';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/users/create.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function edit(): void
    {
        Auth::requireRole('admin');
        $id = (int) ($_GET['id'] ?? 0);

        $edittingUser = $this->userModel->findById($id);

        if (!$edittingUser) {
            flash('error', 'User not found.');
            redirect('index.php?page=users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=users&action=edit&id=' . $id);
            }

            $data = [
                'name' => $_POST['name'] ?? $edittingUser['name'],
                'phone' => $_POST['phone'] ?? $edittingUser['phone'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            $this->userModel->update($id, $data);
            flash('success', 'User updated.');
            redirect('index.php?page=users');
        }

        $pageTitle = 'Edit User';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/users/edit.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function toggleActive(): void
    {
        Auth::requireRole('admin');
        $id = (int) ($_GET['id'] ?? 0);
        $token = $_POST['csrf_token'] ?? '';
        if (!CSRF::validateToken($token)) {
            flash('error', 'Invalid CSRF token.');
            redirect('index.php?page=users');
        }

        $currentUser = Auth::currentUser();
        if ($currentUser['id'] === $id) {
            flash('error', 'Cannot deactivate your own account.');
            redirect('index.php?page=users');
        }

        $this->userModel->toggleActive($id);
        flash('success', 'User status updated.');
        redirect('index.php?page=users');
    }
}
