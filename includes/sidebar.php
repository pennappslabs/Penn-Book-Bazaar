<?php
////////////////////////////////////////////////////////////
//Sidebar generator
////////////////////////////////////////////////////////////

function getSideBar($beg,$end){//generates the sidebar reading from the config.php
  $widgets=explode(",",SIDEBAR);
  foreach ($widgets as $widget){
    $widget="sb_".$widget;
    echo $widget($beg,$end);
  }
}

//////////////////////////////////////////////////////
//Side bar functions. ALL OF THEM MUST START ON sb_FUNCTION_NAME, to add them in the config file just write FUNCTION_NAME,
/////////////////////////////////////////////////////

function sb_new($beg,$end){//add new
  return $beg.'<b><a title="'._("Publish a new Ad").'" href="'.SITE_URL.newURL().'">'._("Publish a new Ad").'</a></b>'.$end;
}
////////////////////////////////////////////////////////////
function sb_search($beg,$end){//serach form
  global $categoryName,$idCategory,$currentCategory,$type,$location;
    if (cG("s")=="") $ws=_("Search")."...";
    else $ws=cG("s");
    $search="<h4>Quick Search</h4>";
    $search.= "<form method=\"get\" action=\"".SITE_URL."\">
      <p><input name=\"s\" id=\"s\" maxlength=\"15\" title=\""._("Search")."\"
        onblur=\"this.value=(this.value=='') ? '$ws' : this.value;\"
        onfocus=\"this.value=(this.value=='$ws') ? '' : this.value;\"
        value=\"$ws\" type=\"text\" /></p>";

    if(isset($categoryName)) $search.='<p><input type="hidden" name="category" value="'.$currentCategory.'" /></p>';
    if(isset($location)) $search.='<p><input type="hidden" name="location" value="'.getLocationFriendlyName($location).'" /></p>';

    $search.='</form>';
    $search.="Can't find what you need? Try ";
    $search.=advancedSearchURL();
    $search.="!";

  return $beg.$search.$end;
}
////////////////////////////////////////////////////////////
function sb_locations($beg,$end){//locations list (state or city)
  if (LOCATION){
    global $location,$currentCategory,$selectedCategory;

    if (isset($location)) {
      $currentlocation = getLocationName($location);
      $locationparent = getLocationParent($location);
    } else $locationparent = 0;

    $locationcontent = "<h4>"._("Location")."</h4>";

    if ($locationparent != 0) $locationcontent .= "<h4><a href=\"".SITE_URL.catURL($currentCategory,$selectedCategory,getLocationFriendlyName($locationparent))."\">".getLocationName($locationparent)."</a> / $currentlocation</h4>";
    elseif (isset($location)) {
      $locationroot = LOCATION_ROOT;
      if ($locationroot == "") $locationroot = _("Home");
      $locationcontent .= "<h4><a href=\"".SITE_URL.catURL($currentCategory,$selectedCategory,$_unused_)."\">$locationroot</a> / $currentlocation</h4>";
    }

    if (!isset($location)) $location = 0;
    global $ocdb;
    $query = "select idLocation, name, friendlyName from ".TABLE_PREFIX."locations where idLocationParent=$location order by name";
    $result=$ocdb->getRows($query);

    $i = 0;
    $q = count($result);
    $z = round($q/2);

    foreach ($result as $location_row ) {
      if ($i==0 or $i==$z) $locationcontent .= "<div class=\"columns\"><ul>";

      $locationcontent .= "<li><a href=\"".SITE_URL.catURL($currentCategory,$selectedCategory,$location_row["friendlyName"])."\">".$location_row["name"]."</a></li>";

      if ($i==($z-1) or $i==($q-1)) $locationcontent .= "</ul></div>";

      $i++;
    }

    $locationcontent .= "<div class=\"clear\" />";

    return $beg.$locationcontent.$end;
  }
}
////////////////////////////////////////////////////////////
function sb_infolinks($beg,$end){//site stats info and tools linsk rss map..
  global $idCategory;
  $info.= '<b>'._("Total Ads").':</b> '.totalAds($idCategory).SEPARATOR
    .' <b>'._("Views").':</b> '.totalViews($idCategory).SEPARATOR
    .' <b><a href="'.rssURL().'?category='.$currentCategory.'&amp;type='.$type.'">RSS</a></b>';
     if (MAP_KEY!="") $info.=SEPARATOR.'<b><a href="'.SITE_URL.'/'.mapURL().'?category='.$currentCategory.'&amp;type='.$type.'">'._("Map").'</a></b>';
   return $beg.$info.$end;
}
////////////////////////////////////////////////////////////
function sb_donate($beg,$end){//donation
  return $beg.'<h4>'._("Recommended").'</h4><br />Please donate to help developing this software. No matter how much, even small amounts are very welcome.
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amp;business=paypal%40open-classifieds%2ecom&amp;lc=EUR&amp;item_name=Donate%20Open%20Classifieds&amp;amount=5.00&amp;currency_code=EUR&amp;no_note=1&amp;no_shipping=2&amp;rm=1&amp;weight_unit=lbs&amp;bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHosted" target="_blank">
<img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="" />
</a> Thanks. <br /><br /> To erase this please go to: Admin->Settings->Look and Feel->Widget Sidebar->donate.'.$end;
}
////////////////////////////////////////////////////////////
function sb_advertisement($beg,$end){//advertisement
  return $beg.ADVERT_SIDEBAR.$end;
}
////////////////////////////////////////////////////////////
function sb_popular($beg,$end){//popular items
  if (COUNT_POSTS){
    global $categoryName,$idCategory;
    $ret=$beg."<h4>"._("Most popular")." $categoryName:</h4>";
    $ret.=generatePopularItems(7,5,$idCategory);
    $ret.="*"._("Last Week").$end;
    return $ret;
  }
}
////////////////////////////////////////////////////////////
function sb_item_tools($beg,$end){//utils for admin
  global $idItem,$itemPassword;
  if(isset($idItem)&&isset($_SESSION['admin'])){
    echo $beg;?>
    <h4><?php echo _("Classifieds tools");?>:</h4>
    <ul>
      <li><a href="<?php echo itemManageURL();?>?post=<?php echo $idItem;?>&amp;pwd=<?php echo $itemPassword;?>&amp;action=edit">
        <?php echo _("Edit");?></a>
      </li>
      <li><a onClick="return confirm('<?php echo _("Deactivate");?>?');"
        href="<?php echo itemManageURL();?>?post=<?php echo $idItem;?>&amp;pwd=<?php echo $itemPassword;?>&amp;action=deactivate">
        <?php echo _("Deactivate");?></a>
      </li>
      <li>  <a onClick="return confirm('<?php echo _("Spam");?>?');"
          href="<?php echo itemManageURL();?>?post=<?php echo $idItem;?>&amp;pwd=<?php echo $itemPassword;?>&amp;action=spam">
            <?php echo _("Spam");?></a>
      </li>
      <li><a onClick="return confirm('<?php echo _("Delete");?>?');"
        href="<?php echo itemManageURL();?>?post=<?php echo $idItem;?>&amp;pwd=<?php echo $itemPassword;?>&amp;action=delete">
        <?php echo _("Delete");?></a>
      </li>
      <li><a href="<?php echo SITE_URL;?>/admin/logout.php"><?php echo _("Logout");?></a>
      </li>
    </ul>
  <?php
    echo $end;
  }
}
////////////////////////////////////////////////////////////
function sb_links($beg,$end){//links sitemap
    echo $beg;

  ?>
    <h4><?php echo _("Menu");?>:</h4>
    <ul>
      <?php if(FRIENDLY_URL) {?>

      <?php }else { ?>
        <li><a href="<?php echo SITE_URL;?>/content/search.php"><?php echo _("Advanced Search");?></a></li>
        <li><a href="<?php echo SITE_URL;?>/content/site-map.php"><?php echo _("Sitemap");?></a></li>
        <li><a href="<?php echo SITE_URL;?>/content/privacy.php"><?php echo _("Privacy Policy");?></a></li>
      <?php } ?>
       <li><a href="<?php echo SITE_URL;?>/content/faq.php"><?php echo _("Frequently Asked Questions");?></a></li>
         <li><a href="<?php echo SITE_URL;?>/content/terms.php"><?php echo _("Terms of Use");?></a></li>
          <li><a href="<?php echo SITE_URL."/".u(_("Privacy Policy"));?>.htm"><?php echo _("Privacy Policy");?></a></li>
          <li><a href="<?php echo SITE_URL;?>/content/acknowledgments.php"><?php echo _("Acknowledgments");?></a></li>

      <li>Found glitches? Need help? <a href="mailto:labs@pennapps.com">Send us an email</a>!</li>
      <!--   <li><a href="<?php echo SITE_URL;?>/admin/"><?php echo _("Administrator");?></a></li> -->
    </ul>
  <?php
  echo $end;
}

