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

        // SSL Configuration for Aiven
        $ssl_options = [
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/aiven/ca.pem',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Important for Aiven
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false // Better for containerized environments
        ];

        $this->db = new PDO($dsn, $this->user, $this->password, $ssl_options);

    } catch (PDOException $e) {
        // More detailed error reporting for debugging
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed. Check logs for details.");
    }

    return $this->db;
}

}
?>
