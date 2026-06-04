<?php

abstract class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function all() {
        return $this->query("SELECT * FROM {$this->table}")->fetchAll();
    }

    public function find($id) {
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id])->fetch();
    }
}
