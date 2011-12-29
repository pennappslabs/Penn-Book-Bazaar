<?php
require_once('access.php');
require_once('header.php');
?>
<h2><?php echo _("Tools to Optimize");?></h2>
<?php
if (cG("action")=="db"){
    $result  = $ocdb->query('SHOW TABLE STATUS FROM '. DB_NAME); 
    while ($row = mysql_fetch_array($result)) $tables[]=$row[0];  
    $tables=implode(", ",$tables); //echo $tables;
    $ocdb->query('OPTIMIZE TABLE '.$tables);
    echo "<p>"._("All tables found in the database were optimized").": $tables</p>";
}
elseif (cG("action")=="cache") {
	deleteCache();
	echo "<p>"._("Cache was deleted")."</p>";
}
elseif (cG("action")=="notconfirmed") {
	echo "<p>"._("Delete Ads not confirmed in 3 days")."</p>";
	$query="SELECT idPost,insertDate FROM ".TABLE_PREFIX."posts where isConfirmed=0  and TIMESTAMPDIFF(DAY,insertDate,now())>=3";
	$result =	$ocdb->query($query);
	while ($row=mysql_fetch_assoc($result))//delete posible images and folders
	{	
		$idPost=$row['idPost'];
		$date=setDate($row['insertDate']);
		deletePostImages($idPost,$date);//delete images and folder for the ad
		echo _("Deleted")." $idPost<br />";
	}
	//delete from db
	$ocdb->delete(TABLE_PREFIX."posts","isConfirmed=0  and TIMESTAMPDIFF(DAY,insertDate,now())>=3");		
}
elseif (cG("action")=="senddeactivate") {
  echo "<p>"._("Deactivation emails were sent to all posts older than 1mo!")."</p>";
  
  $query="SELECT email,isConfirmed,title,insertDate,idPost,password,isAvailable FROM ".TABLE_PREFIX."posts where email='aditij@wharton.upenn.edu' and isConfirmed=1 and isAvailable=1";
  //and TIMESTAMPDIFF(DAY,insertDate,now())>=30";
  // email='aditij@wharton.upenn.edu' and
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
      <img src='http://pennua.org/textbook/pbb4.png' height=100px  alt='Penn Book Bazaar'><br /><br />
      <font size=4>You posted \"$title\" more than a month ago on Penn Book Bazaar. If you have sold this textbook or if it is no longer available please <a href='http://pennua.org/textbook/manage/?post=$id&pwid=$pwid&action=deactivate'>deactivate</a> your listing from our directory.</font><br /><br />
        <a href='http://pennua.org/textbook/manage/?post=$id&pwid=$pwid&action=deactivate'>Deactivate this listing</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href='http://pennua.org/textbook/my-account/'>View Your Posts</a><br />   
      </center>
 	  </body>
	</html>";
    
    sendEmail("$email","Are you still selling $title?","$message");    
  }  
  echo "<br />";
  
}

?>
<ul>
    <li><a href="<?php echo SITE_URL;?>/admin/optimize.php?action=cache" onClick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Delete Cache");?> <?php echo round((time()-filemtime(CACHE_DATA_FILE))/60,1);?> <?php echo _("minutes")?></a></li>
    <li><a href="<?php echo SITE_URL;?>/admin/optimize.php?action=notconfirmed" onClick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Delete Ads not confirmed in 3 days");?></a></li>
				<li><a href="optimize.php?action=senddeactivate" onclick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Send Deactivation Emails to Posts Older than 1mo");?></a></li>
    <li><a href="<?php echo SITE_URL;?>/admin/optimize.php?action=db" onClick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Optimize  all tables found in the database");?></a></li>
    <li><a href="<?php echo SITE_URL;?>/admin/phpinfo.php" ><?php echo _("PHP Info")?></a></li>
</ul>
<?php
require_once('footer.php');
?>