<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/Appointment.php';

class ReportController {
    private Appointment $appointmentModel;

    public function __construct() {
        $this->appointmentModel = new Appointment();
    }

    public function index(): void {
        Auth::requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;

            if (!$startDate || !$endDate || strtotime($startDate) > strtotime($endDate)) {
                flash('error', 'Invalid date range.');
                redirect('index.php?page=reports');
            }

            if (!empty($_GET['export']) && $_GET['export'] === 'csv') {
                $this->exportCsv($startDate, $endDate);
            }

            $filters = [
                'date_from' => $startDate,
                'date_to' => $endDate,
                'doctor_id' => $_POST['doctor_id'] ?? '',
                'status' => $_POST['status'] ?? '',
            ];

            $appointments = $this->appointmentModel->getAll(1, $filters);
        }

        $pageTitle = 'Reports';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/reports/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    private function exportCsv(string $startDate, string $endDate): void {
        $filters = [
            'date_from' => $startDate,
            'date_to' => $endDate,
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
