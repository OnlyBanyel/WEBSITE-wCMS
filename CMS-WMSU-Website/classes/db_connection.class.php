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
   // Temporary debug
    error_log("DB Connection Details:");
    error_log("Host: " . $this->dbhost);
    error_log("Port: " . $this->port);
    error_log("User: " . $this->user);
    error_log("DB Name: " . $this->dbname);
}

    public function connect() {
    try {
        $dsn = "mysql:host={$this->dbhost};port={$this->port};dbname={$this->dbname};charset=utf8mb4";

        $ssl_ca = '/etc/ssl/aiven/ca.pem';

        $options = [
            PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $this->db = new PDO($dsn, $this->user, $this->password, $options);
        return $this->db;

    } catch (PDOException $e) {
        // 🚨 This line gives us visibility into the problem
        die("PDO ERROR: " . $e->getMessage());
    }
}


}
?>
