<?php

class Database {
    private $dbhost = getenv('DB_HOST');  // Use environment variable for host
    private $dbname = getenv('DB_NAME');  // 'wmsucms'
    private $user = getenv('DB_USER');   // 'root'
    private $password = getenv('DB_PASS'); // Empty password

    protected $db;

    function connect(){
        try {
            $this->db = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->user, $this->password);
            // Set the PDO error mode to exception
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->db;
    }
}
?>
