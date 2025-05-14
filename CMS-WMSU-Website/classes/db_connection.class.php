<?php

class Database {
    private $dbhost;
    private $dbname;
    private $user;
    private $password;
    protected $db;

    public function __construct() {
        // These values are pulled from environment variables for Docker use
        $this->dbhost = getenv('DB_HOST') ?: '127.0.0.1';   // 127.0.0.1 instead of 'localhost' for Docker container
        $this->dbname = getenv('DB_NAME') ?: 'wmsucms';      // Default 'wmsucms'
        $this->user = getenv('DB_USER') ?: 'root';           // Default 'root'
        $this->password = getenv('DB_PASS') ?: '';           // Default empty password for 'root'
    }

    public function connect() {
        try {
            // Use TCP/IP connection for Docker container, set port 3306
            $dsn = "mysql:host={$this->dbhost};port=3306;dbname={$this->dbname}";
            
            // Create PDO connection
            $this->db = new PDO($dsn, $this->user, $this->password);
            
            // Set PDO error mode to exception to catch any issues
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Optional: Output success message to confirm connection
            // echo "Connected to the database successfully!";
            
        } catch(PDOException $e) {
            // Handle any connection error
            echo "Connection error: " . $e->getMessage();
            $this->db = null;
        }

        return $this->db;
    }
}
?>
