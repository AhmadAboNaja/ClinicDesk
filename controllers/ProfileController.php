<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        Auth::requireRole('admin', 'doctor', 'patient');

        $user = Auth::currentUser();
        if (!$user) {
            redirect('index.php?page=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=profile');
            }

            $name = trim($_POST['name'] ?? '');
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($name === '') {
                flash('error', 'Name cannot be empty.');
                redirect('index.php?page=profile');
            }

            $dbUser = $this->userModel->findById((int)$user['id']);
            if (!$dbUser) {
                flash('error', 'User not found.');
                redirect('index.php?page=profile');
            }

            if ($currentPassword === '' || !password_verify($currentPassword, $dbUser['password'])) {
                flash('error', 'Current password is incorrect.');
                redirect('index.php?page=profile');
            }

            if ($newPassword === '' || $confirmPassword === '') {
                flash('error', 'New password and confirmation are required.');
                redirect('index.php?page=profile');
            }

            if ($newPassword !== $confirmPassword) {
                flash('error', 'New password and confirmation do not match.');
                redirect('index.php?page=profile');
            }

            if (strlen($newPassword) < 6) {
                flash('error', 'New password must be at least 6 characters.');
                redirect('index.php?page=profile');
            }

            $this->userModel->update((int)$user['id'], [
                'name' => $name,
            ]);

            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->userModel->updatePassword((int)$user['id'], $newHash);

            flash('success', 'Profile updated successfully.');
            redirect('index.php?page=profile');
        }

        $pageTitle = 'Profile';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/profile/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }
}


