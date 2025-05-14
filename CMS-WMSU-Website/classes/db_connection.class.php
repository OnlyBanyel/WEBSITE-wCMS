<?php

class Database {
    private $dbhost;
    private $dbname;
    private $user;
    private $password;
    protected $db;

    public function __construct() {
        $this->dbhost = getenv('DB_HOST') ?: 'localhost';   // fallback to 'localhost' if not set
        $this->dbname = getenv('DB_NAME') ?: 'wmsucms';     // fallback to 'wmsucms'
        $this->user = getenv('DB_USER') ?: 'root';          // fallback to 'root'
        $this->password = getenv('DB_PASS') ?: '';          // fallback to empty string
    }

    public function connect() {
        try {
            $this->db = new PDO(
                "mysql:host={$this->dbhost};dbname={$this->dbname}",
                $this->user,
                $this->password
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->db;
    }
}
?>
