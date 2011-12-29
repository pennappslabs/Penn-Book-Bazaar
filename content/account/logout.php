<?php

require_once('../../includes/header.php');

Account::logOut();
header("Location: ".SITE_URL);
die();

?>