<?php

require_once __DIR__ . '/BaseModel.php';

class Appointment extends BaseModel
{
    public function book(array $data): bool
    {
        $sql = 'INSERT INTO appointments (patient_id, doctor_id, appt_date, appt_time, reason, status)
                VALUES (?, ?, ?, ?, ?, ?)';
        try {
            $this->execute($sql, [
                $data['patient_id'],
                $data['doctor_id'],
                $data['appt_date'],
                $data['appt_time'],
                $data['reason'] ?? null,
                'pending',
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function hasConflict(int $doctorId, string $date, string $time): bool
    {
        $result = $this->fetchOne(
            'SELECT COUNT(*) as total FROM appointments
             WHERE doctor_id = ? AND appt_date = ? AND appt_time = ? AND status != "cancelled"',
            [$doctorId, $date, $time]
        );
        return $result && (int) $result['total'] > 0;
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT a.*, u.name as patient_name, u.email as patient_email,
                       d.name as doctor_name, s.name as specialization
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE a.id = ?';
        return $this->fetchOne($sql, [$id]);
    }

    public function getByPatient(int $patientId, int $page, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);
        $conditions = [];
        $params = [];

        $conditions[] = 'a.patient_id = ?';
        $params[] = $patientId;

        if (!empty($filters['status'])) {
            $conditions[] = 'a.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['date_from'])) {
            $conditions[] = 'a.appt_date >= ?';
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $conditions[] = 'a.appt_date <= ?';
            $params[] = $filters['date_to'];
        }

        $whereClause = implode(' AND ', $conditions);
        $sql = "SELECT a.*, d.name as doctor_name, s.name as specialization
                FROM appointments a
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE $whereClause
                ORDER BY a.appt_date DESC, a.appt_time DESC
                LIMIT ? OFFSET ?";
        $params[] = ITEMS_PER_PAGE;
        $params[] = $offset;
        return $this->fetchAll($sql, $params);
    }

    public function getByDoctor(int $doctorId, int $page, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);
        $conditions = [];
        $params = [];

        $conditions[] = 'a.doctor_id = ?';
        $params[] = $doctorId;

        if (!empty($filters['status'])) {
            $conditions[] = 'a.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['date_from'])) {
            $conditions[] = 'a.appt_date >= ?';
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $conditions[] = 'a.appt_date <= ?';
            $params[] = $filters['date_to'];
        }

        $whereClause = implode(' AND ', $conditions);
        $sql = "SELECT a.*, u.name as patient_name, u.email as patient_email
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                WHERE $whereClause
                ORDER BY a.appt_date DESC, a.appt_time DESC
                LIMIT ? OFFSET ?";
        $params[] = ITEMS_PER_PAGE;
        $params[] = $offset;
        return $this->fetchAll($sql, $params);
    }

    public function getAll(array $filters = []): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['doctor_id'])) {
            $conditions[] = 'a.doctor_id = ?';
            $params[] = $filters['doctor_id'];
        }

        if (!empty($filters['patient_name'])) {
            $conditions[] = 'u.name LIKE ?';
            $params[] = '%' . $filters['patient_name'] . '%';
        }

        if (!empty($filters['status'])) {
            $conditions[] = 'a.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['date_from'])) {
            $conditions[] = 'a.appt_date >= ?';
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $conditions[] = 'a.appt_date <= ?';
            $params[] = $filters['date_to'];
        }

