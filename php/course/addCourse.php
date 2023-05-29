<?php

    require($_SERVER['DOCUMENT_ROOT']."/task2/php/course/courses.php");

    // Process the form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents("php://input"), true);

        if ($data === null) {
            // Error parsing JSON data
            $response = array('error' => 'Invalid  data');
            echo json_encode($response);
            exit();
        } else {
            $course = new Course();

            $response = $course->addCourse($data);

            echo json_encode($response);

        }
    }

?>
