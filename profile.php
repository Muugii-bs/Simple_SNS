<?php // Example 26-8: profile.php
  require_once 'header.php';

  if (!$loggedin) die();

  echo "<div id='content'><div class='content_section'><div class='main'><h2>Edit your Profile</h2>";

  $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

  if (isset($_POST['text']))
  {
    $text = sanitizeString($_POST['text']);
    $text = preg_replace('/\s\s+/', ' ', $text);

    $fact1 = sanitizeString($_POST['phi']);
    $fact2 = sanitizeString($_POST['money']);
    $fact3 = sanitizeString($_POST['love']);
    $fact4 = sanitizeString($_POST['person']);

    if ($result->num_rows)
         queryMysql("UPDATE profiles SET text='$text', fact1='$fact1', fact2='$fact2', fact3='$fact3', fact4='$fact4' WHERE user='$user'");
    
    else queryMysql("INSERT INTO profiles VALUES('$user', '$text', '$fact1', '$fact2', '$fact3', '$fact4')");
  }

  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $text = stripslashes($row['text']);
    }
    else $text = "";
  }

  $text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

  if (isset($_FILES['image']['name']))
  {
    $saveto = "photos/$user.jpg";
    //print_r($_FILES);
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    
    $typeok = TRUE;
    
    switch($_FILES['image']['type'])
    {
      case "image/gif":   $src = imagecreatefromgif($saveto); break;
      case "image/jpeg":  //$src = imagecreatefromjpeg($saveto); break;
      case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
      case "image/png":   $src = imagecreatefrompng($saveto); break;
      default:            $typeok = FALSE; break;
    }

    if ($typeok)
    {
      list($w, $h) = getimagesize($saveto);

      $max = 100;
      $tw  = $w;
      $th  = $h;

      if ($w > $h && $max < $w)
      {
        $th = $max / $w * $h;
        $tw = $max;
      }
      elseif ($h > $w && $max < $h)
      {
        $tw = $max / $h * $w;
        $th = $max;
      }
      elseif ($max < $w)
      {
        $tw = $th = $max;
      }

      $tmp = imagecreatetruecolor($tw, $th);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
      imageconvolution($tmp, array(array(-1, -1, -1),
        array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
      imagejpeg($tmp, $saveto);
      imagedestroy($tmp);
      imagedestroy($src);
    }
    else
      echo "<div class='error'>File not chosen or Invalid file type!</div>";
  }

  showProfile($user);

  echo <<<_END
    <form method='post' action='profile.php' enctype='multipart/form-data'>
    <h3>Your greetings in 100 characters:</h3>
    <textarea name='text' cols='50' rows='2' maxlength='100'>$text</textarea><br>
_END;
?>

    <h3>Profile picture:</h3>
    <input type='file' name='image' size='14'><br><br>
    <!-- </form></div><br></div> 
    <div class='content_section last_section'> 
    <form method='post' action='profile.php'>-->  
    <h3>Your natures:</h3>
    <div><ul>
      <li>
        <a href='http://www.fll.fcu.edu.tw/wSite/publicfile/Attachment/f1279678092949.pdf'>Life philosophy:</a>&nbsp;<br>
        Eastern <input type='radio' name='phi' value='eastern' checked='checked'>&nbsp;&nbsp;
        Western <input type='radio' name='phi' value='western'>&nbsp;&nbsp;
        Hybrid <input type='radio' name='phi' value='hybrid'>
      </li>

      <li>
        <a href='http://aarticles.net/money/1732-sekrety-bogatstva-ot-garvardskoj-biznes-shkoly-kto-vy-krot-kurica-obezyana-ili-kot.html'>Passion for wealth:</a>&nbsp;<br>
        Mole <input type='radio' name='money' value='mole' checked='checked'>&nbsp;&nbsp;
        Chicken <input type='radio' name='money' value='chicken'>&nbsp;&nbsp;
        Monkey <input type='radio' name='money' value='monkey'>&nbsp;&nbsp;
        Cat <input type='radio' name='money' value='cat'>
      </li>

      <li>
        <a href='http://webcenters.netscape.compuserve.com/love/package.jsp?name=fte/typeoflover/typeoflover'>Loving behaviour:</a>&nbsp;<br>
        Romantic <input type='radio' name='love' value='romantic' checked='checked'>&nbsp;&nbsp;
        Lister-maker <input type='radio' name='love' value='list-maker'>&nbsp;&nbsp;
        Obsessive <input type='radio' name='love' value='obsessive'>&nbsp;&nbsp;<br>
        Giver <input type='radio' name='love' value='giver'>&nbsp;&nbsp;
        Player <input type='radio' name='love' value='player'>&nbsp;&nbsp;
        Pal <input type='radio' name='love' value='pal'>    
      </li> 

      <li>
        <a href='http://www.16personalities.com/personality-types'>Personality:</a>&nbsp;<br>
        Analyst <input type='radio' name='person' value='analyst' checked='checked'>&nbsp;&nbsp;
        Diplomat <input type='radio' name='person' value='diplomat'>&nbsp;&nbsp;
        Sentinel <input type='radio' name='person' value='sentinel'>&nbsp;&nbsp;
        Explorer <input type='radio' name='person' value='explorer'>
      </li>
     </lu></div>
     <input type='submit' value='Save Profile'>
     </form>   
  </div> <!-- for last_section--> 
  </div> <!-- for content -->
  </div><!-- for wrapper -->
  </body>
</html>
