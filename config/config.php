<?php

define('APP_NAME', 'ClinicDesk');

// Detect the base URL dynamically based on the current request
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// Remove trailing slash
$scriptDir = rtrim($scriptDir, '/');

// The base URL should point to the public directory
$baseUrl = $protocol . '://' . $host . $scriptDir;
define('BASE_URL', $baseUrl . '/');

// Asset path - relative to BASE_URL
define('ASSETS_URL', BASE_URL . 'assets/');

define('ITEMS_PER_PAGE', 10);
define('UPLOAD_MAX_AVATAR', 1048576); // 1MB
define('UPLOAD_MAX_PRESCRIPTION', 3145728); // 3MB
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('AVATAR_UPLOAD_DIR', UPLOAD_PATH . 'avatars/');
define('DOCTOR_PHOTO_UPLOAD_DIR', UPLOAD_PATH . 'doctor_photos/');
define('PRESCRIPTION_UPLOAD_DIR', UPLOAD_PATH . 'prescriptions/');
