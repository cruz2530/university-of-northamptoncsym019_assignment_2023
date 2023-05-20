<?php
    session_start();
    unset($_SESSION["access"]);
    session_destroy();
    exit(header("Location: ../views/login.php"));
?>