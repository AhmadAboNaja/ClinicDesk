<?php

require_once __DIR__ . '/../core/Model.php';

class Appointment extends Model {
    protected $table = 'appointments';

    public function hasConflict($doctorId, $date, $time) {
        $sql = "SELECT COUNT(*) as count FROM appointments 
                WHERE doctor_id = ? AND appt_date = ? AND appt_time = ? AND status != 'cancelled'";
        $result = $this->query($sql, [$doctorId, $date, $time])->fetch();
        return $result['count'] > 0;
    }

    public function create($data) {
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appt_date, appt_time, reason, status) 
                VALUES (:patient_id, :doctor_id, :appt_date, :appt_time, :reason, 'pending')";
        $this->query($sql, $data);
        return $this->db->lastInsertId();
    }

    public function getPatientAppointments($patientId) {
        $sql = "SELECT a.*, u.name as doctor_name, s.name as specialization 
                FROM appointments a 
                JOIN users u ON a.doctor_id = u.id 
                JOIN doctors d ON u.id = d.user_id 
                JOIN specializations s ON d.specialization_id = s.id 
                WHERE a.patient_id = ? 
                ORDER BY a.appt_date DESC, a.appt_time DESC";
        return $this->query($sql, [$patientId])->fetchAll();
    }
}
