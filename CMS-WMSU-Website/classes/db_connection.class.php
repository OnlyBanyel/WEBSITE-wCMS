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
        $options = [
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/aiven/ca.pem',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
            // Add this for MySQL 8+ authentication
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];

        $dsn = "mysql:host={$this->dbhost};port={$this->port};dbname={$this->dbname}";
        
        $this->db = new PDO($dsn, $this->user, $this->password, $options);
        
        // Test the connection
        $this->db->query("SELECT 1");
        
        return $this->db;
    } catch (PDOException $e) {
        error_log("Full connection error: " . print_r($e, true));
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}
}
?>
