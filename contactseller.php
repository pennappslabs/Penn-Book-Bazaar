<?php

if (LOGON_TO_POST){
    $account = Account::createBySession();
    if ($account->exists){
        $name = $account->name;
        $email = $account->email;
    } else {
        header("Location: ".accountLoginURL());
        die();
    }
}

?>

<?php

if (is_numeric($idItem )){
	 //contact form
	if (cP("contact")==1&&$itemAvailable==1){
		if(cP("math") == $_SESSION["mathCaptcha"])	{
			if (isEmail(cP("email"))){//is email
				if(!isSpam(cP("name"),cP("email"),cP("msg"))){//check if is spam!
					//generate the email to send to the client that is contacted
					$subject=_("Contact")." ".html_entity_decode($itemTitle, ENT_QUOTES, CHARSET).SEPARATOR. SITE_NAME;
                    
                    $message="<p>".cP("name")." (".cP("email").") "._("contacted you about the Ad") ." ".
							 $_SERVER['SERVER_NAME'].urldecode($_SERVER["REQUEST_URI"]) ."<br /><br />".
							 cP("msg").
							 "<br /><br />"._("Do not answer this email, answer to this account").": ".cP("email")."</p>";
                    
                    $array_content[]=array("ACCOUNT", _("User"));
                    $array_content[]=array("MESSAGE", $message);
                    
                    $bodyHTML=buildEmailBodyHTML($array_content);
                    
					sendEmailComplete($itemEmail,$subject,$bodyHTML,cP("email"),cP("name"));
					//for preventing f5
					$_SESSION["mathCaptcha"]="";
					echo "<div id='sysmessage'>"._("Message sent, thank you").".</div>";//
				}//end akismet
				else echo "<div id='sysmessage'>"._("Oops! Spam? If it was not spam, contact us");
			}
			else echo "<div id='sysmessage'>"._("Wrong email address or you need to login to contact this seller.")."</div>";//Wrong email address
		}
		else echo "<div id='sysmessage'>"._("Wrong Captcha")."</div>";//wrong captcha
	}
}

?>

  	<br />
  	<div class="contact" style="padding: 0 10px 10px 10px;">
	<h3 style="cursor:pointer;" onclick="openClose('contactmail');"><?php echo _("Contact");?> <?php echo $itemName.': '.$itemTitle;?></h3>
	<div id="contactmail" class="contactform form">
		<!-- <?php if ($itemPhone!=""){?><b><?php echo _("Phone");?>:</b> <?php echo encode_str($itemPhone); ?><?php }?> --!>
		<form method="post" action="" id="contactItem" onsubmit="return checkForm(this);">
		 <input id="name" name="name" type="hidden" value="<?php echo $name;?>" /> 
    <input id="email" name="email" type="hidden" value="<?php echo $email;?>" />
		
		
		<p>
            <label><small><strong>MESSAGE</strong></small></label><br />
            Your message will be delivered to <?php echo $itemName ?>'s Penn email account.
		    <textarea rows="10" cols="79" name="msg" id="msg" onblur="validateText(this);"  lang="false"><?php echo strip_tags(stripslashes($_POST['msg']));?></textarea><br />
		</p>
		<p>
            <label><small><?php  mathCaptcha();?></small></label>
		    <input id="math" name="math" type="text" size="2" maxlength="2"  onblur="validateNumber(this);"  onkeypress="return isNumberKey(event);" lang="false" />
            <br />
            <br />
		<input type="hidden" name="contact" value="1" />
		<input type="submit" id="submit" value="<?php echo _("Contact");?>" />
		</p>
		</form> 
	</div>
	</div>
