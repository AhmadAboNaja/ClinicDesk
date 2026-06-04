<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../core/CSRF.php';

class AdminController {
    private $userModel;
    private $doctorModel;

    public function __construct() {
        $this->userModel = new User();
        $this->doctorModel = new Doctor();
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::validateToken($_POST['csrf_token']);

            $userData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['temp_password'], PASSWORD_BCRYPT),
                'role' => $_POST['role'],
                'phone' => $_POST['phone'],
                'is_active' => 1,
                'first_login' => 1
            ];

            $userId = $this->userModel->create($userData);

            if ($userData['role'] === 'doctor') {
                $doctorData = [
                    'user_id' => $userId,
                    'specialization_id' => $_POST['specialization_id'],
                    'consultation_fee' => $_POST['fee'],
                    'available_days' => implode(',', $_POST['available_days'])
                ];
                $this->doctorModel->query("INSERT INTO doctors (user_id, specialization_id, consultation_fee, available_days) VALUES (?, ?, ?, ?)", 
                    [$doctorData['user_id'], $doctorData['specialization_id'], $doctorData['consultation_fee'], $doctorData['available_days']]);
            }

            $_SESSION['success'] = "User created successfully!";
            header('Location: index.php?page=manage_users');
            exit();
        }
    }
}
