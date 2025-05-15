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
        // Verify environment variables
        error_log("DB Connection Attempt:");
        error_log("Host: " . $this->dbhost);
        error_log("Port: " . $this->port);
        error_log("User: " . $this->user);
        
        // Verify certificate exists
        $certPath = '/etc/ssl/aiven/ca.pem';
        error_log("Certificate exists: " . (file_exists($certPath) ? 'Yes' : 'No'));
        
        $options = [
            PDO::MYSQL_ATTR_SSL_CA => $certPath,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        $dsn = "mysql:host={$this->dbhost};port={$this->port};dbname={$this->dbname}";
        
        // Test raw socket connection first
        $socket = @fsockopen($this->dbhost, $this->port, $errno, $errstr, 5);
        if (!$socket) {
            throw new Exception("Raw TCP connection failed: $errstr ($errno)");
        }
        fclose($socket);
        
        $this->db = new PDO($dsn, $this->user, $this->password, $options);
        return $this->db;
    } catch (Exception $e) {
        error_log("FULL CONNECTION ERROR: " . $e->getMessage());
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}
}
?>
