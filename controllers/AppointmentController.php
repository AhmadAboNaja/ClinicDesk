<?php

require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../core/CSRF.php';

class AppointmentController {
    private $appointmentModel;

    public function __construct() {
        $this->appointmentModel = new Appointment();
    }

    public function book() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::validateToken($_POST['csrf_token']);

            $data = [
                'patient_id' => $_SESSION['user_id'],
                'doctor_id' => $_POST['doctor_id'],
                'appt_date' => $_POST['appt_date'],
                'appt_time' => $_POST['appt_time'],
                'reason' => htmlspecialchars($_POST['reason'], ENT_QUOTES, 'UTF-8')
            ];

            // Validation: Date not in the past
            if (strtotime($data['appt_date']) < strtotime(date('Y-m-d'))) {
                $_SESSION['error'] = "Appointment date cannot be in the past.";
                header('Location: index.php?page=book_appointment');
                exit();
            }

            // Conflict Check
            if ($this->appointmentModel->hasConflict($data['doctor_id'], $data['appt_date'], $data['appt_time'])) {
                $_SESSION['error'] = "This slot is already booked. Please choose another time.";
                header('Location: index.php?page=book_appointment');
                exit();
            }

            if ($this->appointmentModel->create($data)) {
                $_SESSION['success'] = "Appointment booked successfully!";
                header('Location: index.php?page=my_appointments');
                exit();
            }
        }
    }

    public function cancel($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::validateToken($_POST['csrf_token']);
            $this->appointmentModel->query("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND patient_id = ?", [$id, $_SESSION['user_id']]);
            $_SESSION['success'] = "Appointment cancelled.";
            header('Location: index.php?page=my_appointments');
            exit();
        }
    }
}
