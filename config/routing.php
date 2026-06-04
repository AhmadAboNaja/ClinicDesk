<?php
// Extended front controller routing configuration
// This file can be included to extend routing if needed separately

return [
    'users' => [
        'admin_only' => true,
        'actions' => ['index', 'create', 'edit'],
    ],
    'doctors' => [
        'roles' => ['admin', 'doctor'],
        'actions' => ['index', 'create', 'edit'],
    ],
    'specializations' => [
        'admin_only' => true,
        'actions' => ['index', 'create', 'delete'],
    ],
    'appointments' => [
        'roles' => ['admin', 'doctor', 'patient'],
        'actions' => ['index', 'book', 'cancel', 'updateStatus'],
    ],
    'prescriptions' => [
        'roles' => ['doctor', 'patient', 'admin'],
        'actions' => ['index', 'add', 'view', 'download'],
    ],
    'reports' => [
        'admin_only' => true,
        'actions' => ['index'],
    ],
];
