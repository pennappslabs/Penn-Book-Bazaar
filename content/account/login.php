<?php
require_once('../../includes/header.php');
require '../../facebook-php-sdk/src/facebook.php';

$account = Account::createBySession();

if ($account->exists){
    header("Location: ".accountURL());
    die();
}
?>
<h2><?php echo _("Login")?></h2>
<?php

$facebook = new Facebook(array(
      'appId'  => '160063330776916',
      'secret' => '12a83f54102db21f68cdf0fb52dcf2ff',
    ));
$user = $facebook->getUser();

// someone is trying to log in with FB
if ($user) {
  $account = new Account($user);
  if ($account->FBlogOn($user)) {
    header("Location: ".accountURL());
    die();
  }
} else if ($_POST){
  $email = cP('email');
  $password = cP('password');
  $rememberme = cP('rememberme');
  if ($rememberme == "1") $rememberme = true;
  else $rememberme = false;
  
  $account = new Account($email);
  if ($account->logOn($password,$rememberme,"ocEmail")){
      header("Location: ".accountURL());
      die();
  }
  else {
    if (!$account->exists) {
      echo "<div id='sysmessage'>"._("Account not found")."</div>";//account not found by emaili
    }
    elseif (!$account->status_password) echo "<div id='sysmessage'>"._("Wrong password")."</div>";//wrong password
    elseif (!$account->active) echo "<div id='sysmessage'>"._("Account is disabled")."</div>";//account is disabled
  }
} else {
    $email = $_COOKIE["ocEmail"];
    if ($email!="") $rememberme = "1";
}
?>
<div>
<script>               
  window.fbAsyncInit = function() {
    FB.init({
      appId: '<?php echo $facebook->getAppID() ?>', 
      cookie: true, 
      xfbml: true,
      oauth: true
    });
    FB.Event.subscribe('auth.login', function(response) {
      window.location.reload();
    });
    FB.Event.subscribe('auth.logout', function(response) {
      window.location.reload();
    });
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
<div id='fblogin'><fb:login-button></fb:login-button></div>
<div id='OR' style='font-size: 20px; padding-left: 10px; padding-bottom: 10px; padding-top: 10px;'>OR</div>
<form id="loginForm" name="loginForm" title="bob" action="" method="post" 
  onsubmit="return checkForm(this);">
	<p><label for="email"><?php echo _("Penn Email Address")?>:<br />
    <input type="text" name="email" id="email" maxlength="145" value="<?php echo $email;?>" onblur="validateEmail(this);" lang="false" /></label></p>
	<p><label for="password"><?php echo _("Password")?>:<br />
    <input type="password" name="password" id="password" maxlength="<?php PASSWORD_SIZE?>" onblur="validateText(this);" lang="false" /></label></p>
	<p><label for="rememberme"><input type="checkbox" name="rememberme" id="rememberme" value="1" <?php if ($rememberme == "1") echo "checked ";?> style="width: 10px;" /><small><?php echo _("Remember me on this computer");?></small></label></p>
	<p><input name="submit" id="submit" type="submit" value="<?php echo _("Submit")?>" /></p>
    <br />
	<p><?php echo '<a href="'.accountRecoverPasswordURL().'">'._("Forgot My Password").'</a>';?></p>
</form>
</div>
<br/>
<h3><?php echo _("If you do not have an account") .' '.'<a href="'.accountRegisterURL().'">'._("Register").'</a>';?></h3>
<?php
require_once('../../includes/footer.php');
?>
