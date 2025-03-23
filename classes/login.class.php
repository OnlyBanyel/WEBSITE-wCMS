<?php
require_once "../tools/functions.php";
require_once __DIR__ . "/db_connection.class.php";
    Class Login{

        public $id;
        public $email;
        public $password;
        protected $db;

        function __construct(){
            $this->db = new Database;

        }

        function clean(){
            $this->email = cleanInput($_POST['email']);
            $this->password = cleanInput($_POST['password']);
        }

        function auth(){
            $sql = "SELECT email, password from accounts WHERE email = :email";
            $qry = $this->db->connect()->prepare($sql);
            
            $qry->bindParam(":email", $this->email);
            $qry->execute();

            if($data = $qry->fetch()){

                if (password_verify($this->password, $data['password'])){
                    return $data;
                }

            }
            if (!$data){
                // Wrong User or Password
            }
            return null;
        }
        
        function fetchAccount(){
            $sql = "SELECT * from accounts WHERE email = :email";
            $qry = $this->db->connect()->prepare($sql);

            $qry->bindParam(":email", $this->email);

            if ($qry->execute()){
                $data = $qry->fetchAll(PDO::FETCH_ASSOC);
                return $data;
            }
        }
    }

?>