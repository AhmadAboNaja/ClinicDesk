<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models\Doctor.php';
require_once __DIR__ . '/../models\Appointment.php';
require_once __DIR__ . '/../models\Prescription.php';

class DashboardController {
    private User $userModel;
    private Doctor $doctorModel;
    private Appointment $appointmentModel;
    private Prescription $prescriptionModel;

    public function __construct() {
        $this->userModel = new User();
        $this->doctorModel = new Doctor();
        $this->appointmentModel = new Appointment();
        $this->prescriptionModel = new Prescription();
    }

    public function index(): void {
        Auth::requireRole('admin', 'doctor', 'patient');
        $user = Auth::currentUser();
        $role = Auth::role();

        if ($role === 'admin') {
            $totalByRole = $this->userModel->countByRole();
            $todayAppointments = $this->appointmentModel->countByDate(date('Y-m-d'));
            $todayAppointmentsList = $this->appointmentModel->getTodayAppointments();
            $statusBreakdown = $this->appointmentModel->countByStatusForWeek();
            $recent = $this->appointmentModel->getRecentAppointments(5);
            require_once __DIR__ . '/../views/dashboard/admin.php';
            return;
        }

        if ($role === 'doctor') {
            $doctor = $this->doctorModel->findByUserId($user['id']);
            $todayAppointments = $this->appointmentModel->getDoctorAppointmentsByDate($doctor['user_id'], date('Y-m-d'));
            $counts = $this->appointmentModel->countDoctorAppointmentsSummary($doctor['user_id']);
            $upcoming = $this->appointmentModel->getDoctorUpcomingAppointments($doctor['user_id'], 5);
            require_once __DIR__ . '/../views/dashboard/doctor.php';
            return;
        }

        if ($role === 'patient') {
            $active = $this->appointmentModel->countPatientActiveAppointments($user['id']);
            $completed = $this->appointmentModel->countPatientCompletedAppointments($user['id']);
            $prescriptionCount = $this->prescriptionModel->countByPatient($user['id']);
            $nextAppointment = $this->appointmentModel->getPatientNextAppointment($user['id']);
            require_once __DIR__ . '/../views/dashboard/patient.php';
            return;
        }

        header('Location: index.php?page=login');
        exit;
    }
}