////////////////////////////////////////////////////////////
function sb_comments($beg,$end){//disqus comments
  if (DISQUS!=""){
    return $beg .'<script type="text/javascript" src="http://disqus.com/forums/'.DISQUS.'/combination_widget.js?num_items=5&hide_mods=0&color=blue&default_tab=recent&excerpt_length=200"></script>'.$end;
  }
}

////////////////////////////////////////////////////////////
function sb_translator($beg,$end){//google translate
  $lang = LANGUAGE;
  return $beg.'<div id="google_translate_element"></div><script type="text/javascript">
  function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: \''.$lang.'\'}, \'google_translate_element\');
  }</script><script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>'.$end;
}

///////////////////////////////////////////////////////////
function sb_theme($beg,$end){//theme selector
  if (THEME_SELECTOR){
    echo $beg;?>
    <b onclick="openClose('theme_sel');" style="cursor:pointer;"><?php echo THEME;?></b>
    <div id="theme_sel" style="display:none;"><ul>
    <?php
    $themes = scandir(SITE_ROOT."/themes");
    foreach ($themes as $theme) {
      if($theme!="" && $theme!=THEME && $theme!="." && $theme!=".." && $theme!="wordcloud.css"){
        echo '<li><a href="'.SITE_URL.'/?theme='.$theme.'">'.$theme.'</a></li>';
      }
    }
    echo "</ul></div>" . $end;
  }
}
////////////////////////////////////////////////////////////
function sb_categories_cloud($beg,$end){// popular categories
  global $categoryName;
  if(!isset($categoryName)){
    echo $beg."<h4>"._("Categories")."</h4><br />";
    generateTagPopularCategories();
    echo $end;
  }
}
////////////////////////////////////////////////////////////
function sb_account($beg,$end){
  $account = Account::createBySession();
  if ($account->exists){
    $ret='<h4>'._("").' '.$account->name.': Account</h4>';
    $ret.= '<li><a href="http://pennbookbazaar.com/publish.htm">'._("Post a New Book!").'</a></li>';
    $ret.= '<li><a href="'.accountURL().'">'._("My Posts").'</a></li>';
    $ret.= '<li><a href="'.accountSettingsURL().'">'._("Change My Password").'</a></li>';
    $ret.= '<li><a href="'.accountLogoutURL().'">'._("Logout").'</a></li>';
    return $beg.$ret.$end;
  }
  else {

if ($_POST && !isset($_POST["agree_terms"])){
  $email = cP('email');
  $password = cP('password');
  $rememberme = cP('rememberme');
  if ($rememberme == "1") $rememberme = true;
  else $rememberme = false;

  $account = new Account($email);
  if ($account->logOn($password,$rememberme,"ocEmail")){
    return sb_account($beg,$end);
  } else {
    if (!$account->exists)//account not found by email
      echo "<div id='sysmessage'>"._("Account not found")."</div>";
    elseif (!$account->status_password) //wrong password
      echo "<div id='sysmessage'>"._("Wrong password")."</div>";
    elseif (!$account->active) { //account is disabled
      echo "<div id='sysmessage'>" .
           _("Account is not yet activated — check your spam for the " .
             "verification e-mail <small style='font-size: small'>" .
             "(subject: 'Confirm your account - Penn Book Bazaar')</small>") .
             " - <a href=\"".accountResendEmailURL()."\">Resend confirmation email</a>".
           "</div>";
    }
  }
} else {
  $email = $_COOKIE["ocEmail"];
  if ($email!="") $rememberme = "1";
}
echo $beg;
?>
  <h4> Welcome - Login </h4>
<?php
$is_recover_page =
  ($_SERVER['REQUEST_URI'] == "/textbook/forgot-my-password.htm") ?
  true : false;
?>
   <form <?php if ($is_recover_page){echo 'class="hidden" ';} ?>id="loginForm" name="loginForm" action="" method="post" onsubmit="return checkForm(this);">
  <p><label for="email"><?php echo _("Penn Email Address")?>:<br />
  <input type="text" name="email" id="email" maxlength="145" value="<?php echo $email;?>" onblur="validateEmail(this);" lang="false" /></label></p>
  <p><label for="password"><?php echo _("Password")?>:<br />
  <input type="password" name="password" id="password" maxlength="<?php PASSWORD_SIZE?>" onblur="validateText(this);" lang="false" /></label></p>
  <p><label for="rememberme"><input type="checkbox" name="rememberme" id="rememberme" value="1" <?php if ($rememberme == "1") echo "checked ";?> style="width: 10px;" /><small><?php echo _("Remember me on this computer");?></small></label></p>
  <p><input name="submit" id="submit" type="submit" value="<?php echo _("Login")?>" /></p>
  <br />
  <p><?php echo '<a href="'.accountRecoverPasswordURL().'">'._("Forgot My Password").'</a>';?><br />
  <?php echo _("If you do not have an account") .' '.'<a href="'.accountRegisterURL().'">'._("Register").'</a>';?></p>
</form>
<?php
echo $end;
  }
}

?>