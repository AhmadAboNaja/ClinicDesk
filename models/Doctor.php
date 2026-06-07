<?php

require_once __DIR__ . '/BaseModel.php';

class Doctor extends BaseModel
{
public function getDoctorsPaginated(int $page, string $search = '', string $specialization = ''): array
{
    $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);

    $sql = "
        SELECT d.*, u.name as user_name, u.email, s.name as specialization
        FROM doctors d
        JOIN users u ON d.user_id = u.id
        JOIN specializations s ON d.specialization_id = s.id
        WHERE 1=1
    ";

    $params = [];

    if ($search !== '') {
        $sql .= " AND (u.name LIKE ? OR u.email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($specialization !== '') {
        $sql .= " AND d.specialization_id = ?";
        $params[] = $specialization;
    }

    $sql .= " ORDER BY u.name ASC LIMIT " . ITEMS_PER_PAGE . " OFFSET " . $offset;

    return $this->fetchAll($sql, $params);
}
public function countDoctors(string $search = '', string $specialization = ''): int
{
    $sql = "
        SELECT COUNT(*) as total
        FROM doctors d
        JOIN users u ON d.user_id = u.id
        WHERE 1=1
    ";

    $params = [];

    if ($search !== '') {
        $sql .= " AND (u.name LIKE ? OR u.email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($specialization !== '') {
        $sql .= " AND d.specialization_id = ?";
        $params[] = $specialization;
    }

    $result = $this->fetchOne($sql, $params);

    return $result ? (int)$result['total'] : 0;
}
    public function findByUserId(int $userId): ?array
    {
        return $this->fetchOne(
            'SELECT d.*, u.name as user_name, u.email, s.name as specialization FROM doctors d JOIN users u ON d.user_id = u.id JOIN specializations s ON d.specialization_id = s.id WHERE d.user_id = ?',
            [$userId]
        );
    }
    public function getDoctorsAvailable(): array
    {
        return $this->fetchAll("
        SELECT u.*
        FROM users u
        WHERE u.role = 'doctor'
        AND u.id NOT IN (
            SELECT user_id FROM doctors
        )
        ORDER BY u.created_at DESC
    ");
    }
    public function findById(int $doctorId): ?array
    {
        return $this->fetchOne(
            'SELECT d.*, u.name as user_name, u.email, s.name as specialization FROM doctors d JOIN users u ON d.user_id = u.id JOIN specializations s ON d.specialization_id = s.id WHERE d.user_id = ?',
            [$doctorId]
        );
    }

    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT d.*, u.name as user_name, s.name as specialization FROM doctors d JOIN users u ON d.user_id = u.id JOIN specializations s ON d.specialization_id = s.id ORDER BY u.name ASC'
        );
    }

    public function getAllPaginated(int $page): array
    {
        $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);
        return $this->fetchAll(
            'SELECT d.*, u.name as user_name, u.email, s.name as specialization FROM doctors d JOIN users u ON d.user_id = u.id JOIN specializations s ON d.specialization_id = s.id ORDER BY u.name ASC LIMIT ? OFFSET ?',
            [ITEMS_PER_PAGE, $offset]
        );
    }

    public function countAll(): int
    {
        $result = $this->fetchOne('SELECT COUNT(*) as total FROM doctors');
        return $result ? (int) $result['total'] : 0;
    }

    public function create(array $data): int
    {
        $this->execute(
            'INSERT INTO doctors (user_id, specialization_id, bio, consultation_fee, available_days, profile_photo) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $data['user_id'],
                $data['specialization_id'],
                $data['bio'] ?? null,
                $data['consultation_fee'],
                $data['available_days'],
                $data['profile_photo'] ?? null,
            ]
        );
        return (int) $this->db->lastInsertId();
    }

    public function update(int $doctorId, array $data): bool
    {
        $fields = [];
        $params = [];
        foreach ($data as $column => $value) {
            $fields[] = "$column = ?";
            $params[] = $value;
        }
        $params[] = $doctorId;
        $stmt = $this->execute('UPDATE doctors SET ' . implode(', ', $fields) . ' WHERE user_id = ?', $params);
        return $stmt !== false;
    }

    public function getAvailableDays(int $doctorId): array
    {
        $row = $this->fetchOne('SELECT available_days FROM doctors WHERE user_id = ?', [$doctorId]);
        return $row ? array_filter(array_map('trim', explode(',', $row['available_days']))) : [];
    }
}
