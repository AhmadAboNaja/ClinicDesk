<?php

class Auth {
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];
        session_regenerate_id(true);
    }

    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }

    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        if (!self::check()) return null;
        $userModel = new User();
        return $userModel->find($_SESSION['user_id']);
    }

    public static function requireRole($role) {
        if (!self::check() || $_SESSION['user_role'] !== $role) {
            header('Location: login.php');
            exit();
        }
    }

    public static function guest() {
        if (self::check()) {
            header('Location: index.php?page=dashboard');
            exit();
        }
    }
}
