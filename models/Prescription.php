<?php

require_once __DIR__ . '/BaseModel.php';

class Prescription extends BaseModel
{
    public function getAll(): array
    {
        $sql = 'SELECT p.*, a.appt_date, d.name as doctor_name, u.name as patient_name
                FROM prescriptions p
                JOIN appointments a ON p.appointment_id = a.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                JOIN users u ON a.patient_id = u.id
                ORDER BY a.appt_date DESC';
        return $this->fetchAll($sql);
    }
    public function getByDoctor(int $doctorId): array
    {
        $sql = 'SELECT p.*, a.appt_date, u.name as patient_name, d.name as doctor_name
                FROM prescriptions p
                JOIN appointments a ON p.appointment_id = a.id
                JOIN users u ON a.patient_id = u.id
                JOIN users d ON a.doctor_id = d.id
                WHERE a.doctor_id = ? AND a.status = "completed"
                ORDER BY a.appt_date DESC';
        return $this->fetchAll($sql, [$doctorId]);
    }
    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM prescriptions WHERE id = ?', [$id]);
    }

    public function findByAppointmentId(int $appointmentId): ?array
    {
        return $this->fetchOne('SELECT * FROM prescriptions WHERE appointment_id = ?', [$appointmentId]);
    }

    public function create(array $data): int
    {
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

    public function update(int $id, array $data): bool
    {
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

    public function getByPatient(int $patientId): array
    {
        $sql = 'SELECT p.*, a.appt_date, d.name as doctor_name
                FROM prescriptions p
                JOIN appointments a ON p.appointment_id = a.id
                JOIN doctors doc ON a.doctor_id = doc.user_id
                JOIN users d ON doc.user_id = d.id
                WHERE a.patient_id = ? AND a.status = "completed"
                ORDER BY a.appt_date DESC';
        return $this->fetchAll($sql, [$patientId]);
    }

    public function countByPatient(int $patientId): int
    {
        $result = $this->fetchOne(
            'SELECT COUNT(*) as total FROM prescriptions p
             JOIN appointments a ON p.appointment_id = a.id
             WHERE a.patient_id = ?',
            [$patientId]
        );
        return $result ? (int) $result['total'] : 0;
    }
    public function countAll(): int
    {
        $result = $this->fetchOne(
            'SELECT COUNT(*) AS total FROM prescriptions'
        );

        return (int)($result['total'] ?? 0);
    }

    public function countByDoctor(int $doctorId): int
    {
        $result = $this->fetchOne(
            'SELECT COUNT(*) AS total
         FROM prescriptions p
         JOIN appointments a ON p.appointment_id = a.id
         WHERE a.doctor_id = ?',
            [$doctorId]
        );

        return (int)($result['total'] ?? 0);
    }

    public function getPaged(int $page, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;

        $sql = "
        SELECT p.*, a.appt_date,
               d.name AS doctor_name,
               u.name AS patient_name
        FROM prescriptions p
        JOIN appointments a ON p.appointment_id = a.id
        JOIN doctors doc ON a.doctor_id = doc.user_id
        JOIN users d ON doc.user_id = d.id
        JOIN users u ON a.patient_id = u.id
        ORDER BY a.appt_date DESC
        LIMIT $perPage OFFSET $offset
    ";

        return $this->fetchAll($sql);
    }

    public function getByDoctorPaged(
        int $doctorId,
        int $page,
        int $perPage = ITEMS_PER_PAGE
    ): array {
        $offset = ($page - 1) * $perPage;

        $sql = "
        SELECT p.*, a.appt_date,
               u.name AS patient_name,
               d.name AS doctor_name
        FROM prescriptions p
        JOIN appointments a ON p.appointment_id = a.id
        JOIN users u ON a.patient_id = u.id
        JOIN users d ON a.doctor_id = d.id
        WHERE a.doctor_id = ?
        AND a.status = 'completed'
        ORDER BY a.appt_date DESC
        LIMIT $perPage OFFSET $offset
    ";

        return $this->fetchAll($sql, [$doctorId]);
    }

    public function getByPatientPaged(
        int $patientId,
        int $page,
        int $perPage = ITEMS_PER_PAGE
    ): array {
        $offset = ($page - 1) * $perPage;

        $sql = "
        SELECT p.*, a.appt_date,
               d.name AS doctor_name
        FROM prescriptions p
        JOIN appointments a ON p.appointment_id = a.id
        JOIN doctors doc ON a.doctor_id = doc.user_id
        JOIN users d ON doc.user_id = d.id
        WHERE a.patient_id = ?
        AND a.status = 'completed'
        ORDER BY a.appt_date DESC
        LIMIT $perPage OFFSET $offset
    ";

        return $this->fetchAll($sql, [$patientId]);
    }
    public function getFiltered(
    string $role,
    int $userId,
    int $page,
    array $filters = [],
    int $perPage = ITEMS_PER_PAGE
): array
{
    $offset = ($page - 1) * $perPage;

    $sql = "
        SELECT p.*, a.appt_date,
               d.name AS doctor_name,
               u.name AS patient_name
        FROM prescriptions p
        JOIN appointments a ON p.appointment_id = a.id
        JOIN doctors doc ON a.doctor_id = doc.user_id
        JOIN users d ON doc.user_id = d.id
        JOIN users u ON a.patient_id = u.id
        WHERE 1=1
    ";

    $params = [];

    if ($role === 'doctor') {
        $sql .= " AND a.doctor_id = ?";
        $params[] = $userId;
    }

    if ($role === 'patient') {
        $sql .= " AND a.patient_id = ?";
        $params[] = $userId;
    }

    if (!empty($filters['doctor_name'])) {
        $sql .= " AND d.name LIKE ?";
        $params[] = '%' . $filters['doctor_name'] . '%';
    }

    if (!empty($filters['patient_name'])) {
        $sql .= " AND u.name LIKE ?";
        $params[] = '%' . $filters['patient_name'] . '%';
    }

    if (!empty($filters['date_from'])) {
        $sql .= " AND a.appt_date >= ?";
        $params[] = $filters['date_from'];
    }

    if (!empty($filters['date_to'])) {
        $sql .= " AND a.appt_date <= ?";
        $params[] = $filters['date_to'];
    }

    $sql .= " ORDER BY a.appt_date DESC LIMIT $perPage OFFSET $offset";

    return $this->fetchAll($sql, $params);
}
public function countFiltered(string $role, int $userId = 0, array $filters = []): int
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM prescriptions p
        JOIN appointments a ON p.appointment_id = a.id
        JOIN doctors doc ON a.doctor_id = doc.user_id
        JOIN users d ON doc.user_id = d.id
        JOIN users u ON a.patient_id = u.id
        WHERE 1=1
    ";

    $params = [];

    if ($role === 'doctor') {
        $sql .= " AND a.doctor_id = ?";
        $params[] = $userId;
    }

    if ($role === 'patient') {
        $sql .= " AND a.patient_id = ?";
        $params[] = $userId;
    }

    if (!empty($filters['doctor_name'])) {
        $sql .= " AND d.name LIKE ?";
        $params[] = '%' . $filters['doctor_name'] . '%';
    }

    if (!empty($filters['patient_name'])) {
        $sql .= " AND u.name LIKE ?";
        $params[] = '%' . $filters['patient_name'] . '%';
    }

    if (!empty($filters['date_from'])) {
        $sql .= " AND a.appt_date >= ?";
        $params[] = $filters['date_from'];
    }

    if (!empty($filters['date_to'])) {
        $sql .= " AND a.appt_date <= ?";
        $params[] = $filters['date_to'];
    }

    $result = $this->fetchOne($sql, $params);

    return (int)($result['total'] ?? 0);
}
}
