<?php
    if(!isset($_SESSION)) 
        session_start();

    require_once('users.php');

    $users = new User();
    
    if($_SERVER['REQUEST_METHOD'] == "GET") 
        unset($_SESSION['loginF']);


    if($_SERVER['REQUEST_METHOD'] == "POST" ){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $users->getAUser($username);

        if($user && $user->username == $username and $user->password == $password){
            $_SESSION['access'] = True;
            exit(header("location: ../"));
        }
        $_SESSION['loginF'] = True;
    }
