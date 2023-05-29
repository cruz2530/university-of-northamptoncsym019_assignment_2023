<?php
    require($_SERVER['DOCUMENT_ROOT']."/task2/php/course/courses.php");

    if( $_SERVER['REQUEST_METHOD'] === 'POST'){

        $data = json_decode(file_get_contents("php://input"),true);

        $course = new Course();

        $response = $course->updateCourse($data);

        echo json_encode($response);
    }


?>