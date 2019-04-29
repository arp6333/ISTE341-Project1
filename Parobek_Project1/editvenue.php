<?php
    // Ellie Parobek editvenue.php: Edit a venue.

    // Check if logged in as admin.
    include('header.php');
    if($_SESSION['userType'] == "manager"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    if($_SESSION['userType'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    // Check ID is sent.
    if(!isset($_GET['id'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Delete a venue function.
    if(isset($_GET['deleteVenue']) && !($_GET['id'] == 1)){
        $mysql->adminDelete("venue", $_GET['id'], "idvenue");
        $mysql->adminDelete("event", $_GET['id'], "venue");
        echo '<script>window.location.replace("admin.php");</script>';
    }

    // Save changes to form.
    if(isset($_GET['updated'])){
        $res = $mysql->editVenue($_POST['name'], $_POST['capacity'], $_POST['id']);
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
        <title>Edit Venue</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    </head>
    <body>
        <!-- Header -->
        <nav class="blue-grey lighten-2 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Edit Venue</a>
                <!-- Navigation -->
                <ul class="right">
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="events.php">Events</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="registrations.php">Registrations</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="admin.php">Admin Controls</a></li>
                    <li><a class="red darken-1 waves-effect waves-light btn" href="editvenue.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Edit a user -->
        <a class="blue-grey darken-1 waves-effect waves-light btn" href="admin.php">Go Back</a><br/>
        <div class="section container m9 s9">
            <?php
                $response = $mysql->getVenue($_GET['id']);
                echo '<form id="myForm" action="editvenue.php?updated=true&id=' . $response["idvenue"] . '" method="post">
                    ID:<input type="text" readonly name="id" value="' . $response["idvenue"] . '"><br/>
                    Name:<input type="text" name="name" value="' . $response["name"] . '"><br/>
                    Capacity:<input type="number" name="capacity" value="' . $response["capacity"] . '">';
                echo '<br/><input class="btn blue-grey darken-2" type="submit" value="Save Changes">&nbsp;';
                echo '<a class="btn red darken-2" href="editvenue.php?deleteVenue=true&id=' . $response["idvenue"] . '">Delete 
                    Venue</a></form>';     
            ?>
        </div>
        
        <!-- Materialize script -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
