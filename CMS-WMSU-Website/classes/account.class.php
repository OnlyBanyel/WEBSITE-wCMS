<?php
require_once "../tools/functions.php";
require_once __DIR__ . "/db_connection.class.php";

    Class Accounts{

        public $id;       
        public $email;
        public $password;
        public $role;
        public $subpage_assigned;

        protected $db;
    

        function __construct(){
            $this->db = new Database;
        }

        function cleanAccount($email, $password, $role, $subpage_assigned = NULL){
            $this->email = cleanInput($email);
            $this->password = cleanInput($password);
            $this->role = cleanInput($role);
            if ($subpage_assigned != NULL){
            $this->subpage_assigned = cleanInput($subpage_assigned);}
        }

        function addAccount(){
            $sql = "INSERT INTO accounts (email, password, pageID, role_id, subpage_assigned)
                    VALUES (:email, :password, 3, :role_id, :subpage_assigned);";
            $qry = $this->db->connect()->prepare($sql);
            $hashpass = password_hash($this->password, PASSWORD_DEFAULT);
            $qry->bindParam(":email", $this->email);
            $qry->bindParam(":password", $hashpass);
            $qry->bindParam(":role_id", $this->role);
            $qry->bindParam(":subpage_assigned", $this->subpage_assigned);

            if ($qry->execute());
        }
        
}
?>