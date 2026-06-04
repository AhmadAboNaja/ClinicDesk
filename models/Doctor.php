<?php

require_once __DIR__ . '/../core/Model.php';

class Doctor extends Model {
    protected $table = 'doctors';

    public function getDetails($userId) {
        $sql = "SELECT d.*, u.name, u.email, s.name as specialization 
                FROM doctors d 
                JOIN users u ON d.user_id = u.id 
                JOIN specializations s ON d.specialization_id = s.id 
                WHERE d.user_id = ?";
        return $this->query($sql, [$userId])->fetch();
    }

    public function getSchedule($doctorId) {
        $sql = "SELECT a.*, u.name as patient_name 
                FROM appointments a 
                JOIN users u ON a.patient_id = u.id 
                WHERE a.doctor_id = ? 
                ORDER BY a.appt_date ASC, a.appt_time ASC";
        return $this->query($sql, [$doctorId])->fetchAll();
    }

    public function updateStatus($appointmentId, $status, $notes = '') {
        $sql = "UPDATE appointments SET status = ?, doctor_notes = ? WHERE id = ?";
        return $this->query($sql, [$status, $notes, $appointmentId]);
    }
}
