<?php
require_once('../../includes/header.php');

$account = Account::createBySession();
if ($account->exists){
    $email = $account->email;
    $name = $account->name;
} else {
    header("Location: ".accountLoginURL());
    die();
}
?>
<h3><?php echo _("Change My Password")?></h3>
<?php
if ($_POST){
    $name = cP('name');
    $password = cP('password');
    $password_confirmation = cP('password_confirmation');

    if (trim($password)!=""){
        if ($password != $password_confirmation) echo "<div id='sysmessage'>"._("Passwords do not match")."</div>";
        else{
            $account->updateName($name);
            $account->updatePassword($password);
            
            echo "<div id='sysmessage'>"._("Your account has been updated")."</div>";
        }
    } else {
        $account->updateName($name);
        
        echo "<div id='sysmessage'>"._("Your account has been updated")."</div>";
    }
} else {
    $name = $account->name;
}
?>
<div>
<form id="settingsForm" action="" onsubmit="return checkForm(this);" method="post">
    <p><?php echo "Account Email: ".$email;?></p>
    <p><label for="name"><?php echo _("Name")?>:<br />
    <input type="text" id="name" name="name" value="<?php echo $name;?>" maxlength="250" onblur="validateText(this);" lang="false" /></label></p>
    <p><label for="password"><?php echo _("Password")?>:<br />
    <input type="password" id="password" name="password" value="" /></label></p>
    <p><label for="password_confirmation"><?php echo _("Confirm password")?>:<br />
    <input type="password" id="password_confirmation" value="" name="password_confirmation" /></label></p>
    <br />
    <p><input name="submit" id="submit" type="submit" value="<?php echo _("Submit")?>" /></p>
</form>
</div>
<?php

require_once('../../includes/footer.php');
?>