<?php
    // Ellie Parobek registrations.php: View and edit registrations user has.

    include('header.php');
    $mysql = new MySQLDatabase();
    
    // Delete an attending event function
    if(isset($_GET['deleteEvent'])){
        $mysql->delete("attendee_event", $_GET['id'], "event");
    }
    // Delete an attending session function
    if(isset($_GET['deleteSession'])){
        $mysql->delete("attendee_session", $_GET['id'], "session");
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
        <meta charset="utf-8">
        <title>Registrations</title>
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    </head>
    <body>
        <!-- Header -->
        <nav class="blue-grey lighten-2 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Your Registrations</a>
                <!-- Navigation -->
                <ul class="right">
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="events.php">Events</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userType'] == "admin"){
                            echo "<li><a class='blue-grey darken-1 waves-effect waves-light btn' href='admin.php'>Admin Controls</a></li>";
                        }
                    ?>
                    <li><a class="red darken-1 waves-effect waves-light btn" href="registrations.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Display user registrations. -->
        <div class="section container s11 m11">
            <?php
                // Get registered events from database.
                $result = $mysql->getRegisteredEvents($_SESSION["username"]);
                echo "<h4 class='center'>Events Attending</h4>";
                foreach($result as $v){
                    echo "<p>";
                    foreach($v as $v2){
                        echo $v2 . " - ";
                    }
                    echo '</p><br/><a class="btn red darken-2" href="registrations.php?deleteEvent=true&id=' . $v["idevent"] . '">Stop 
                        Attending</a><br/>';
                } 
                // Get registered sessions from database.
                $result2 = $mysql->getRegisteredSessions($_SESSION["username"]);
                echo "<h4 class='center'>Sessions Attending</h4>";
                foreach($result2 as $v2){
                    echo "<p>";
                    foreach($v2 as $v3){
                        echo $v3 . " - ";
                    }
                    echo '</p><br/><a class="btn red darken-2" href="registrations.php?deleteSession=true&id=' . $v2["idsession"] . '">Stop 
                        Attending</a><br/>';
                } 
            ?>
        </div>
        
        <!-- Materialize script -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
