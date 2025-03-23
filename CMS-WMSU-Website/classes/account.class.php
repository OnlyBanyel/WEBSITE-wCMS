<?php
require_once "../tools/functions.php";
require_once __DIR__ . "/db_connection.class.php";

    Class Accounts{

        public $id;       
        public $email;
        public $password;

        protected $db;
    

        function __construct(){
            $this->db = new Database;
        }

        function cleanAccount($email, $password){
            $this->email = cleanInput($email);
            $this->password = cleanInput($password);
        }

        function addAccount(){
            $sql = "INSERT INTO accounts (email, password)
                    VALUES (:email, :password);";
            $qry = $this->db->connect()->prepare($sql);
            $hashpass = password_hash($this->password, PASSWORD_DEFAULT);
            $qry->bindParam(":email", $this->email);
            $qry->bindParam(":password", $hashpass);

            if ($qry->execute());
        }
        
}
?>