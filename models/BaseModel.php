<?php

require_once __DIR__ . '/../core/Database.php';

abstract class BaseModel {
    protected Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function execute(string $sql, array $params = []) {
        return $this->db->query($sql, $params);
    }

    protected function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->execute($sql, $params);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    }

    protected function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->execute($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
