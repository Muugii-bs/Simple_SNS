<?php
	require_once 'header.php';

	//echo "<div><span class='main'>Welcome to $appname.";
	echo "<div id='content'><div class='content_section'>";
	if($loggedin)
		echo "<h2>Hello" . strtoupper($user) . "</h2>";
	else
		echo "<h2>Welcome to Character book!</h2>";
?>
	</div>
	</div>
</div>
</body>
</html>
