<?php
require_once('../../includes/header.php');
?>
<h2><?php echo _("Resend Confirmation Email")?></h2>
<?php
$show_form = true;
if ($_POST){
    $email = trim(cP('email'));
    $account = new Account($email);
    
    if ($account->exists){
        $token = $account->token();

        $url=accountRegisterURL();
        if (strpos($url,"?")) $url.='&amp;account='.$email.'&amp;token='.$token.'&amp;action=confirm';
        else $url.='?account='.$email.'&amp;token='.$token.'&amp;action=confirm';

        $message='<p>'._("Click the following link or copy and paste it into your browser address field to activate your account").'</p>
        <p><a href="'.$url.'">'._("Confirm account").'</a></p><p>'.$url.'</p>';
        
        $array_content[]=array("ACCOUNT", $account->name);
        $array_content[]=array("MESSAGE", $message);
        
        $bodyHTML=buildEmailBodyHTML($array_content);
        
    	sendEmail($email,_("Confirm your account")." - ".SITE_NAME,$bodyHTML);//resends confirmation email
        
        $show_form = false;
        echo "<div id='sysmessage'>"._("Instructions to confirm your account has been sent").". "._("Please, check your email")."</div>";//password sent notice
    } else echo "<div id='sysmessage'>"._("Account not found")."</div>";//account not found by email
} else $email = $_COOKIE["ocEmail"];

if ($show_form){
?>
<div>
<form name="resendEmailForm" action="" onsubmit="return checkForm(this);" method="post">
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