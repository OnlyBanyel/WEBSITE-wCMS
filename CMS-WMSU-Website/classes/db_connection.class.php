<?php

class Database {
    private $dbhost;
    private $dbname;
    private $user;
    private $password;

    private $port;
    protected $db;

    public function __construct() {
        // For Docker, values will be from .env or docker-compose
        // For local (XAMPP), fallback defaults will be used
        $this->dbhost   = getenv('DB_HOST') ?: '127.0.0.1';  // Avoid 'localhost' due to socket issues in some setups
        $this->dbname   = getenv('DB_NAME') ?: 'wmsucms';
        $this->user     = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ''; // Handles empty string env var
        $this->port     = getenv('DB_PORT') ?: '3306'; // Default MySQL port
    }

    public function connect() {
        try {
            // Explicit port to ensure compatibility in Docker and local
            $dsn = "mysql:host={$this->dbhost};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            
            $this->db = new PDO($dsn, $this->user, $this->password);

            // Error reporting to exception mode
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Database connection error: " . $e->getMessage();
            $this->db = null;
        }

        return $this->db;
    }
}
?>
