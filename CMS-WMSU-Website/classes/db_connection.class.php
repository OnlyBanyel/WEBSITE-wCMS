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
        // Debug output
        error_log("Attempting connection to: {$this->dbhost}:{$this->port}");
        
        // Test raw TCP connection first
        $timeout = 5;
        $socket = @fsockopen($this->dbhost, $this->port, $errno, $errstr, $timeout);
        
        if (!$socket) {
            throw new Exception("Raw TCP connection failed: $errstr ($errno)");
        }
        fclose($socket);
        
        // SSL options
        $options = [
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/aiven/ca.pem',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        $dsn = "mysql:host={$this->dbhost};port={$this->port};dbname={$this->dbname}";
        $this->db = new PDO($dsn, $this->user, $this->password, $options);
        
        return $this->db;
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        throw $e;
    }
}
}
?>
