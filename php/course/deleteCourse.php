<?php

    require($_SERVER['DOCUMENT_ROOT']."/task2/php/course/courses.php");

    // Process the form data
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {


        $courseId = $_GET['course_id'];

        $course = new Course();

        $response = $course->deleteCourse($courseId);

        echo json_encode($response);
    
    }

?>
