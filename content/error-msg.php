<?php
require_once('../includes/header.php');
if (cG("msg")==1) echo _("There was a problem with your post. Please do not use active code")." <a href=\"javascript:history.go(-1)\">"._("Go Back")."</a>";
require_once('../includes/footer.php');
?>