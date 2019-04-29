<?php
    // Ellie Parobek adduser.php: Admin controls to add a user.

    // Check if logged in as admin.
    include('header.php');
    if($_SESSION['userType'] == "manager"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    if($_SESSION['userType'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Save changes to form.
    if(isset($_GET['updated'])){
        if($_POST['role'] == 'Admin'){
            $role = 1;
        }
        else if($_POST['role'] == 'Manager'){
            $role = 2;
        }
        else{
            $role = 3;
        }
        if($res = $mysql->insertUser($_POST['name'], $_POST['password'], $role)){
            echo "<script type='text/javascript'>alert('User Successfully added!')</script>";
        }
        else{
            echo "<script type='text/javascript'>alert('User Unsuccessfully Added.')</script>"; 
        }
    }

    // Logout button.
	if(isset($_GET['logout'])){
        logout();
    }
    // Destroy session.
	function logout(){
		session_destroy();
		header("Location: http://serenity.ist.rit.edu/~arp6333/341/project1/login.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin Add User</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    </head>
    <body>
        <!-- Header -->
        <nav class="blue-grey lighten-2 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Add User</a>
                <!-- Navigation -->
                <ul class="right">
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="events.php">Events</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="registrations.php">Registrations</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="admin.php">Admin Controls</a></li>
                    <li><a class="red darken-1 waves-effect waves-light btn" href="adduser.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Add a user -->
        <a class="blue-grey darken-1 waves-effect waves-light btn" href="admin.php">Go Back</a><br/>
        <div class="section container m9 s9">
            <?php
                echo '<form id="myForm" action="adduser.php?updated=true" method="post">
                    Name:<input data-length="100" type="text" name="name" required="required" value=""><br/>
                    Password:<input data-length="100" type="password" name="password" required="required" value=""><br/>
                    Role:<br/>';
                echo '<p><label><input value="Admin" name="role" type="radio"><span>Admin</span></label></p>
                      <p><label><input value="Manager" name="role" type="radio"><span>Manager</span></label></p>
                      <p><label><input value="Attendee" name="role" type="radio" checked><span>Attendee</span></label></p>';
                echo '<br/><input class="btn blue-grey darken-2" type="submit" value="Add User"></form>';  
            ?>
        </div>
        
        <!-- Materialize script -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
