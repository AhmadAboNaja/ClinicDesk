<?php

class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';

        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['dbname']);
            $this->connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed.');
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query(string $sql, array $params = []) {
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        $operation = strtolower(strtok(trim($sql), " \n\r\t"));
        if ($operation === 'select' || $operation === 'show' || $operation === 'describe') {
            return $statement;
        }

        return $statement;
    }

    public function lastInsertId(): string {
        return $this->connection->lastInsertId();
    }
}
