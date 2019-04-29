<?php
    // Ellie Parobek editevent.php: Edit an event.

    // Check if logged in as admin or manager.
    include('header.php');
    if($_SESSION['userType'] == "attendee" || !isset($_GET['id'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    // Check this event is associated with the user logged in (if not admin).
    if($_SESSION['userType'] == "manager"){    
        $result = $mysql->getManagerEvents();
        $allowed = false;
        foreach($result as $response){
            if($response['idevent'] == $_GET['id']){
                $allowed = true;
            }
        }
        if($allowed == false){
            echo '<script>window.location.replace("events.php");</script>';
        }
    }

    $mysql = new MySQLDatabase();

    // Delete an event function.
    if(isset($_GET['deleteEvent'])){
        $mysql->adminDelete("event", $_GET['id'], "idevent");
        $mysql->adminDelete("session", $_GET['id'], "event");
        echo '<script>window.location.replace("events.php");</script>';
    }

    // Save changes to form.
    if(isset($_GET['updated'])){
        $mysql->editEvent($_POST['name'], $_POST['datestart'], $_POST['dateend'], $_POST['capacity'], $_POST['dropdown'], $_GET['id']);
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
        <title>Edit Event</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    </head>
    <body>
        <!-- Header -->
        <nav class="blue-grey lighten-2 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Edit Event</a>
                <!-- Navigation -->
                <ul class="right">
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="events.php">Events</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userType'] == "admin"){
                            echo "<li><a class='blue-grey darken-1 waves-effect waves-light btn' href='admin.php'>Admin Controls</a></li>";
                        }
                    ?>
                    <li><a class="red darken-1 waves-effect waves-light btn" href="editevent.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Edit an event -->
        <div class="section container m9 s9">
            <?php
                $response = $mysql->getEvent($_GET['id']);
                $result = $mysql->getVenues();
                echo '<h6 class="center">Event ' . $response["idevent"] . '</h6><form id="myForm" action="editevent.php?updated=true&id=' . $response["idevent"] . '" method="post">
                    Name:<input type="text" name="name" value="' . $response["name"] . '"><br/>
                    Date Start:<input type="text" name="datestart" value="' . $response["datestart"] . '"><br/>
                    Date End:<input type="text" name="dateend" value="' . $response["dateend"] . '"><br/>
                    Number Allowed:<input type="number" name="capacity" value="' . $response["numberallowed"] . '"><br/>
                    Venue:';
                $venue = '<select name="dropdown">';
                foreach($result as $res){
                    $venue .= "<option value='" . $res['idvenue'] . "'>" . $res['name'] . "</option>";
                }
                $venue .= '</select></div>';
                echo $venue . '<br/><input class="btn center blue-grey darken-2" type="submit" value="Save Changes">&nbsp;';
                echo '<a class="btn center red darken-2" href="editevent.php?deleteEvent=true&id=' . $response["idevent"] . '">Delete 
                    Event</a></form>';     
            ?>
        </div>
        
        <!-- Materialize script -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
