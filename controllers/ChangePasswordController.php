<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/User.php';

class ChangePasswordController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        // Accessible only to authenticated users
        Auth::requireRole('admin', 'doctor', 'patient');

        $user = Auth::currentUser();
        if (!$user) {
            redirect('index.php?page=login');
        }

        $dbUser = $this->userModel->findById((int)$user['id']);
        if (!$dbUser) {
            flash('error', 'User not found.');
            redirect('index.php?page=login');
        }

        // Enforce PDF requirement: only first_login users must use this page
        $firstLogin = isset($dbUser['first_login']) ? (int)$dbUser['first_login'] : 0;
        if ($firstLogin !== 1) {
            redirect('index.php?page=dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=change_password');
            }

            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($currentPassword === '' || !password_verify($currentPassword, $dbUser['password'])) {
                flash('error', 'Current password is incorrect.');
                redirect('index.php?page=change_password');
            }

            if ($newPassword === '' || $confirmPassword === '') {
                flash('error', 'New password and confirmation are required.');
                redirect('index.php?page=change_password');
            }

            if ($newPassword !== $confirmPassword) {
                flash('error', 'New password and confirmation do not match.');
                redirect('index.php?page=change_password');
            }

            // PDF advanced challenge rules (for first_login change-password page)
            // min 8 chars, mixed case + number
            $newPassword = (string)$newPassword;
            $hasLower = (bool)preg_match('/[a-z]/', $newPassword);
            $hasUpper = (bool)preg_match('/[A-Z]/', $newPassword);
            $hasNumber = (bool)preg_match('/[0-9]/', $newPassword);
            $hasWhitespace = (bool)preg_match('/\s/', $newPassword);

            if (strlen($newPassword) < 8) {
                flash('error', 'New password must be at least 8 characters long.');
                redirect('index.php?page=change_password');
            }

            if (!$hasLower || !$hasUpper || !$hasNumber) {
                flash('error', 'New password must contain at least one lowercase letter, one uppercase letter, and one number.');
                redirect('index.php?page=change_password');
            }

            if ($hasWhitespace) {
                flash('error', 'New password must not contain spaces or whitespace characters.');
                redirect('index.php?page=change_password');
            }

            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->userModel->updatePassword((int)$dbUser['id'], $newHash);

            flash('success', 'Password changed successfully.');
            redirect('index.php?page=dashboard');
        }

        $pageTitle = 'Change Password';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/auth/change_password.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }
}

