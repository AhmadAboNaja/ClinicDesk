<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../core/Paginator.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Doctor.php';

class AppointmentController
{
    private Appointment $appointmentModel;
    private Doctor $doctorModel;

    public function __construct()
    {
        $this->appointmentModel = new Appointment();
        $this->doctorModel = new Doctor();
    }
public function view(): void
{
    Auth::requireRole('admin', 'doctor', 'patient');

    $id = (int) ($_GET['id'] ?? 0);

    $appointment = $this->appointmentModel->findById($id);

    if (!$appointment) {
        flash('error', 'Appointment not found.');
        redirect('index.php?page=appointments');
    }

    $pageTitle = 'Appointment Details';

    require __DIR__ . '/../views/partials/header.php';
    require __DIR__ . '/../views/appointments/view.php';
    require __DIR__ . '/../views/layouts/footer.php';
}
    public function book(): void
    {
        Auth::requireRole('patient');
        $user = Auth::currentUser();
        $doctors = $this->doctorModel->getAll();
        // Convert available_days string to array for JS usage
        foreach ($doctors as &$doc) {
            $doc['days'] = array_filter(explode(',', $doc['available_days']));
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=appointments&action=book');
            }

            $date = $_POST['appt_date'] ?? '';
            $time = $_POST['appt_time'] ?? '';
            $doctorId = (int) ($_POST['doctor_id'] ?? 0);

            if (strtotime($date) < strtotime(date('Y-m-d'))) {
                flash('error', 'Appointment date cannot be in the past.');
                redirect('index.php?page=appointments&action=book');
            }

            if ($this->appointmentModel->hasConflict($doctorId, $date, $time)) {
                flash('error', 'This slot is already booked.');
                redirect('index.php?page=appointments&action=book');
            }

            $data = [
                'patient_id' => $user['id'],
                'doctor_id' => $doctorId,
                'appt_date' => $date,
                'appt_time' => $time,
                'reason' => sanitize($_POST['reason'] ?? ''),
            ];

            if ($this->appointmentModel->book($data)) {
                flash('success', 'Appointment booked successfully.');
                redirect('index.php?page=appointments');
            } else {
                flash('error', 'Failed to book appointment.');
                redirect('index.php?page=appointments&action=book');
            }
        }

        $pageTitle = 'Book Appointment';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/appointments/book.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function index(): void
    {
        Auth::requireRole('admin', 'doctor', 'patient');
        $user = Auth::currentUser();
        $role = $user['role'];
        $page = (int) ($_GET['page_num'] ?? 1);

        $filters = [
            'status' => $_GET['status'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
        ];

        if ($role === 'patient') {
            $total = $this->appointmentModel->countFiltered('patient', $user['id'], $filters);
            $appointments = $this->appointmentModel->getByPatient($user['id'], $page, $filters);
        } elseif ($role === 'doctor') {
            $doctor = $this->doctorModel->findByUserId($user['id']);
            $total = $this->appointmentModel->countFiltered('doctor', $doctor['user_id'] ?? 0, $filters);
            $appointments = $this->appointmentModel->getByDoctor($doctor['user_id'] ?? 0, $page, $filters);
        } else {
            $filters['doctor_id'] = $_GET['doctor_id'] ?? '';
            $filters['patient_name'] = $_GET['patient_name'] ?? '';
            $total = $this->appointmentModel->countFiltered('admin', 0, $filters);
            $appointments = $this->appointmentModel->getAll($page, $filters);
        }

        $paginator = new Paginator($total, ITEMS_PER_PAGE, $page);
        $doctors = $this->doctorModel->getAll();

        $pageTitle = 'Appointments';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/appointments/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function updateStatus(): void
    {
        Auth::requireRole('admin', 'doctor');
        $id = (int) ($_GET['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $token = $_POST['csrf_token'] ?? '';

        if (!CSRF::validateToken($token)) {
            flash('error', 'Invalid CSRF token.');
            redirect('index.php?page=appointments');
        }

        $this->appointmentModel->updateStatus($id, $status, $_POST['notes'] ?? '');
        flash('success', 'Appointment status updated.');
        redirect('index.php?page=appointments');
    }

    public function cancel(): void
    {
        Auth::requireRole('patient');
        $user = Auth::currentUser();
        $id = (int) ($_GET['id'] ?? 0);
        $token = $_POST['csrf_token'] ?? '';

        if (!CSRF::validateToken($token)) {
            flash('error', 'Invalid CSRF token.');
            redirect('index.php?page=appointments');
        }

        $this->appointmentModel->updateStatus($id, 'cancelled');
        flash('success', 'Appointment cancelled.');
        redirect('index.php?page=appointments');
    }
}
