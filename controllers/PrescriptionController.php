<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/Prescription.php';
require_once __DIR__ . '/../models/Appointment.php';

class PrescriptionController {
    private Prescription $prescriptionModel;
    private Appointment $appointmentModel;

    public function __construct() {
        $this->prescriptionModel = new Prescription();
        $this->appointmentModel = new Appointment();
    }

    public function add(): void {
        Auth::requireRole('doctor');
        $appointmentId = (int) ($_GET['id'] ?? 0);
        $user = Auth::currentUser();

        $appointment = $this->appointmentModel->findById($appointmentId);
        if (!$appointment || (int) $appointment['doctor_id'] !== (int) $appointmentId) {
            flash('error', 'Appointment not found.');
            redirect('index.php?page=appointments');
        }

        $existing = $this->prescriptionModel->findByAppointmentId($appointmentId);
        if ($existing) {
            flash('error', 'Prescription already exists for this appointment.');
            redirect('index.php?page=appointments');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=appointments');
            }

            $filePath = null;
            if (!empty($_FILES['prescription_file']['name'])) {
                $filePath = $this->uploadPrescriptionFile($_FILES['prescription_file']);
                if (!$filePath) {
                    flash('error', 'Invalid prescription file.');
                    redirect('index.php?page=prescriptions&action=add&id=' . $appointmentId);
                }
            }

            $data = [
                'appointment_id' => $appointmentId,
                'diagnosis' => $_POST['diagnosis'] ?? '',
                'medications' => $_POST['medications'] ?? '',
                'notes' => $_POST['notes'] ?? '',
                'file_path' => $filePath,
            ];

            $this->prescriptionModel->create($data);
            flash('success', 'Prescription created.');
            redirect('index.php?page=appointments');
        }

        $pageTitle = 'Add Prescription';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/prescriptions/add.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function view(): void {
        Auth::requireRole('patient', 'doctor', 'admin');
        $prescriptionId = (int) ($_GET['id'] ?? 0);
        $prescription = $this->prescriptionModel->findById($prescriptionId);
        if (!$prescription) {
            flash('error', 'Prescription not found.');
            redirect('index.php?page=prescriptions');
        }

        $appointment = $this->appointmentModel->findById($prescription['appointment_id']);
        $user = Auth::currentUser();
        $role = $user['role'];

        if ($role === 'patient' && (int) $appointment['patient_id'] !== $user['id']) {
            header('Location: index.php?page=error&code=403');
            exit;
        }

        if ($role === 'doctor' && (int) $appointment['doctor_id'] !== $user['id']) {
            header('Location: index.php?page=error&code=403');
            exit;
        }

        $pageTitle = 'View Prescription';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/prescriptions/view.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function download(): void {
        Auth::requireRole('patient', 'doctor', 'admin');
        $prescriptionId = (int) ($_GET['id'] ?? 0);
        $prescription = $this->prescriptionModel->findById($prescriptionId);
        if (!$prescription || !$prescription['file_path']) {
            http_response_code(404);
            die('File not found.');
        }

        $filePath = __DIR__ . '/../public/' . $prescription['file_path'];
        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File not found on server.');
        }

        $appointment = $this->appointmentModel->findById($prescription['appointment_id']);
        $user = Auth::currentUser();
        $role = $user['role'];

        if ($role === 'patient' && (int) $appointment['patient_id'] !== $user['id']) {
            http_response_code(403);
            die('Unauthorized.');
        }

        if ($role === 'doctor' && (int) $appointment['doctor_id'] !== $user['id']) {
            http_response_code(403);
            die('Unauthorized.');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="prescription_' . $prescriptionId . '.pdf"');
        readfile($filePath);
        exit;
    }

    public function index(): void {
        Auth::requireRole('patient');
        $user = Auth::currentUser();
        $prescriptions = $this->prescriptionModel->getByPatient($user['id']);

        $pageTitle = 'My Prescriptions';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/prescriptions/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    private function uploadPrescriptionFile(array $file): ?string {
        $maxSize = UPLOAD_MAX_PRESCRIPTION;
        if ($file['size'] > $maxSize) {
            return null;
        }

        $mimeType = mime_content_type($file['tmp_name']);
        if ($mimeType !== 'application/pdf') {
            return null;
        }

        $filename = 'prescription_' . time() . '_' . bin2hex(random_bytes(8)) . '.pdf';
        $destination = PRESCRIPTION_UPLOAD_DIR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return 'uploads/prescriptions/' . $filename;
    }
}
