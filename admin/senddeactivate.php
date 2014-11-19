<?php
require_once('access.php');
require_once('header.php');

  $query="SELECT * FROM ".TABLE_PREFIX."posts where isAvailable=1 and numcontacts>3";
  $result =	$ocdb->query($query);
  while ($row = mysql_fetch_array($result))
  {
    $title=$row['title'];
    $email=$row['email'];
    $insertDate=$row['insertDate'];
    $id=$row['idPost'];
    $pwid=$row['password'];

    $message = "<html>
  	  <body>
      <center>
      <img src='http://pennbookbazaar.com/pbb4.png' height=100px  alt='Penn Book Bazaar'><br /><br />
      <font size=4>You have been contacted about \"$title\" more than three times through Penn Book Bazaar. If you have sold this textbook or if it is no longer available please <a href='http://pennbookbazaar.com/manage/?post=$id&pwid=$pwid&action=deactivate'>deactivate</a> your listing from our directory.</font><br /><br />
        <a href='http://pennbookbazaar.com/manage/?post=$id&pwid=$pwid&action=deactivate'>Deactivate this listing</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href='http://pennbookbazaar.com/my-account/'>View Your Posts</a><br />
      </center>
 	  </body>
	</html>";

    sendEmail("$email","Are you still selling $title?","$message");
  }
?>