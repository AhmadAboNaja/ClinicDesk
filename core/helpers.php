<?php

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function sanitize(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function formatDate(string $date): string {
    return date('M j, Y', strtotime($date));
}

function formatTime(string $time): string {
    return date('g:i A', strtotime($time));
}

function flash(string $type, string $message): void {
    $_SESSION['flash'][$type] = $message;
}

function displayFlash(): void {
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return;
    }

    foreach ($_SESSION['flash'] as $type => $message) {
        $class = match ($type) {
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            default => 'alert-info',
        };
        echo "<div class=\"alert $class alert-dismissible fade show\" role=\"alert\">" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "<button type=\"button\" class=\"btn-close\" data-dismiss=\"alert\" aria-label=\"Close\"></button></div>";
    }

    unset($_SESSION['flash']);
}
