<?php
	require_once 'header.php'

	echo <<<_END
		<script>
			function checkUser(user){
				if(user.value == ''){
					O('info').innerHTML = ''
					return
				}

				params = "user=" + user.value
				request = new ajaxRequest()
				request.open("POST", "checkuser.php", true)
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
				request.setRequestHeader("Content-length", params.length)
				request.setRequestHeader("Connection", "close")

				request.onreadystatechange = function(){
					if(this.readyState == 4)
						if(this.status == 200)
							if(this.responsText != null)
								O('info').innerHTML = this.responsText
				}
				request.send(params)

			}

			fucntion ajaxRequest(){
				try{
					var request = new XMLHttpRequest()
				}
				catch(e1){
					try{
						request = new ActiveXObject("Msxml2.XMLHTTP")
					}
					catch(e2){
						try{
							request = new ActiveXObject("Microsoft.XMLHTTP")
						}
						catch(e3){
							request = false
						}
					}
				}
				return request
			}
			</script>
			<div class='main'><h3>Please sign up here</h3>
	_END;
	
	$error = $user = $pass = "";
	if(isset($_SESSION['user'])) destroySession();

	if(isset($_POST['user'])){
		$user = sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);

		if($user == "" || $pass == "")
			$error = "Unfilled fields exist"<br><br>";
		else{
			$result = queryMysql("SELECT * FROM members WHERE user='$user'");

			if($result->num_rows)
				$error = "That username already exists<br><br>";
			else{
				queryMysql("INSERT INTO members VALUES('$user', '$pass')");
				die("<h4>Account created</h4>Now you are ready to login.<br><br>");
			}
		}
	}

	echo <<<_END
		<form method='post' action='signup.php'>$error
		<span class='fieldname'>Username</span>
		<input type='text' maxlength='16' name='user' value='$user' onBlur='checkUser(this)'>
		<span id='info'></span><br>
		<span class='fieldname'>Password</span>
		<input type='text' maxlength='16' name='pass' value='$pass'><br>
	_END;
	
?>

	<span class='fieldname'>&nbsp;</span>
	<input type='submit' value='Sign up'>
	</form></div><br>
</body>
</html>
















