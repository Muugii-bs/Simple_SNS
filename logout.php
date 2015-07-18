<?php // Example 26-12: logout.php
  require_once 'header.php';
  echo "<div id='content'><div class='content_section'>";

  if (isset($_SESSION['user']))
  {
    destroySession();
    echo "<h3>You have been logged out. Please " .
         "<a href='index.php'>click here</a> to refresh the screen.</h3>";
  }
  else echo "<br><h3>You cannot log out because you are not logged in.</h3>";
?>
  </div>
  </div>
  </div>
  </div><!-- for wrapper -->
  </body>
</html>
