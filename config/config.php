<?php

define('APP_NAME', 'ClinicDesk');
define('BASE_URL', '/');
define('ITEMS_PER_PAGE', 10);
define('UPLOAD_MAX_AVATAR', 1048576); // 1MB
define('UPLOAD_MAX_PRESCRIPTION', 3145728); // 3MB
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('AVATAR_UPLOAD_DIR', UPLOAD_PATH . 'avatars/');
define('DOCTOR_PHOTO_UPLOAD_DIR', UPLOAD_PATH . 'doctor_photos/');
define('PRESCRIPTION_UPLOAD_DIR', UPLOAD_PATH . 'prescriptions/');
