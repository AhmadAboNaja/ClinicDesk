<?php

require_once __DIR__ . '/../core/Model.php';

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        return $this->query("SELECT * FROM users WHERE email = ?", [$email])->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, role, phone, is_active, first_login) 
                VALUES (:name, :email, :password, :role, :phone, :is_active, :first_login)";
        $this->query($sql, $data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        return $this->query($sql, $data);
    }
}
