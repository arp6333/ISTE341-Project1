<?php
    // Ellie Parobek login.php: Log in a user.

    // Login and start session.
    include('MySQLDatabase.php');
    // Check if logged in.
    if(isset($_SESSION['userType'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    if(session_status() == PHP_SESSION_ACTIVE){
        session_destroy();
    }
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    </head>
    <body>
        <!-- Header -->
        <nav class="blue-grey lighten-2 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Login</a>
            </div>
        </nav>
        
        <!-- Form for entering user name and password. -->
        <div class="section container m9 s9">
            <form action="" method="post">
                <div>
                    <label for="username"><b>Username:</b></label>
                    <input type="text" placeholder="Enter Username" name="username">
                </div>

                <div>
                    <label for="password"><b>Password:</b></label>
                    <input type="password" placeholder="Enter Password" name="password">
                </div>
                <div>
                    <input class="btn" type="submit" value="Log In">
                </div>
            </form>
            <br/><a class="btn" href="register.php">Register</a>
        </div>

        <?php
            // Check if form is filled out.
            if(isset($_POST['username'])&& isset($_POST['password'])){
                $mysql = new MySQLDatabase();
                $res = $mysql->login($_POST['username'], $_POST['password']);
            }
        ?>
        
        <!-- Materialize script -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
