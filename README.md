# ClinicDesk - Clinic Management System

ClinicDesk is a comprehensive management system for clinics, featuring role-based access for Admins, Doctors, and Patients.

## Features
### Admin Panel
 - User management (create, edit, deactivate accounts)
 - Doctor management and specialization CRUD
 - Full appointment oversight with filtering
 - Appointment reports with CSV export
 - Dashboard with statistics (users by role, appointments summary)

## Installation
1. Clone the repository.
2. Import `database.sql` into your MySQL server.
3. Configure your database credentials in `config/database.php`.
4. Point your web server to the `public/` directory.

## Technical Stack
- **Backend**: PHP 7.4+ with object-oriented design
- **Database**: MySQL 5.7+ with InnoDB
- **Frontend**: AdminLTE 3 (included locally, no CDN)
- **Design Patterns**: Singleton (Database), Factory-like (Controllers), MVC

## Project Structure

```
clinicdesk/
├── index.php                    ← Front controller (routing)
├── config/
│   ├── config.php               ← Application constants
│   ├── database.php             ← DB credentials (git-ignored)
│   └── routing.php              ← Route configuration
├── core/
│   ├── Database.php             ← MySQL singleton connection
│   ├── Auth.php                 ← Session & role management
│   ├── CSRF.php                 ← Token generation & validation
│   ├── Paginator.php            ← Reusable pagination logic
│   └── helpers.php              ← Utility functions
├── models/
│   ├── BaseModel.php            ← Abstract base with execute()
│   ├── User.php
│   ├── Doctor.php
│   ├── Appointment.php
│   ├── Prescription.php
│   └── Specialization.php
├── controllers/
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── UserController.php
│   ├── DoctorController.php
│   ├── AppointmentController.php
│   ├── PrescriptionController.php
│   └── ReportController.php
├── views/
│   ├── auth/login.php
│   ├── dashboard/admin.php, doctor.php, patient.php
│   ├── users/, doctors/, appointments/, prescriptions/, reports/
│   ├── errors/403.php, 404.php
│   ├── partials/header.php, footer.php
│   └── layouts/header.php, footer.php
├── public/
│   ├── index.php                ← Entry point
│   ├── .htaccess                ← URL rewriting
│   ├── assets/adminlte/         ← CSS, JS, fonts (local)
│   └── uploads/
│       ├── avatars/
│       ├── doctor_photos/
│       └── prescriptions/       ← .htaccess blocks direct access
├── database.sql                 ← Schema + seed data
└── README.md
```

## Installation & Setup

### 1. Database

```bash
mysql -u root -p
CREATE DATABASE clinic_desk;
USE clinic_desk;
SOURCE database.sql;
```

### 2. Configuration

Copy `config/database.php.example` to `config/database.php` and set your credentials:

```php
return [
		'host' => '127.0.0.1',
		'dbname' => 'clinic_desk',
		'user' => 'root',
		'pass' => '',
];
```

### 3. Web Server

Point your web server to `public/` folder. With Apache and `.htaccess` enabled, all requests route through `index.php?page=...`.

**XAMPP Example**: Alias or vhost to `public/` folder.

### 4. Default Login

- Email: `admin@clinicdesk.com`
- Password: `admin123` (update after first login)

## Database Schema Highlights

- **Users**: id, name, email, password (hashed), role, phone, avatar, active, first_login
- **Doctors**: user_id (FK to users), specialization_id, bio, fee, available_days, profile_photo
- **Specializations**: id, name (unique index)
- **Appointments**: patient_id, doctor_id, date, time, status, reason, doctor_notes
	- UNIQUE constraint on (doctor_id, date, time) prevents double-booking
- **Prescriptions**: appointment_id (UNIQUE, 1:1 with appointments), diagnosis, medications, notes, file_path

All foreign keys use `ON DELETE CASCADE` or `ON DELETE RESTRICT` appropriately.

## Key Features Implemented

### Appointment Booking
- Conflict detection (database-enforced UNIQUE constraint + application check)
- Date validation (must be future)
- Doctor availability days checked
- Automatic `status = 'pending'`

### Prescriptions
- Doctors can only add prescriptions to _completed_ appointments
- PDF upload optional, validated with `finfo_file()`
- Files served through PHP (never directly accessible)
- Access restricted to patient, doctor, or admin

### Reports
- Date range filtering (required)
- Doctor and status filters (optional)
- CSV export with headers and formatted dates
- fputcsv() for standards-compliant output

### Pagination
- Configurable items per page (ITEMS_PER_PAGE constant)
- Reusable Paginator class with hasNext(), hasPrev(), offset()
- Used on all listing pages

## API Routes (Front Controller)

- `?page=login` — Login form / handler (POST)
- `?page=logout` — Logout (POST with CSRF)
- `?page=dashboard` — Role-specific dashboard
- `?page=users&action=create|edit|index` — Admin user CRUD
- `?page=doctors&action=create|edit|index` — Doctor management
- `?page=specializations&action=create|delete|index` — Specializations
- `?page=appointments&action=book|index|cancel` — Appointment booking and list
- `?page=prescriptions&action=add|view|download|index` — Prescription management
- `?page=reports` — Appointment reports with CSV export
- `?page=error&code=403|404` — Error pages

## Security Practices

✓ No raw SQL (prepared statements everywhere)
✓ No echoed user data without escaping
✓ Session regeneration on login
✓ CSRF tokens on every POST form
✓ Role checks on every protected action
✓ Ownership verification for resource access
✓ Passwords hashed with bcrypt (PASSWORD_BCRYPT)
✓ Database credentials not in git (config/database.php in .gitignore)
✓ Prescription files blocked from direct HTTP access (.htaccess)
✓ File type validation on upload
✓ No sensitive errors displayed to users (logged only)

## Learning Objectives Met

This project demonstrates:

1. **Singleton Pattern** — One Database instance across all models
2. **OOP Principles** — Abstract models, inheritance, encapsulation
3. **Prepared Statements** — Every query uses bound parameters
4. **Session Management** — Secure login flow with regeneration
5. **Role-Based Access Control** — requireRole() guards every action
6. **CSRF Protection** — Token validation on all state-changing requests
7. **File Upload Security** — MIME validation and restricted access
8. **Pagination** — Reusable, database-efficient listing
9. **Dynamic Queries** — Flexible filtering for appointments and reports
10. **Dashboard Statistics** — Live COUNT() and GROUP BY queries
11. **CSV Export** — Standards-compliant report generation
12. **Code Organization** — Clear separation of config, core, models, controllers, views

## Next Steps for Enhancement

- First-login password change enforcement
- Doctor availability calendar view (weekly grid)
- Appointment cancellation audit log
- Patient self-registration with admin approval workflow
- JSON API endpoint for appointments
- Chart.js dashboard visualizations

## Git History

```
feat: core infrastructure - database singleton, auth, CSRF, routing (Part 1)
feat: controllers - auth, users, doctors, appointments, prescriptions, reports (Part 2)
feat: view templates - users, doctors, appointments, prescriptions, reports (Part 3)
```

All commits are clean with meaningful messages documenting each major milestone.

---

**Created**: June 2026  
**Developed for**: Web Development and Modern Management (WDMM 2010) Final Project  
**Engineer**: Mohammed Zuqlam
