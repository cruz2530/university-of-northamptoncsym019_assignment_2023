<?php

    require($_SERVER['DOCUMENT_ROOT']."/task2/php/course/courses.php");

    // Process the form data
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $contentID = $_GET['id'];

        $course = new Course();

        $course->deleteContent($contentID);

        // Redirect back to the referring page
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;


    }

?>
