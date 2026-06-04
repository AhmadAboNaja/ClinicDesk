<?php

require_once __DIR__ . '/BaseModel.php';

class Prescription extends BaseModel {
    public function findById(int $id): ?array {
        return $this->fetchOne('SELECT * FROM prescriptions WHERE id = ?', [$id]);
    }

    public function findByAppointmentId(int $appointmentId): ?array {
        return $this->fetchOne('SELECT * FROM prescriptions WHERE appointment_id = ?', [$appointmentId]);
    }

    public function create(array $data): int {
        $sql = 'INSERT INTO prescriptions (appointment_id, diagnosis, medications, notes, file_path)
                VALUES (?, ?, ?, ?, ?)';
        $this->execute($sql, [
            $data['appointment_id'],
            $data['diagnosis'],
            $data['medications'],
            $data['notes'] ?? null,
            $data['file_path'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        $sql = 'UPDATE prescriptions SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $this->execute($sql, $params);
        return $stmt !== false;
    }

    public function getByPatient(int $patientId): array {
        $sql = 'SELECT p.*, a.appt_date, d.name as doctor_name
                FROM prescriptions p
                JOIN appointments a ON p.appointment_id = a.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                WHERE a.patient_id = ? AND a.status = "completed"
                ORDER BY a.appt_date DESC';
        return $this->fetchAll($sql, [$patientId]);
    }

    public function countByPatient(int $patientId): int {
        $result = $this->fetchOne(
            'SELECT COUNT(*) as total FROM prescriptions p
             JOIN appointments a ON p.appointment_id = a.id
             WHERE a.patient_id = ?',
            [$patientId]
        );
        return $result ? (int) $result['total'] : 0;
    }
}
