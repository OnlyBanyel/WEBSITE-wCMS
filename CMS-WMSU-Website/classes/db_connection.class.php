<?php

    class Database{
        private $dbhost = "localhost";
        private $dbname = "wmsuCMS";
        private $user = "root";
        private $password = "";

        protected $db;

        function connect(){
            
            try{
            $this->db = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->user, $this->password);
        }               
            catch(PDOException $e){
            echo "connection error" . $e->getMessage();
        }

        return $this->db;
    }

}
?>