# ClinicDesk - Clinic Management System

ClinicDesk is a comprehensive management system for clinics, featuring role-based access for Admins, Doctors, and Patients.

## Features
- **Patient Management**: Book appointments, view medical records, and download prescriptions.
- **Doctor Management**: Manage schedules, handle appointments, and issue prescriptions.
- **Admin Panel**: Oversight of all users, doctors, and system statistics.
- **Security**: CSRF protection, password hashing, and secure file handling.

## Installation
1. Clone the repository.
2. Import `database.sql` into your MySQL server.
3. Configure your database credentials in `config/database.php`.
4. Point your web server to the `public/` directory.

## Technical Stack
- PHP 8.x
- MySQL
- AdminLTE 3 (UI)
- Bootstrap 4
