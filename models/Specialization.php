<?php

require_once __DIR__ . '/BaseModel.php';

class Specialization extends BaseModel {
    protected string $table = 'specializations';

    public function getAll(): array {
        return $this->fetchAll("SELECT * FROM specializations ORDER BY name ASC");
    }

    public function findById(int $id): ?array {
        return $this->fetchOne("SELECT * FROM specializations WHERE id = ?", [$id]);
    }

    public function create(string $name): bool {
        return (bool) $this->execute("INSERT INTO specializations (name) VALUES (?)", [$name]);
    }

    public function delete(int $id): bool {
        return (bool) $this->execute("DELETE FROM specializations WHERE id = ?", [$id]);
    }

    public function isSafeToDelete(int $id): bool {
        $result = $this->fetchOne("SELECT COUNT(*) as total FROM doctors WHERE specialization_id = ?", [$id]);
        return $result && $result['total'] == 0;
    }
}
