<?php 

    require_once('db.php');

    class User {

        public $conn;

        public function __construct()
        {
            $this->conn = Database::getConnection();
        }

        public function getAUser($username){
            $stmt = $this->conn->prepare("SELECT * from users where username = :username");
            $stmt->bindParam(":username",$username);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_OBJ);
        }


        public function __destruct()
        {
            Database::releaseConnection($this->conn);
        }

    }

    
?>