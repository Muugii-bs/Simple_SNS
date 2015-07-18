<?php
	require_once 'header.php';
	echo "<div class='main'><h3>Please enter the user name and password</h3>";
	$error = $user = $pass = "";

	if(isset($_POST['user'])){
		$user = sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);

		if($user == "" || $pass == "")
			$error = "Unfilled fields exist<br>";
		else{
			$result = queryMysql("SELECT user,pass FROM members WHERE user='$user' AND pass='$pass'");

			if($result->num_rows == 0){
				$error = "<span class='error'>Invalid username or password</span><br><br>";
			}
			else{
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				die("Login succeeded. <a href='members.php?view=$user'>".
					"continue</a>");
			}
		}
	}

	echo <<<_END
		<form method='post' action='login.php'>$error
		<span class='fieldname'>Username</span><input type='text' maxlength='16' name='user' value='$user'><br>
		<span class='fieldname'>Password</span><input type='passowrd' maxlength='16' name='pass' value='$pass'>
	_END
?>

	<br>
	<span class='fieldname'>&nbsp;</span>
	<input type='submit' value='Login'>
	</form><br></div>
</div><!-- for wrapper -->
</body>
</html>		 
