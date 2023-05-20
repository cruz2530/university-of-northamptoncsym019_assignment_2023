<?php

    require_once('db.php');

    class Course {

        public $conn;

        public function __construct()
        {
            $this->conn = Database::getConnection();
        }


        public function index(){
            $query = "
                SELECT c.title, c.overview, GROUP_CONCAT(h.highlight SEPARATOR ', ') AS highlights, GROUP_CONCAT(cc.course_details SEPARATOR ', ') AS contents
                FROM Course AS c
                LEFT JOIN Highlight AS h ON c.id = h.course_id
                LEFT JOIN CourseContent AS cc ON c.id = cc.course_id
                GROUP BY c.id
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }


        public function __destruct()
        {
            $this->conn = Database::releaseConnection($this->conn);
        }


    }

?>