<?php
	//require_once 'header.php';
session_start();

	echo "<!DOCTYPE html>\n<html><head>";

	require_once 'utilities.php';

	$userstr = 'Welcome to Character book';

	if(isset($_SESSION['user'])){
		$user = $_SESSION['user'];
		$loggedin = TRUE;
		$userstr = " ($user)";
	}
	else $loggedin = FALSE;

	echo "<title>$userstr</title>" . 
		 "<link rel='stylesheet' href='style.css' type='text/css' />" .
		 "</head><body><div id='tooplate_wrapper'>" . 
		 "<div id='header'><h1><img src='logo1.png'/></h1></div>" .
		 "<script src='scripts.js'></script>";

	if($loggedin){
		echo "<div><ul class='menu'>" . 
			 "<li><a href='members.php?view=$user' class='menu_01'>Home</a></li>" .
			 "<li><a href='members.php' class='menu_02'>Members</a></li>" .
			 "<li><a href='friends.php' class='menu_03'>Friends</a></li>" .
			 "<li><a href='messages.php' class='menu_04'>Messages</a></li>" .
			 "<li><a href='profile.php' class='menu_05'>Edit Profile</a></li>" .
			 "<li><a href='logout.php' class='menu_02'>Logout</a></li></ul></div>";
	} else
		echo ("<div><ul class='menu'>" .
			  "<li><a href='index.php' class='menu_01'>Home</a></li>" .
			  "<li><a href='signup.php' class='menu_02'>Sign up</a></li>" .
			  "<li><a href='login.php' class='menu_03'>Login</a></li></ul></div>");
	}

	echo "<div><span class='main'>Welcome to $appname.";

	if($loggedin)
		echo "Hello $user";
	else
		echo "Sign up or login here."
?>
	</span></div>
</body>
</html>