        $whereClause = implode(' AND ', $conditions);
        if ($whereClause) {
            $whereClause = 'WHERE ' . $whereClause;
        }
        $sql = "SELECT a.*, u.name as patient_name, d.name as doctor_name, s.name as specialization
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                JOIN specializations s ON doc.specialization_id = s.id
                $whereClause
                ORDER BY a.created_at DESC";
        return $this->fetchAll($sql, $params);
    }

    public  function getPagenated(int $page, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);
        $conditions = [];
        $params = [];

        if (!empty($filters['doctor_id'])) {
            $conditions[] = 'a.doctor_id = ?';
            $params[] = $filters['doctor_id'];
        }

        if (!empty($filters['patient_name'])) {
            $conditions[] = 'u.name LIKE ?';
            $params[] = '%' . $filters['patient_name'] . '%';
        }

        if (!empty($filters['status'])) {
            $conditions[] = 'a.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['date_from'])) {
            $conditions[] = 'a.appt_date >= ?';
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $conditions[] = 'a.appt_date <= ?';
            $params[] = $filters['date_to'];
        }

        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT a.*, u.name as patient_name, d.name as doctor_name, s.name as specialization
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                JOIN specializations s ON doc.specialization_id = s.id
                $whereClause
                ORDER BY a.created_at DESC
                LIMIT ? OFFSET ?";
        $params[] = ITEMS_PER_PAGE;
        $params[] = $offset;
        return $this->fetchAll($sql, $params);
    }

    public function countFiltered(string $scope, int $scopeId, array $filters = []): int
    {
        $conditions = [];
        $params = [];

        if ($scope === 'patient') {
            $conditions[] = 'patient_id = ?';
            $params[] = $scopeId;
        } elseif ($scope === 'doctor') {
            $conditions[] = 'doctor_id = ?';
            $params[] = $scopeId;
        }

        if (!empty($filters['status'])) {
            $conditions[] = 'status = ?';
            $params[] = $filters['status'];
        }

        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT COUNT(*) as total FROM appointments $whereClause";
        $result = $this->fetchOne($sql, $params);
        return $result ? (int) $result['total'] : 0;
    }

    public function updateStatus(int $id, string $status, string $notes = ''): bool
    {
        $params = [$status];
        if ($notes !== '') {
            $params[] = $notes;
            $sql = 'UPDATE appointments SET status = ?, doctor_notes = ? WHERE id = ?';
        } else {
            $sql = 'UPDATE appointments SET status = ? WHERE id = ?';
        }
        $params[] = $id;
        $stmt = $this->execute($sql, $params);
        return $stmt !== false;
    }

    public function countByDate(string $date): int
    {
        $result = $this->fetchOne('SELECT COUNT(*) as total FROM appointments WHERE appt_date = ?', [$date]);
        return $result ? (int) $result['total'] : 0;
    }

    public function countByStatusForWeek(): array
    {
        return $this->fetchAll(
            'SELECT status, COUNT(*) as total FROM appointments
             WHERE WEEK(appt_date) = WEEK(NOW()) AND appt_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY status'
        );
    }

    public function getRecentAppointments(int $limit): array
    {
        $sql = 'SELECT a.*, u.name as patient_name, d.name as doctor_name, s.name as specialization
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                JOIN specializations s ON doc.specialization_id = s.id
                ORDER BY a.created_at DESC
                LIMIT ?';
        return $this->fetchAll($sql, [$limit]);
    }

    public function getDoctorAppointmentsByDate(int $doctorId, string $date): array
    {
        $sql = 'SELECT a.*, u.name as patient_name
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                WHERE a.doctor_id = ? AND a.appt_date = ? AND a.status != "cancelled"
                ORDER BY a.appt_time ASC';
        return $this->fetchAll($sql, [$doctorId, $date]);
    }

    public function countDoctorAppointmentsSummary(int $doctorId): array
    {
        $sql = 'SELECT
                  (SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND DATE_FORMAT(appt_date, "%Y-%m") = DATE_FORMAT(NOW(), "%Y-%m")) as month_total,
                  (SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND status = "pending") as pending,
                  (SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND status = "completed") as completed
                FROM dual';
        $result = $this->fetchOne($sql, [$doctorId, $doctorId, $doctorId]);
        return $result ?? ['month_total' => 0, 'pending' => 0, 'completed' => 0];
    }

    public function getDoctorUpcomingAppointments(int $doctorId, int $limit): array
    {
        $sql = 'SELECT a.*, u.name as patient_name
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                WHERE a.doctor_id = ? AND (a.appt_date > CURDATE() OR (a.appt_date = CURDATE() AND a.appt_time > TIME(NOW())))
                AND a.status != "cancelled"
                ORDER BY a.appt_date ASC, a.appt_time ASC
                LIMIT ?';
        return $this->fetchAll($sql, [$doctorId, $limit]);
    }

    public function countPatientActiveAppointments(int $patientId): int
    {
        $result = $this->fetchOne(
            'SELECT COUNT(*) as total FROM appointments
             WHERE patient_id = ? AND status IN ("pending", "confirmed")',
            [$patientId]
        );
        return $result ? (int) $result['total'] : 0;
    }

    public function countPatientCompletedAppointments(int $patientId): int
    {
        $result = $this->fetchOne(
            'SELECT COUNT(*) as total FROM appointments WHERE patient_id = ? AND status = "completed"',
            [$patientId]
        );
        return $result ? (int) $result['total'] : 0;
    }

    public function getPatientNextAppointment(int $patientId): ?array
    {
        $sql = 'SELECT a.*, d.name as doctor_name, s.name as specialization
                FROM appointments a
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE a.patient_id = ? AND a.status IN ("pending", "confirmed")
                AND (a.appt_date > CURDATE() OR (a.appt_date = CURDATE() AND a.appt_time > TIME(NOW())))
                ORDER BY a.appt_date ASC, a.appt_time ASC
                LIMIT 1';
        return $this->fetchOne($sql, [$patientId]);
    }

    public function getTodayAppointments(): array
    {
        $sql = 'SELECT a.*, u.name as patient_name, d.name as doctor_name
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                WHERE a.appt_date = CURDATE()
                ORDER BY a.appt_time ASC';
        return $this->fetchAll($sql);
    }
}
