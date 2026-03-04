<?php
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/config.php';
}

class Database {
    private $conn;

    public function connect() {
        if ($this->conn !== null) {
            return $this->conn;
        }
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $this->conn = new PDO($dsn, DB_USER, DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        } catch (PDOException $e) {
            die('Database Connection Failed: ' . $e->getMessage());
        }
    }
}
?>
