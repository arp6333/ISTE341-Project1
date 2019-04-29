<?php
    // Ellie Parobek addsession.php: Add a session.

    // Check if logged in as admin or manager.
    include('header.php');
    if($_SESSION['userType'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Save changes to form.
    if(isset($_GET['updated'])){
        if($res = $mysql->insertSession($_POST['name'], $_POST['dropdown'], $_POST['datestart'], $_POST['dateend'], $_POST['capacity'])){
            echo "<script type='text/javascript'>alert('Session Successfully added!')</script>";
        }
        else{
            echo "<script type='text/javascript'>alert('Session Unsuccessfully Added.')</script>"; 
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
        <title>Add Session</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    </head>
    <body>
        <!-- Header -->
        <nav class="blue-grey lighten-2 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Add Session</a>
                <!-- Navigation -->
                <ul class="right">
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="events.php">Events</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userType'] == "admin"){
                            echo "<li><a class='blue-grey darken-1 waves-effect waves-light btn' href='admin.php'>Admin Controls</a></li>";
                        }
                    ?>
                    <li><a class="red darken-1 waves-effect waves-light btn" href="addsession.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Add a session -->
        <div class="section container m9 s9">
            <?php
                $result = $mysql->getManagerEvents();
                echo '<form id="myForm" action="addsession.php?updated=true" method="post">
                    Name:<input data-length="50" type="text" name="name" required="required" value=""><br/>
                    Date start:<input type="datetime-local" name="datestart" required="required" value=""><br/>
                    Date end:<input type="datetime-local" name="dateend" required="required" value=""><br/>
                    Number allowed:<input type="number" name="capacity" required="required" value=""><br/>
                    Event Associated:';
                $event = '<select name="dropdown">';
                foreach($result as $res){
                    $event .= "<option value='" . $res['idevent'] . "'>" . $res['name'] . "</option>";
                }
                $event .= '</select></div>';
                echo $event . '<br/><input class="btn blue-grey darken-2" type="submit" value="Add Session"></form>';  
            ?>
        </div>
        
        <!-- Materialize script -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
