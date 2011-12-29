<?php
require_once('../includes/header.php');
if ($_POST){//contact form
	if(cP("math") == $_SESSION["mathCaptcha"])	{
		if (isEmail(cP("email"))){//is email
			if(!isSpam(cP("name"),cP("email"),cP("msg"))){//check if is spam!
				//generate the email to send to the client that is contacted
				$subject=_("Contact").SEPARATOR.cP("subject").SEPARATOR. $_SERVER['SERVER_NAME'];
				$body=cP("name")." (".cP("email").") "._("contacted you about the Ad") . "<br /><br />".cP("msg");
	
				sendEmailComplete(NOTIFY_EMAIL,$subject,$body,cP("email"),cP("name"));
				
				echo "<div id='sysmessage'>"._("Message sent, thank you")."</div>";
			}//end akismet
			else echo "<div id='sysmessage'>"._("Oops! Spam? If it was not spam, contact us")."</div>";
		}
		else echo "<div id='sysmessage'>"._("Wrong email")."</div>";	
	}
	else echo "<div id='sysmessage'>"._("Wrong captcha")."</div>";
}
?>
<a href="<?php echo SITE_URL."/".contactURL()."?subject="._("Suggest new category");?>"><?php echo _("Suggest new category");?></a>
<h3><?php echo _("Contact");?></h3>
<form method="post" action="" id="contactItem" onsubmit="return checkForm(this);">
<p>
<?php echo _("Your Name");?>*:<br />
<input id="name" name="name" type="text" value="<?php echo cP("name");?>" maxlength="75" onblur="validateText(this);"  onkeypress="return isAlphaKey(event);" lang="false"  /><br />
<?php echo _("Email");?>*:<br />
<input id="email" name="email" type="text" value="<?php echo cP("email");?>" maxlength="120" onblur="validateEmail(this);" lang="false"  /><br />
<?php echo _("Subject");?>*:<br />
<input id="subject" name="subject" type="text" value="<?php echo cP("subject");?><?php echo cG("subject");?>" maxlength="75" onblur="validateText(this);"  onkeypress="return isAlphaKey(event);" lang="false"  /><br />
<?php echo _("Message");?>*:<br />
<textarea rows="10" cols="79" name="msg" id="msg" onblur="validateText(this);"  lang="false"><?php echo strip_tags(stripslashes($_POST['msg']));?></textarea><br />
<?php mathCaptcha();?>
<input id="math" name="math" type="text" size="2" maxlength="2"  onblur="validateNumber(this);"  onkeypress="return isNumberKey(event);" lang="false" />
<br />
<br />
<input type="submit" id="submit" value="<?php echo _("Contact");?>" />
</p>
</form>
<?php
require_once('../includes/footer.php');
?>