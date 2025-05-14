<?php
class Database {
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    public function __construct() {
        // Get database credentials from environment variables
        $this->host = getenv('DB_HOST') ?: 'db';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->database = getenv('DB_NAME') ?: 'wmsucms';
    }

    public function connect() {
        try {
            // Add error handling and retry logic
            $retries = 5;
            $retry_interval = 5; // seconds
            
            for ($i = 0; $i < $retries; $i++) {
                try {
                    $this->connection = new PDO(
                        "mysql:host={$this->host};dbname={$this->database}",
                        $this->username,
                        $this->password,
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
                    
                    // If we get here, connection succeeded
                    return $this->connection;
                } catch (PDOException $e) {
                    // If this is the last retry, throw the exception
                    if ($i === $retries - 1) {
                        throw $e;
                    }
                    
                    // Otherwise wait and retry
                    error_log("Database connection failed, retrying in {$retry_interval} seconds...");
                    sleep($retry_interval);
                }
            }
        } catch (PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            // Return null instead of throwing to prevent fatal errors
            return null;
        }
    }
}
?>