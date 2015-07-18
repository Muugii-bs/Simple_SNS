<?php // Example 26-9: members.php
  require_once 'header.php';

  if (!$loggedin) die();

  if (isset($_GET['view']))
  {
    $view = sanitizeString($_GET['view']);
    
    if ($view == $user) $name = strtoupper($user)."'s";
    else                $name = "$view's";
    
    echo "<div id='content'><div class='content_section'><h2>$name Profile</h2>";
    showProfile($view);
    echo "<a class='button' href='messages.php?view=$view'>" .
         "View $name messages</a><br><br>";
    die("</div></div></div></div></body></html>");
  }

  if (isset($_GET['add']))
  {
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");
    if (!$result->num_rows)
      queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
  }
  elseif (isset($_GET['remove']))
  {
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
  }

  $result = queryMysql("SELECT user FROM members ORDER BY user");
  $num    = $result->num_rows;

  echo "<div id='content'><div class='content_section'><h2>Other Members</h2><ul>";

  for ($j = 0 ; $j < $num ; ++$j)
  {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['user'] == $user) continue;
    
    echo "<li><a href='members.php?view=" .
      $row['user'] . "'>" . $row['user'] . "</a>";
    $follow = "follow";

    $result1 = queryMysql("SELECT * FROM friends WHERE
      user='" . $row['user'] . "' AND friend='$user'");
    $t1      = $result1->num_rows;

    $result1 = queryMysql("SELECT * FROM friends WHERE
      user='$user' AND friend='" . $row['user'] . "'");
    $t2      = $result1->num_rows;

    if (($t1 + $t2) > 1) echo " &harr; is a mutual friend";
    elseif ($t1)         echo " &larr; you are following";
    elseif ($t2)       { echo " &rarr; is following you";
      $follow = "recip"; }
    
    if (!$t1) echo " [<a href='members.php?add="   .$row['user'] . "'>$follow</a>]";
    else      echo " [<a href='members.php?remove=".$row['user'] . "'>drop</a>]";
  }

  echo "</ul><h3>The members whose natures are same as yours:</h3>";

  $fact1 = 'fact1';     
  $fact2 = 'fact2';
  $fact3 = 'fact3';
  $fact4 = 'fact4';

  $my_phi = fetch_fact($fact1, $user);
  $my_pass = fetch_fact($fact2, $user);     
  $my_love = fetch_fact($fact3, $user);
  $my_person = fetch_fact($fact4, $user);

  $s_phi = array();
  $s_pass = array();
  $s_love = array();
  $s_person = array();

  $match_12 = array();
  $match_13 = array();
  $match_14 = array();
  $match_23 = array();
  $match_24 = array();
  $match_34 = array();
  $match_123 = array();
  $match_124 = array();
  $match_134 = array();
  $match_234 = array();
  $match_1234 = array();

  $m_res = queryMysql("SELECT * FROM profiles WHERE fact1='$my_phi' AND user<>'$user' ORDER BY user");
  $m_num = $m_res->num_rows;
  for($j = 0; $j < $m_num; ++$j){
    $m_row = $m_res->fetch_array(MYSQLI_ASSOC);
    $s_phi[$j] = $m_row['user'];
  }

  $m_res = queryMysql("SELECT * FROM profiles WHERE fact2='$my_pass' AND user<>'$user' ORDER BY user");
  $m_num = $m_res->num_rows;
  for($j = 0; $j < $m_num; ++$j){
    $m_row = $m_res->fetch_array(MYSQLI_ASSOC);
    $s_pass[$j] = $m_row['user'];
  }  

  $m_res = queryMysql("SELECT * FROM profiles WHERE fact3='$my_love' AND user<>'$user' ORDER BY user");
  $m_num = $m_res->num_rows;
  for($j = 0; $j < $m_num; ++$j){
    $m_row = $m_res->fetch_array(MYSQLI_ASSOC);
    $s_love[$j] = $m_row['user'];
  }  

  $m_res = queryMysql("SELECT * FROM profiles WHERE fact4='$my_person' AND user<>'$user' ORDER BY user");
  $m_num = $m_res->num_rows;
  for($j = 0; $j < $m_num; ++$j){
    $m_row = $m_res->fetch_array(MYSQLI_ASSOC);
    $s_person[$j] = $m_row['user'];
  }  

  $match_12 = array_intersect($s_phi, $s_pass);
  $match_13 = array_intersect($s_phi, $s_love);
  $match_14 = array_intersect($s_phi, $s_person);
  $match_23 = array_intersect($s_pass, $s_love);
  $match_24 = array_intersect($s_pass, $s_person);
  $match_34 = array_intersect($s_love, $s_person);
  $match_123 = array_intersect($s_phi, $match_23);
  $match_124 = array_intersect($s_phi, $match_24);
  $match_134 = array_intersect($s_phi, $match_34);
  $match_234 = array_intersect($s_pass, $match_34);
  $match_1234 = array_intersect($match_12, $match_34);

  /*echo "<form action='members.php' method='post'>" . 
       "<input type='radio' name='match' value='1' checked='checked'>Life philosophy &nbsp" . 
       "<input type='radio' name='match' value='2'>Passion for wealth &nbsp" .
       "<input type='radio' name='match' value='3'>Loving behaviour &nbsp" .
       "<input type='radio' name='match' value='4'>Personality &nbsp" .
       "<input type='radio' name='match' value='12'>Life philosophy and passion for wealth &nbsp" .
       "<input type='radio' name='match' value='13'>Life philosophy and loving behaviour &nbsp" .
       "<input type='radio' name='match' value='14'>Life philosophy and personality &nbsp" .
       "<input type='radio' name='match' value='23'>Passion for wealth and loving behaviour &nbsp" .
       "<input type='radio' name='match' value='24'>Passion for wealth and personality &nbsp" .
       "<input type='radio' name='match' value='34'>Loving behaviour and personality &nbsp" .
       "<input type='radio' name='match' value='123'>Life philosophy, passion for wealth and loving behaviour &nbsp" .
       "<input type='radio' name='match' value='124'>Life philosophy, passion for wealth and personality &nbsp" .
       "<input type='radio' name='match' value='134'>Life philosophy, loving behaviour and personality &nbsp" .
       "<input type='radio' name='match' value='234'>Passion for wealth, loving behaviour and personality &nbsp" .
       "<input type='radio' name='match' value='1234'>All natures &nbsp" .
       "<br><input type='submit' value='Filter'></form>";*/


  /*echo "All natures:";
  print_users($match_1234);
  echo "Life philosophy, passion for wealth and loving behaviour:";
  print_users($match_123);
  echo "Life philosophy, passion for wealth and personality:";
  print_users($match_124);
  echo "Life philosophy, loving behaviour and personality:";
  print_users($match_134);
  echo "Passion for wealth, loving behaviour and personality:";
  print_users($match_234);
  echo "Life philosophy and passion for wealth:";
  print_users($match_12);
  echo "Life philosophy and loving behaviour:";
  print_users($match_13);
  echo "Life philosophy and personality:";
  print_users($match_14);
  echo "Passion for wealth and loving behaviour:";
  print_users($match_23);
  echo "Passion for wealth and personality:";  
  print_users($match_24);
  echo "Loving behaviour and personality:";  
  print_users($match_34);
  echo "Life philosophy:";
  print_users($s_phi);
  echo "Passion for wealth:";
  print_users($s_pass);
  echo "Loving behaviour:";
  print_users($s_love);
  echo "Personality:";
  print_users($s_person);*/

  echo "<form action='members.php' method='post'>" . 
       "<select name='match'>" . 
       "<option value='1'>Life philosophy &nbsp </option>" . 
       "<option value='2'>Passion for wealth &nbsp </option>" .
       "<option value='3'>Loving behaviour &nbsp </option>" .
       "<option value='4'>Personality &nbsp </option>" .
       "<option value='12'>Life philosophy and passion for wealth &nbsp </option>" .
       "<option value='13'>Life philosophy and loving behaviour &nbsp </option>" .
       "<option value='14'>Life philosophy and personality &nbsp </option>" .
       "<option value='23'>Passion for wealth and loving behaviour &nbsp </option>" .
       "<option value='24'>Passion for wealth and personality &nbsp </option>" .
       "<option value='34'>Loving behaviour and personality &nbsp </option>" .
       "<option value='123'>Life philosophy, passion for wealth and loving behaviour &nbsp </option>" .
       "<option value='124'>Life philosophy, passion for wealth and personality &nbsp </option>" .
       "<option value='134'>Life philosophy, loving behaviour and personality &nbsp </option>" .
       "<option value='234'>Passion for wealth, loving behaviour and personality &nbsp </option>" .
       "<option value='1234'>All natures &nbsp </option>" .
       "</select>" . 
       "&nbsp&nbsp<input type='submit' value='Filter'></form>";

  if(isset($_POST['match'])){
    switch ($_POST['match']) {
      case '1':
        print_users($s_phi);
        break;
      case '2':
        print_users($s_pass);
        break;
        case '3':
        print_users($s_love);
        break;
        case '4':
        print_users($s_person);
        break;
        case '12':
        print_users($match_12);
        break;
        case '13':
        print_users($match_13);
        break;
        case '14':
        print_users($match_14);
        break;
        case '23':
        print_users($match_23);
        break;
        case '24':
        print_users($match_24);
        break;
        case '34':
        print_users($match_34);
        break;
        case '123':
        print_users($match_123);
        break;
        case '124':
        print_users($match_124);
        break;
        case '134':
        print_users($match_134);
        break;
        case '234':
        print_users($match_234);
        break;
        case '1234':
        print_users($match_1234);
        break;

      default:
        break;
    }
  }

  function compare($fact, $value){
    $query_str = "SELECT * FROM profiles WHERE $fact='$value'";
    echo " compare!";
    return queryMysql($query_str);
  }

  function print_users($users){
    $num_users = sizeof($users);
    echo "<br>";
    for($j = 0; $j < $num_users; ++$j){
      echo "<a href='members.php?view=" . $users[$j] . "'>" . $users[$j] . "</a>&nbsp";
    }
    echo "<br>";
  }

  function fetch_fact($fact, $usr){
    $res = queryMysql("SELECT $fact FROM profiles WHERE user='$usr'");
    $m_row = $res->fetch_array(MYSQLI_ASSOC);
    return $m_row[$fact];
  }

  function same_nature($res, $fact, $value, $usr){
    $m_res = queryMysql("SELECT * FROM profiles WHERE $fact='$value' AND user<>'$usr' ORDER BY user");
    $m_num = $m_res->num_rows;
    for($j = 0; $j < $m_num; ++$j){
      $m_row = $m_res->fetch_array(MYSQLI_ASSOC);
      $res[$j] = $m_row['user'];
    }
  }
?>

    </div>
  </div><!--- for content -->
  </div><!--- for content_section -->
  </div><!--- for wrapper -->
  </body>
</html>
