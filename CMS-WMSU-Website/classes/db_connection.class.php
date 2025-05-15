<?php

class Database {
    private $dbhost;
    private $dbname;
    private $user;
    private $password;
    private $port;
    protected $db;

    public function __construct() {
        $this->dbhost   = getenv('DB_HOST') ?: '127.0.0.1';
        $this->dbname   = getenv('DB_NAME') ?: 'wmsucms';
        $this->user     = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
        $this->port     = getenv('DB_PORT') ?: '3306';
    }

    public function connect() {
        try {
            $dsn = "mysql:host={$this->dbhost};port={$this->port};dbname={$this->dbname};charset=utf8mb4";

            // Optional: only include SSL if ca.pem is mounted
            $ssl_ca = '/etc/ssl/aiven/ca.pem';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            if (file_exists($ssl_ca)) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = $ssl_ca;
            }

            $this->db = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }

        return $this->db;
    }
}
?>
