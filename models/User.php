<?php

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function getByRole(string $role = ''): array
    {
        if ($role !== '') {
            return $this->fetchAll('SELECT * FROM users WHERE role = ? ORDER BY created_at DESC', [$role]);
        }

        return $this->fetchAll('SELECT * FROM users ORDER BY created_at DESC');
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (name, email, password, role, phone, is_active, first_login) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->execute($sql, [
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['phone'] ?? null,
            $data['is_active'] ?? 1,
            $data['first_login'] ?? 1,
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
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $this->execute($sql, $params);
        return $stmt !== false;
    }

    public function updatePassword(int $id, string $newHash): bool
    {
        $stmt = $this->execute('UPDATE users SET password = ?, first_login = 0 WHERE id = ?', [$newHash, $id]);
        return $stmt !== false;
    }

    public function getAllPaginated(int $page, string $role = ''): array
    {
        $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);
        if ($role !== '') {
            return $this->fetchAll('SELECT * FROM users WHERE role = ? ORDER BY created_at DESC LIMIT ? OFFSET ?', [$role, ITEMS_PER_PAGE, $offset]);
        }

        return $this->fetchAll('SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?', [ITEMS_PER_PAGE, $offset]);
    }
    public function getUsersPaginated(int $page, string $role = '', string $search = '', string $status = ''): array
    {
        $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);

        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($role !== '') {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        if ($status !== '') {
            $sql .= " AND is_active = ?";
            $params[] = (int)$status;
        }

        $sql .= " ORDER BY created_at DESC LIMIT " . ITEMS_PER_PAGE . " OFFSET " . $offset;

        return $this->fetchAll($sql, $params);
    }
    public function countUsers(string $role = '', string $search = '', string $status = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($role !== '') {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        if ($status !== '') {
            $sql .= " AND is_active = ?";
            $params[] = (int)$status;
        }

        $result = $this->fetchOne($sql, $params);

        return $result ? (int)$result['total'] : 0;
    }
    public function countAll(string $role = ''): int
    {
        if ($role !== '') {
            $result = $this->fetchOne('SELECT COUNT(*) as total FROM users WHERE role = ?', [$role]);
        } else {
            $result = $this->fetchOne('SELECT COUNT(*) as total FROM users');
        }

        return $result ? (int) $result['total'] : 0;
    }

    public function toggleActive(int $id): bool
    {
        $user = $this->findById($id);
        if (!$user) {
            return false;
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $stmt = $this->execute('UPDATE users SET is_active = ? WHERE id = ?', [$newStatus, $id]);
        return $stmt !== false;
    }

    public function countByRole(): array
    {
        return $this->fetchAll('SELECT role, COUNT(*) as total FROM users GROUP BY role');
    }
}
