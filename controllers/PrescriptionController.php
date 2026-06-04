<?php

require_once __DIR__ . '/../models/Prescription.php';
require_once __DIR__ . '/../models/Appointment.php';

class PrescriptionController {
    private $prescriptionModel;
    private $appointmentModel;

    public function __construct() {
        $this->prescriptionModel = new Prescription();
        $this->appointmentModel = new Appointment();
    }

    public function download($id) {
        $prescription = $this->prescriptionModel->find($id);
        if (!$prescription) {
            die("Prescription not found.");
        }

        $appointment = $this->appointmentModel->find($prescription['appointment_id']);
        
        // Security Check: Only involved parties or admin can access
        if ($_SESSION['user_role'] !== 'admin' && 
            $_SESSION['user_id'] !== $appointment['patient_id'] && 
            $_SESSION['user_id'] !== $appointment['doctor_id']) {
            header('HTTP/1.0 403 Forbidden');
            die("Unauthorized access.");
        }

        $filePath = __DIR__ . '/../public/' . $prescription['file_path'];
        if (file_exists($filePath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="prescription_' . $id . '.pdf"');
            readfile($filePath);
            exit();
        } else {
            die("File not found on server.");
        }
    }
}
