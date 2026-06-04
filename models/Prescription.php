<?php

require_once __DIR__ . '/../core/Model.php';

class Prescription extends Model {
    protected $table = 'prescriptions';

    public function create($data) {
        $sql = "INSERT INTO prescriptions (appointment_id, diagnosis, medications, notes, file_path) 
                VALUES (:appointment_id, :diagnosis, :medications, :notes, :file_path)";
        return $this->query($sql, $data);
    }

    public function getByAppointment($appointmentId) {
        return $this->query("SELECT * FROM prescriptions WHERE appointment_id = ?", [$appointmentId])->fetch();
    }

    public function getPatientPrescriptions($patientId) {
        $sql = "SELECT p.*, a.appt_date, u.name as doctor_name 
                FROM prescriptions p 
                JOIN appointments a ON p.appointment_id = a.id 
                JOIN users u ON a.doctor_id = u.id 
                WHERE a.patient_id = ? 
                ORDER BY a.appt_date DESC";
        return $this->query($sql, [$patientId])->fetchAll();
    }
}
