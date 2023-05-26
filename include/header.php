<?php
    if(!isset($_SESSION)) session_start();
    if(!isset($_SESSION['access']))
        exit(header("location:  ../views/login.php"));

?>
<header>
    <h3>CSYM019 - UNIVERSITY COURSES</h3>
</header>