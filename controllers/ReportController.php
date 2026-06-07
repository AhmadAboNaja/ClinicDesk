<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Doctor.php';
class ReportController
{
    private Appointment $appointmentModel;
    private Doctor $doctorModel;

    public function __construct()
    {
        $this->doctorModel = new Doctor();
        $this->appointmentModel = new Appointment();
    }

    public function index(): void
    {
        Auth::requireRole('admin');
        $appointments = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;

            if (!$startDate || !$endDate || strtotime($startDate) > strtotime($endDate)) {
                flash('error', 'Invalid date range.');
                redirect('index.php?page=reports');
            }

            if (!empty($_POST['export']) && $_POST['export'] === 'csv') {
                $this->exportCsv();
            }
            $filters = [
                'date_from' => $startDate,
                'date_to' => $endDate,
                'doctor_id' => $_POST['doctor_id'] ?? '',
                'status' => $_POST['status'] ?? '',
            ];

            $appointments = $this->appointmentModel->getAll(1, $filters);
        }
        $doctors = $this->doctorModel->getAll();

        $reportData = $appointments;
        $pageTitle = 'Reports';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/reports/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    private function exportCsv(): void
    {

        $filters = [
            'date_from' => $_POST['start_date'],
            'date_to' => $_POST['end_date'],
            'doctor_id' => $_POST['doctor_id'] ?? '',
            'status' => $_POST['status'] ?? '',
        ];

        $appointments = $this->appointmentModel->getAll(1, $filters);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="appointment_report_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Patient Name', 'Doctor Name', 'Specialization', 'Date', 'Time', 'Status', 'Reason']);

        foreach ($appointments as $appt) {
            fputcsv($output, [
                $appt['patient_name'],
                $appt['doctor_name'],
                $appt['specialization'],
                formatDate($appt['appt_date']),
                formatTime($appt['appt_time']),
                strtoupper($appt['status']),
                $appt['reason'] ?? 'N/A',
            ]);
        }

        fclose($output);
        exit;
    }
}
