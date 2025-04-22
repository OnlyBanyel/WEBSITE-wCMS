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
            $sql = "SELECT email, password, status from accounts WHERE email = :email";
            $qry = $this->db->connect()->prepare($sql);
            
            $qry->bindParam(":email", $this->email);
            $qry->execute();

            if($data = $qry->fetch()){

                if (password_verify($this->password, $data['password'])){

                    if($data['status'] == 1 || $data['status' == null])
                    {
                    return $data;
                    }else{
                        return null;
                    }
                }

            }
            if (!$data){
                // Wrong User or Password
            }
            return null;
        }
        
        function fetchAccount(){
            $sql = "SELECT * from accounts LEFT JOIN subpages ON subpage_assigned = subpageID WHERE email = :email";
            $qry = $this->db->connect()->prepare($sql);

            $qry->bindParam(":email", $this->email);

            if ($qry->execute()){
                $data = $qry->fetch(PDO::FETCH_ASSOC);
                return $data;
            }
        }

        function fetchCollegeData($subpage_assigned){
            $sql = "SELECT * from page_sections WHERE subpage = :subpage_assigned";
            $qry = $this->db->connect()->prepare($sql);

            $qry->bindParam(":subpage_assigned", $subpage_assigned);

            if ($qry->execute()){
                $data = $qry->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $data = null;
            }
            return $data;
        }
    }

?>