<?php
    // Check if logged in and logged in as who.
    include('header.php');
    if($_SESSION['userType'] == "student"){
        echo '<script>window.location.replace("student.php");</script>';
    }
    if($_SESSION['userType'] == "public"){
        echo '<script>window.location.replace("public.php");</script>';
    }
    
    // Logout button.
	if(isset($_POST['Logout'])){
		switch($_POST['Logout']){
			case 'Logout':
				echo"logout";
				logout();
				break;
		}
    }
    // Destroy session.
	function logout(){
		session_destroy();
		header("Location: http://serenity.ist.rit.edu/~iste330t02/Project/login.php"); /* Redirect browser */
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- DBCA Group 14 -->
    <meta charset="utf-8" />
    <title>Home</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="shortcut icon" href="favicon.ico">
</head>
<body>
    
    <script>
        // Validate the form
        function validateForm(){
            var name = document.forms["myForm"]["name"].value;
            var desc = document.forms["myForm"]["interest"].value;
            if((name == "") || (desc == "")){
                document.getElementById("text").innerHTML = "Please fill out all the fields.";
                return false;
            }
            else{
                return true;
            }
        }
    </script>
    
    <!-- Navigation bar -->
    <form action="" method="post">
		<div class="topnav">
			<a class="active" href="index.php">Home</a>
			<a href="profile.php">Profile</a>
			<a href="projects.php">Projects</a>
			<a href="requests.php">Requests</a>
			<input type="submit" class="lbutton" name="Logout" value="Logout" />
		</div>
	</form>

    <h2>Welcome back!</h2>
    <div class="newProject">    
	<!-- Add a project to the database -->
        <h3>Add New Project</h3>
        <hr>
		<?php
            // Send the data to the database.
			$sql = new MySQLDatabase();
			if (isset($_POST['name'])){
				$sql->insert($_POST['name'], $_POST['interest'], $_SESSION['username']);
                echo "<p>Project successfully submitted!</p>";
			}
		?>
        <form onsubmit="return validateForm();" id="myForm" name="myForm" action="" method="post">
            <p id="text"></p>
            Project Name: <input type="text" name="name"><br>
            Project Description: <input type="text" name="interest"><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
