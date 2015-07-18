<?php
  $dbhost  = 'localhost';
  $dbname  = 'sns1';
  $dbuser  = 'muugii';
  $dbpass  = 'tuulai';
  $appname = "Character book";

  $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if($connection -> connect_error) die($connection->connect_error);

  function createTable($name, $query){
  	queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
  	echo "Table '$name' created or already exists.<br>";
  } 

  function queryMysql($query){
  	global $connection;
  	$result = $connection->query($query);
  	if(!$result) die($connection->error);
  	return $result;
  }

  function destroySession(){
  	$_SESSION = array();

  	if(session_id() != "" || isset($_COOKIE[session_name()]))
  		setcookie(session_name(), '', time()-2592000, '/');

  	session_destroy();
  }

  function sanitizeString($var){
  	global $connection;
  	$var = strip_tags($var);
  	$var = htmlentities($var);
  	$var = stripslashes($var);
  	return $connection->real_escape_string($var);
  }

  function showProfile($user){
  	if(file_exists("photos/$user.jpg"))
  		echo "<img src='photos/$user.jpg' class='image_wrapper image_fl'/>";

  	$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

  	if($result->num_rows){
  		$row = $result->fetch_array(MYSQLI_ASSOC);
      echo strtoupper($user)." thinks in a ".$row['fact1']." way, lives like a ".$row['fact2'].
           ", is the lover of ".$row['fact3']." and is kind of a ".$row['fact4'].".<br><br>Message from ".strtoupper($user).":  ".stripslashes($row['text']) . "<br style='clear:left;'>";
  	}
  }
?>
