<?php
require_once('../../includes/header.php');
?>
<h2><?php echo _("Forgot My Password")?></h2>
<?php
$show_form = true;
if ($_POST){
    $email = trim(cP('email'));
    $account = new Account($email);
    
    if ($account->exists){
        $message='<p>'._("Your log on information").'</p>
    	<p><label>'._("Email").': '.$account->email.'</label><br/>
        <label>'._("New Password").': '.$account->resetPassword().'</label><br/><br/>
        You can always change your password once you log on.</p>';
        
        $array_content[]=array("ACCOUNT", $account->name);
        $array_content[]=array("MESSAGE", $message);
        
        $bodyHTML=buildEmailBodyHTML($array_content);
        
    	sendEmail($email,_("Your log on information")." - ".SITE_NAME,$bodyHTML);//password to the account's email	
        
        $show_form = false;
        echo "<div id='sysmessage'>"._("Your password has been sent").". "._("Please, check your email")."</div>";//password sent notice
    } else echo "<div id='sysmessage'>"._("Account not found")."</div>";//account not found by email
} else $email = $_COOKIE["ocEmail"];

if ($show_form){
?>
<div>
<form name="recoverPasswordForm" action="" onsubmit="return checkForm(this);" method="post">
    <p><label for="email"><?php echo _("Email")?>:<br />
    <input type="text" id="email" name="email" value="<?php echo $email?>" maxlength="145" onblur="validateEmail(this);" lang="false" /></label></p>
    <br />
    <p><input name="submit" id="submit" type="submit" value="<?php echo _("Submit")?>" /></p>
</form>
</div>
<?php
}
require_once('../../includes/footer.php');
?>