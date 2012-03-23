<?php

require_once('../../includes/header.php');

Account::logOut();
$_SESSION['FB_session'] = false;
header("Location: ".SITE_URL);
die();

?>
