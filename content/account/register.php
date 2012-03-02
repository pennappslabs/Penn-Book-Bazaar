<?php
require_once('../../includes/header.php');
?>
<h2><?php echo _("Register (Penn Students Only)")?></h2>
<?php
$show_form = true;

if ($_POST){
  if(cP("math") == $_SESSION["mathCaptcha"])	{
    $name = cP('name');
    $email = cP('email');
    $password = cP('password');
    $password_confirmation = cP('password_confirmation');
    $agree_terms = cP('agree_terms');
  
    if ($agree_terms == "yes"){
      if (isEmail($email)){    
        if ($password == $password_confirmation){
          $account = new Account($email);
          if ($account->exists){
            echo "<div id='sysmessage'>"._("Account already exists")."</div>";
          }
          else {
            if ($account->Register($name,$email,$password)){
              $token=$account->token();
              
              $url=accountRegisterURL();
              if (strpos($url,"?")) $url.='&amp;account='.$email.'&amp;token='.$token.'&amp;action=confirm';
              else $url.='?account='.$email.'&amp;token='.$token.'&amp;action=confirm';
              
              $message='<p>'._("Click the following link or copy and paste it into your browser address field to activate your account").'</p>
            	<p><a href="'.$url.'">'._("Confirm account").'</a></p><p>'.$url.'</p>';
              
              $array_content[]=array("ACCOUNT", $name);
              $array_content[]=array("MESSAGE", $message);
              
              $bodyHTML=buildEmailBodyHTML($array_content);
              
            	sendEmail($email,_("Confirm your account")." - ".SITE_NAME,$bodyHTML);//email registration confirm request
              
              $show_form = false;
              echo "<div id='sysmessage'>"._("Instructions to confirm your account has been sent").". "._("Please, check your email")."</div>";
            } else echo _("An unexpected error has occurred trying to register your account");
          }
        } else echo "<div id='sysmessage'>"._("Passwords do not match")."</div>";
      } else echo "<div id='sysmessage'>"._("Wrong email")."</div>";
    } else echo "<div id='sysmessage'>"._("Terms agreement is required")."</div>";
  } else echo "<div id='sysmessage'>"._("Wrong captcha")."</div>";//wrong captcha
}

if (trim(cG('account'))!="" && trim(cG('token'))!="" && trim(cG('action'))=="confirm"){
  $show_form = false;
  
  $email = trim(cG('account'));
  $token = trim(cG('token'));
  
  $account = new Account($email);
  if ($account->exists){
    if ($account->Activate($token)){
      echo "<div id='sysmessage'>"._("Your account has been succesfully confirmed")."</div>";
      
      $bodyHTML="<p>"._("NEW account registered")."</p><br/>"._("Email").": ".$account->email.", Name: ".$account->name." - ".$account->signupTimeStamp();
    	sendEmail(NOTIFY_EMAIL,_("NEW account")." - ".SITE_NAME,$bodyHTML);//email to the NOTIFY_EMAIL
      
      $account->logOn($account->password());
      
      echo '<p><a href="'.accountURL().'">'._("Welcome").' '.$account->name.'</a></p><br/>';
    } else echo "<div id='sysmessage'>"._("An unexpected error has occurred trying to confirm your account")."</div>";
  } else echo "<div id='sysmessage'>"._("Account not found")."</div>";
}

if ($show_form){§
?>
<div>
<form id="registerForm" action="" onsubmit="return checkForm(this);" method="post">
  <p><label for="name"><?php echo _("Full Name")?>:<br />
  <input type="text" id="name" name="name" value="<?php echo $name;?>" maxlength="250" onblur="validateText(this);" lang="false" /></label></p>
  <p><label for="email"><?php echo _("Penn Email Address")?>:<br />
  <input type="text" id="email" name="email" value="<?php echo $email;?>" maxlength="145" onblur="validateEmail(this);" lang="false" /></label></p>
  <p><label for="password"><?php echo _("Password")?>:<br />
  <input type="password" id="password" name="password" value="" onblur="validateText(this);" lang="false" /></label></p>
  <p><label for="password_confirmation"><?php echo _("Confirm password")?>:<br />
  <input type="password" id="password_confirmation" value="" name="password_confirmation" onblur="validateText(this);" lang="false" /></label></p>
  <p><label for="agree_terms"><input type="checkbox" id="agree_terms" name="agree_terms" value="yes" style="width: 10px;" /> <?php echo _("Accept")?> <a  href="<?php echo termsURL();?>" target="_blank"><?php echo _("Terms")?></a> - <?php echo SITE_NAME?></label></p>
  <br />
  <?php  mathCaptcha();?>
	<p><input id="math" name="math" type="text" size="2" maxlength="2"  onblur="validateNumber(this);"  onkeypress="return isNumberKey(event);" lang="false" /></p>
  <br />
  <p><input name="submit" id="submit" type="submit" value="<?php echo _("Submit")?>" /></p>
</form>
</div>
<?php
}
require_once('../../includes/footer.php');
?>
