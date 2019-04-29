<?php
    // Ellie Parobek admin.php: Admin controls.

    // Check if logged in as admin.
    include('header.php');
    if($_SESSION['userType'] == "manager"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    if($_SESSION['userType'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Delete a user function.
    if(isset($_GET['deleteUser']) && !($_GET['id'] == 1)){
        $mysql->adminDelete("attendee", $_GET['id'], "idattendee");
    }
    // Delete an attending event function.
    if(isset($_GET['deleteAttendingEvent'])){
        $mysql->delete("attendee_event", $_GET['id'], "event");
    }
    // Delete an attending session function.
    if(isset($_GET['deleteAttendingSession'])){
        $mysql->delete("attendee_session", $_GET['id'], "session");
    }
    // Delete a venue function.
    if(isset($_GET['deleteVenue']) && !($_GET['id'] == 1)){
        $mysql->adminDelete("venue", $_GET['id'], "idvenue");
        $mysql->adminDelete("event", $_GET['id'], "venue");
    }
    // Delete an event function.
    if(isset($_GET['deleteEvent'])){
        $mysql->adminDelete("event", $_GET['id'], "idevent");
        $mysql->adminDelete("session", $_GET['id'], "event");
    }
    // Delete a session function.
    if(isset($_GET['deleteSession'])){
        $mysql->adminDelete("session", $_GET['id'], "idsession");
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
        <title>Admin</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    </head>
    <body>
        <!-- Header -->
        <nav class="blue-grey lighten-2 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Admin Controls</a>
                <!-- Navigation -->
                <ul class="right">
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="events.php">Events</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="registrations.php">Registrations</a></li>
                    <li><a class="blue-grey darken-1 waves-effect waves-light btn" href="admin.php">Admin Controls</a></li>
                    <li><a class="red darken-1 waves-effect waves-light btn" href="admin.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>
        
        <!-- Edit user information. -->
        <h3 class="center">User Information</h3>
        <div class="section container m9 s9">
            <div class="center">
                <a class="btn blue-grey darken-2" href="adduser.php">Add New User</a><br/>
            </div>
            <?php
                $result = $mysql->getAttendees();
                foreach($result as $response){
                    echo '<form action="" method="post">
                        ID:<input readonly type="text" name="id" value="' . $response["idattendee"] . '"><br/>
                        Name:<input readonly type="text" name="name" value="' . $response["name"] . '"><br/>
                        Role:<br/>';
                    if($response["role"] == 1){
                        echo '<input readonly type="text" name="role" value="Admin"><br/>';
                    }
                    else if($response["role"] == 2){
                        echo '<input readonly type="text" name="role" value="Manager"><br/>';
                    }
                    else{
                        echo '<input readonly type="text" name="role" value="Attendee"><br/>';
                    }
                    // Do not allow deletion or editing if admin is the super admin (id = 1).
                    if($response["idattendee"] == 1){
                    }
                    else{
                        echo '<br/><a class="btn blue-grey darken-2" href="edituser.php?id=' . $response["idattendee"] . '">Edit 
                            User</a>&nbsp;';
                        echo '<a class="btn red darken-2" href="admin.php?deleteUser=true&id=' . $response["idattendee"] . 
                            '">Delete User</a>';
                    }
                    echo '</form><br/>';
                    // Get registered events for this user.
                    $result = $mysql->getRegisteredEvents($response["idattendee"]);
                    echo "<h6 class='center'>Events Attending</h6>";
                    foreach($result as $v){
                        echo "<p>";
                        foreach($v as $v2){
                            echo $v2 . " - ";
                        }
                        echo '</p><br/><a class="btn red darken-2" href="admin.php?deleteAttendingEvent=true&id=' . $v["idevent"] . '">Stop 
                            Attending</a><br/>';
                    } 
                    // Get registered sessions for this user.
                    $result2 = $mysql->getRegisteredSessions($response["idattendee"]);
                    echo "<h6 class='center'>Sessions Attending</h6>";
                    foreach($result2 as $v2){
                        echo "<p>";
                        foreach($v2 as $v3){
                            echo $v3 . " - ";
                        }
                        echo '</p><br/><a class="btn red darken-2"  href="admin.php?deleteAttendingSession=true&id=' . $v2["idsession"] . 
                            '">Stop Attending</a><br/>';
                    } 
                    echo '<hr/><br/>';
                }
            ?>
        </div>

        <!-- Edit venue information in the database. -->
        <h3 class="center">Venues</h3>
        <div class="section container m9 s9">
            <div class="center">
                <a class="btn blue-grey darken-2" href="addvenue.php">Add New Venue</a><br/>
            </div>
            <?php
                $result = $mysql->getVenues();
                foreach($result as $response){
                    echo '<form action="" method="post">
                        ID:<input readonly type="text" name="idVenue" value="' . $response["idvenue"] . '"><br/>
                        Name:<input readonly type="text" name="nameVenue" value="' . $response["name"] . '"><br/>
                        Capacity:<input readonly type="text" name="capacity" value="' . $response["capacity"] . '"><br/>';
                    echo '<br/><a class="btn blue-grey darken-2" href="editvenue.php?id=' . $response["idvenue"] . '">Edit Venue</a>&nbsp;';
                    echo '<a class="btn red darken-2" href="admin.php?deleteVenue=true&id=' . $response["idvenue"] . 
                        '">Delete Venue</a>';
                    echo '<br/><hr/><br/>';
                }
            ?>
        </div>

        <!-- Edit event information in the database. -->
        <h3 class="center">Events</h3>
        <div class="section container m9 s9">
            <div class="center">
                <a class="btn blue-grey darken-2" href="addevent.php">Add New Event</a><br/>
            </div>
            <?php
                $result = $mysql->getEvents();
                foreach($result as $response){
                    echo '<form action="" method="post">
                        ID:<input readonly type="text" name="idEvent" value="' . $response["idevent"] . '"><br/>
                        Name:<input readonly type="text" name="nameEvent" value="' . $response["name"] . '"><br/>
                        Date Start:<input readonly type="text" name="dateStart" value="' . $response["datestart"] . '"><br/>
                        Date End:<input readonly type="text" name="dateEnd" value="' . $response["dateend"] . '"><br/>
                        Number Allowed:<input readonly type="text" name="eventNumAllowed" value="' . $response["numberallowed"] . '"><br/>
                        Venue:<input readonly type="text" name="eventVenue" value="' . ($mysql->getVenue($response["venue"]))["name"] . '"><br/>';
                    echo '<br/><a class="btn blue-grey darken-2" href="editevent.php?id=' . $response["idevent"] . '">Edit 
                        Event</a>&nbsp;';
                    echo '<a class="btn red darken-2" href="admin.php?deleteEvent=true&id=' . $response["idevent"] . 
                        '">Delete Event</a>';
                    echo '<br/><hr/><br/>';
                }
            ?>
        </div>

        <!-- Edit session information in the database. -->
        <h3 class="center">Sessions</h3>
        <div class="section container m9 s9">
            <div class="center">
                <a class="btn blue-grey darken-2" href="addsession.php">Add New Session</a><br/>
            </div>
            <?php
                $result = $mysql->getSessions();
                foreach($result as $response){
                    echo '<form action="" method="post">
                        ID:<input readonly type="text" name="idSess" value="' . $response["idsession"] . '"><br/>
                        Name:<input readonly type="text" name="nameSess" value="' . $response["name"] . '"><br/>
                        Start date:<input readonly type="text" name="startDate" value="' . $response["startdate"] . '"><br/>
                        End date:<input readonly type="text" name="endDate" value="' . $response["enddate"] . '"><br/>
                        Number Allowed:<input readonly type="text" name="sessNumAllowed" value="' . $response["numberallowed"] . '"><br/>
                        Event:<input readonly type="text" name="sessEvent" value="' . ($mysql->getEvent($response["event"]))["name"] . '"><br/>';
                    echo '<br/><a class="btn blue-grey darken-2" href="editsession.php?id=' . $response["idsession"] . '">Edit 
                        Session</a>&nbsp;';
                    echo '<a class="btn red darken-2" href="admin.php?deleteSession=true&id=' . $response["idsession"] . 
                        '">Delete Session</a>';
                    echo '<br/><hr/><br/>';
                }
            ?>
        </div>
         
        <!-- Materialize script -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
