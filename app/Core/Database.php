<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $config = require CONFIG_PATH . '/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']} COLLATE {$config['collation']}",
            ]);
        } catch (PDOException $e) {
            if ($_ENV['APP_DEBUG'] ?? false) {
                throw $e;
            }
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => ['code' => 'DB_CONNECTION_ERROR', 'message' => 'Database connection failed']]);
            exit;
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a query with prepared statement bindings.
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch all rows.
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Fetch a single row.
     */
    public function fetch(string $sql, array $params = []): ?object
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Get last insert ID.
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
