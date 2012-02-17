<?php
  require_once('includes/header.php');

  mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect: " . mysql_error());
  mysql_select_db(DB_NAME);
  
  $result = mysql_query("SELECT password FROM oc_accounts");
  echo mysql_num_rows($result);
  echo "<br />";
  
  while ($row = mysql_fetch_array($result)) 
  {
  	//printf("%s \n", $row[0]);
    $salt = substr(md5(uniqid(rand(), true)), 0, 3);//random hashed substring of length 3
    $secpassword = sha1($salt.sha1($row['password']));
	$query1 = "UPDATE oc_accounts SET salt='".$salt."' WHERE password='".$row['password']."'";
	$result2 = mysql_query($query1);
	$query2 = "UPDATE oc_accounts SET passhash='".$secpassword."' WHERE password='".$row['password']."'";
	$result3 = mysql_query($query2);
	
	unset($salt);
	unset($secpassword);    
    //$result2 =	$ocdb->query("UPDATE ".TABLE_PREFIX."accounts SET passhash=$secpassword, salt=$salt");
  }
  	
?>
