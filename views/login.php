<!DOCTYPE html>
<html lang="en" >
<head>
    <?php 
        require($_SERVER['DOCUMENT_ROOT']."/task2/include/head.php"); 
        require($_SERVER['DOCUMENT_ROOT']."/task2/php/auth/login.php")
    ?>
</head>
<body id="login-body">

    <div id="bg"></div>

    <form method="POST" action="">
        
        <div class="form-field">
            <input type="text" name="username" placeholder="Username" required/>
        </div>
        
        <div class="form-field">
            <input type="password" name="password" placeholder="Password" required/>                         
        </div>
        <?php if(isset($_SESSION['loginF'])) echo '
            <div class="form-field ">
                <h4 style="color:red;"> Invalid credentials </h4>
            </div>';
        ?>
        <div class="form-field">
            <button class="btn" type="submit">Log in</button>
        </div>
       
    </form>

</body>
</html>
