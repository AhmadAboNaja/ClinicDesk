<?php

class Auth {
    public static function login(array $user): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
            'first_login' => isset($user['first_login']) ? (int) $user['first_login'] : 0,
        ];

        session_regenerate_id(true);
    }

    public static function logout(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }

    public static function check(): bool {
        return isset($_SESSION['user']);
    }

    public static function currentUser(): ?array {
        return self::check() ? $_SESSION['user'] : null;
    }

    public static function role(): string {
        return self::check() ? $_SESSION['user']['role'] : '';
    }

    public static function requireRole(string ...$roles): void {
        if (!self::check()) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($roles && !in_array(self::role(), $roles, true)) {
            header('Location: index.php?page=error&code=403');
            exit;
        }
    }
}
