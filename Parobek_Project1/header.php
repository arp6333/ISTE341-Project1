<?php
    include('MySQLDatabase.php');
    session_start();
    if(!isset($_SESSION['userType'])){
        echo '<script>window.location.replace("login.php");</script>';
    }
?>


