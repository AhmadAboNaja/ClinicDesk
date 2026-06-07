<?php

require_once __DIR__ . '/BaseModel.php';

class Specialization extends BaseModel
{
    public function getAllPaginated(int $page, string $search = ''): array
    {
        $offset = max(0, ($page - 1) * ITEMS_PER_PAGE);

        $sql = "
        SELECT 
            s.*,
            COUNT(d.user_id) AS doctor_count
        FROM specializations s
        LEFT JOIN doctors d ON d.specialization_id = s.id
        WHERE 1=1
    ";

        $params = [];

        if ($search !== '') {
            $sql .= " AND s.name LIKE ?";
            $params[] = "%$search%";
        }

        $sql .= "
        GROUP BY s.id
        ORDER BY s.name ASC
        LIMIT " . ITEMS_PER_PAGE . " OFFSET " . $offset;

        return $this->fetchAll($sql, $params);
    }
    public function countAll(string $search = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM specializations WHERE 1=1";
        $params = [];

        if ($search !== '') {
            $sql .= " AND name LIKE ?";
            $params[] = "%$search%";
        }

        $result = $this->fetchOne($sql, $params);

        return $result ? (int)$result['total'] : 0;
    }
    protected string $table = 'specializations';
    public function getAll(): array
    {
        return $this->fetchAll("
        SELECT 
            s.*,
            COUNT(d.user_id) AS doctor_count
        FROM specializations s
        LEFT JOIN doctors d ON d.specialization_id = s.id
        GROUP BY s.id
        ORDER BY s.name ASC
    ");
    }


    public function findById(int $id): ?array
    {
        return $this->fetchOne("SELECT * FROM specializations WHERE id = ?", [$id]);
    }

    public function create(string $name): bool
    {
        return (bool) $this->execute("INSERT INTO specializations (name) VALUES (?)", [$name]);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->execute("DELETE FROM specializations WHERE id = ?", [$id]);
    }

    public function isSafeToDelete(int $id): bool
    {
        $result = $this->fetchOne("SELECT COUNT(*) as total FROM doctors WHERE specialization_id = ?", [$id]);
        return $result && $result['total'] == 0;
    }
}
