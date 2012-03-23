<?php
  require_once('../../includes/header.php');

  if (isset($_GET['FB'])) {
    $_SESSION['FB_session'] = true;
  }
  // someone is trying to log in with FB
  if ($_SESSION['FB_session']) {
    $facebook = new Facebook(array(
      'appId'  => FB_APP_ID,
     'secret' => FB_APP_SECRET,
    ));
    $user = $facebook->getUser();
    $account = new Account($user);
    if ($account->FBlogOn($user)) {
      header('Location:'. $_SERVER['HTTP_REFERER']);
      die();
    } else {
      // search for their name based on their FB name
      $user_profile = $facebook->api('/me');
      // if not found, create a new account for them with their new credentials
      //header("Location: register.htm");
    }
  } else if ($_POST) {
    echo "help";
    $email = cP('email');
    $password = cP('password');
    $rememberme = cP('rememberme');
    if ($rememberme == "1") $rememberme = true;
    else $rememberme = false;
  
    $account = new Account($email);
    if ($account->logOn($password,$rememberme,"ocEmail")){
      header('Location:'.$_SERVER['HTTP_REFERER']);
      die();
    } else {
      if (!$account->exists) {//account not found by email
        header('Location:'.$_SERVER['HTTP_REFERER'] . '?error=Account+not+found');
        die();
      } elseif (!$account->status_password) { //wrong password
        header('Location:'. $_SERVER['HTTP_REFERER']. '?error=Wrong+password');
        die();
      } elseif (!$account->active) { //account is disabled
        header('Location:'.$_SERVER['HTTP_REFERER']. '?error=Account+is+not+yet+activated');
        die();
      }
    }
  } else {
    echo "what the fuck<br/>";
    echo 'event:' . cp('email');
    $email = $_COOKIE["ocEmail"];
    if ($email!="") $rememberme = "1";
    header('Location:'.$_SERVER['HTTP_REFERER']);
  }

?>
