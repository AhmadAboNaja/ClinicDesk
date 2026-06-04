<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=login');
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!CSRF::validateToken($token)) {
            flash('error', 'Invalid CSRF token. Please try again.');
            redirect('index.php?page=login');
        }

        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Invalid credentials.');
            redirect('index.php?page=login');
        }

        if ((int) $user['is_active'] !== 1) {
            flash('error', 'Account suspended. Contact admin.');
            redirect('index.php?page=login');
        }

        Auth::login($user);

        if (!empty($user['first_login'])) {
            flash('warning', 'Please change your password before continuing.');
            redirect('index.php?page=change_password');
        }

        redirect('index.php?page=dashboard');
    }

    public function logout(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=dashboard');
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!CSRF::validateToken($token)) {
            flash('error', 'Invalid CSRF token.');
            redirect('index.php?page=dashboard');
        }

        Auth::logout();
    }
}
