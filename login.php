<?php // Example 26-7: login.php
  require_once 'header.php';
  echo "<div id='content'><div class='content_section'>" .
  	   "<div class='main'><h2>Please enter your details to log in.</h2>";
  $error = $user = $pass = "";

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    
    if ($user == "" || $pass == "")
        $error = "<span class='error'>Unfilled fields exist</span><br>";
    else
    {
      $result = queryMySQL("SELECT user,pass FROM members
        WHERE user='$user' AND pass='$pass'");

      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>Username/Password
                  invalid</span><br>";
      }
      else
      {
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;
        die("<h3>You are now logged in. Please <a href='members.php?view=$user'>" .
            "click here</a> to continue.<br><br></h3>");
      }
    }
  }

  echo <<<_END
    <form method='post' action='login.php'>$error
    <span class='fieldname'>Username</span><input type='text'
      maxlength='16' name='user' value='$user'><br>
    <span class='fieldname'>Password</span><input type='password'
      maxlength='16' name='pass' value='$pass'>
_END;
?>

    <br>
    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Login'>
    </form><br></div>
	</div></div><!-- for content, content_section -->
	</div><!-- for wrapper -->
  </body>
</html>
