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
            $sql = "INSERT INTO accounts (email, password, pageID, role_id, subpage_assigned, status)
                    VALUES (:email, :password, 3, :role_id, :subpage_assigned, 1);";
            $qry = $this->db->connect()->prepare($sql);
            $hashpass = password_hash($this->password, PASSWORD_DEFAULT);
            $qry->bindParam(":email", $this->email);
            $qry->bindParam(":password", $hashpass);
            $qry->bindParam(":role_id", $this->role);
            $qry->bindParam(":subpage_assigned", $this->subpage_assigned);

            if ($qry->execute());
        }
        function addAdminAccount(){
            $sql = "INSERT INTO accounts (email, password,role_id)
                    VALUES (:email, :password, :role_id);";
            $qry = $this->db->connect()->prepare($sql);
            $hashpass = password_hash($this->password, PASSWORD_DEFAULT);
            $qry->bindParam(":email", $this->email);
            $qry->bindParam(":password", $hashpass);
            $qry->bindParam(":role_id", $this->role);

            if ($qry->execute());
        }

        function updateProfilePicture($userId, $profileImgPath) {
            $sql = "UPDATE accounts SET profileImg = :profileImg WHERE id = :id";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":profileImg", $profileImgPath);
            $qry->bindParam(":id", $userId);
            return $qry->execute();
        }
        
        function updatePersonalInfo($userId, $firstName, $lastName) {
            $sql = "UPDATE accounts SET firstName = :firstName, lastName = :lastName WHERE id = :id";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":firstName", $firstName);
            $qry->bindParam(":lastName", $lastName);
            $qry->bindParam(":id", $userId);
            return $qry->execute();
        }
        
        function updateEmail($userId, $email) {
            $sql = "UPDATE accounts SET email = :email WHERE id = :id";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":email", $email);
            $qry->bindParam(":id", $userId);
            return $qry->execute();
        }
        
        function updatePassword($userId, $newPassword) {
            $sql = "UPDATE accounts SET password = :password WHERE id = :id";
            $qry = $this->db->connect()->prepare($sql);
            $hashpass = password_hash($newPassword, PASSWORD_DEFAULT);
            $qry->bindParam(":password", $hashpass);
            $qry->bindParam(":id", $userId);
            return $qry->execute();
        }
        
        function verifyPassword($userId, $password) {
            $sql = "SELECT password FROM accounts WHERE id = :id";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":id", $userId);
            $qry->execute();
            
            if ($qry->rowCount() > 0) {
                $result = $qry->fetch(PDO::FETCH_ASSOC);
                return password_verify($password, $result['password']);
            }
            return false;
        }
        
        function isEmailTaken($email, $currentUserId) {
            $sql = "SELECT id FROM accounts WHERE email = :email AND id != :currentUserId";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":email", $email);
            $qry->bindParam(":currentUserId", $currentUserId);
            $qry->execute();
            
            return $qry->rowCount() > 0;
        }
        function getUserData($userId) {
            $sql = "SELECT id, email, firstName, lastName, profileImg, role_id, subpage_assigned 
                    FROM accounts 
                    WHERE id = :id";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":id", $userId);
            $qry->execute();
            
            if ($qry->rowCount() > 0) {
                return $qry->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
        
}
?>