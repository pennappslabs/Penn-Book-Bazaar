<?php
require_once('access.php');
require_once('header.php');

if ($_POST){//if there's post action
    //print_r($_POST["SIDEBAR"]);
    $_POST["SIDEBAR"]=implode(",",$_POST["SIDEBAR"]);// sidebar
    
	if ($_POST["ADVERT_TOP"]) {
		$_POST["ADVERT_TOP"] = str_replace("\n", "", $_POST["ADVERT_TOP"]);
		$_POST["ADVERT_TOP"] = stripslashes($_POST["ADVERT_TOP"]);
	}
   
    $succeed=false;
    //generationg the config.php
	$config_content = "<?php\n//Open Classifieds Installation Config ".date("d/m/Y G:i:s")."\n";
	foreach  ($_POST AS $key => $value){
			if ($key!="submit" and $key!="TIMEZONE"){
			    if ($value=="TRUE") $config_content.="define('$key',true);\n";	
			    elseif ($value=="FALSE") $config_content.="define('$key',false);\n";	
				else $config_content.="define('$key','$value');\n";		
			}
	}
	$config_content.="date_default_timezone_set('".$_POST["TIMEZONE"]."');\n";
	$config_content.="?>";//	echo $config_content;
	
	//writting the config.php
	if(is_writable('../includes/config.php')){
		$file = fopen('../includes/config.php' , "w+");
		if (fwrite($file, $config_content)=== FALSE) {
			$msg=_("Cannot write to the configuration file")." '/includes/config.php'";
			$succeed=false;
		}else $succeed=true;
		fclose($file);
	}
	else $msg=_("The configuration file")." '/includes/config.php' "._("is not writable").". "._("Change the permissions");

    //succeded writting the config.php
    if ($succeed){
        generateSitemap();
        //Ocaku change settings
        if (OCAKU){
        	$ocaku=new ocaku();
	        $data=array(
				'KEY'=>$_POST['OCAKU_KEY'],
				'siteName'=>$_POST['SITE_NAME'],
				'email'=>$_POST['NOTIFY_EMAIL'],
				'language'=>$_POST["LANGUAGE"]
			 );
			 $ocaku->editSite($data);
        }
        //end ocaku change settings
	    if (LANGUAGE!=$_POST["LANGUAGE"]){//changing the language generating new .htaccess
	        jsRedirect(SITE_URL."/admin/htaccess.php?lang=".$_POST["LANGUAGE"]);
	    }//end if language
		else { 
		    //echo "Updated";
		    //require_once('../includes/admin/footer.php');
		    jsRedirect(SITE_URL."/admin/settings.php?msg=Updated");
		    die();
		}
    }
    else echo $msg;
}//end post

if (cG("msg")!="") echo '<h4>'.cG("msg").'</h4>';
?>
<script type="text/javascript" src="../includes/js/common.js"></script>
<script  type="text/javascript">
function moveUp(selectId) {
	var selectList = document.getElementById(selectId);
	var selectOptions = selectList.getElementsByTagName('option');
	for (var i = 1; i < selectOptions.length; i++) {
		var opt = selectOptions[i];
		if (opt.selected) {
			selectList.removeChild(opt);
			selectList.insertBefore(opt, selectOptions[i - 1]);
		}
       }
}
function moveDown(selectId) {
	var selectList = document.getElementById(selectId);
	var selectOptions = selectList.getElementsByTagName('option');
	for (var i = selectOptions.length - 2; i >= 0; i--) {
		var opt = selectOptions[i];
		if (opt.selected) {
		   var nextOpt = selectOptions[i + 1];
		   opt = selectList.removeChild(opt);
		   nextOpt = selectList.replaceChild(opt, nextOpt);
		   selectList.insertBefore(nextOpt, opt);
		}
       }
}
function swapElement(fromList,toList){
    var selectOptions = document.getElementById(fromList);
    for (var i = 0; i < selectOptions.length; i++) {
        var opt = selectOptions[i];
        if (opt.selected) {
            document.getElementById(fromList).removeChild(opt);
            document.getElementById(toList).appendChild(opt);
            i--;
        }
    }
}
function selectAllOptions(selStr)
{
    var selObj = document.getElementById(selStr);
    for (var i=0; i<selObj.options.length; i++) {
        selObj.options[i].selected = true;
    }
}
</script>
<h2><?php echo _("Settings");?> v<?php echo VERSION;?></h2>
<form id="install" action="settings.php" method="post" onsubmit="selectAllOptions('selected');return checkForm(this);">

<div class="settingsTitle" onclick="openClose('bconf');"><h3><?php echo _("Basic Configuration");?></h3></div>
<div class="settingsTable" id="bconf">
<fieldset>
<p>
	<label><?php echo _("Site Name");?>:</label>
	<input  type="text" name="SITE_NAME" value="<?php echo SITE_NAME;?>" class="text-long" lang="false" onblur="validateText(this);" />
</p>
<p>
	<label><?php echo _("Notifications Email");?>:</label>
	<input  type="text" name="NOTIFY_EMAIL"  value="<?php echo NOTIFY_EMAIL;?>" class="text-long" lang="false" onblur="validateEmail(this);"/>
</p>
<p>
	<label><?php echo _("Language");?>:</label>
	<select name="LANGUAGE" >
	    <option value="<?php echo LANGUAGE;?>"><?php echo LANGUAGE;?></option>
	    <option value="en_EN">en_EN</option>
	    <?php
	    $languages = scandir("../languages");
	    foreach ($languages as $lang) {
		    
		    if( strpos($lang,'.')==false && $lang!='.' && $lang!='..' && $lang!=LANGUAGE){
			    echo "<option value=\"$lang\">$lang</option>";
		    }
	    }
	    ?>
	</select>
</p>
<p>
	<label><?php echo _("Locale Extension");?>:</label>
	<input  type="text" name="LOCALE_EXT"  value="<?php echo LOCALE_EXT;?>" class="text-long" />
</p>
<p>
	<label><?php echo _("Time Zone");?>:</label>
	<select id="TIMEZONE" name="TIMEZONE">
	<?php
	$timezone_identifiers = DateTimeZone::listIdentifiers();
	foreach( $timezone_identifiers as $value ){
	    if ( preg_match( '/^(America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific|Australia)\//', $value ) ){
	    	$ex=explode("/",$value);//obtain continent,city
	    	
	    	if ($continent!=$ex[0]){
	    		if ($continent!="") echo '</optgroup>';
	    		echo '<optgroup label="'.$ex[0].'">';
	    	}
	    	
	    	$city=$ex[1];
	    	$continent=$ex[0];
	    	if (date_default_timezone_get()==$value) echo '<option selected=selected value="'.$value.'" >'.$city.'</option>';
	    	else echo '<option value="'.$value.'">'.$city.'</option>';	    	
	    	
	    }
	}
	?>
		</optgroup>
	</select>
</p>
<p>
	<label><?php echo _("Administrator Login");?>:</label>
	<input type="text" name="ADMIN" value="<?php echo ADMIN;?>" class="text-long" lang="false" onblur="validateText(this);" />
</p>
<p>
	<label><?php echo _("Administrator Password");?>:</label>
	<input type="password" name="ADMIN_PWD" value="<?php echo ADMIN_PWD;?>" class="text-long" />	
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('dbconf');"><h3><?php echo _("Database Configuration");?></h3></div>
<div class="settingsTable" id="dbconf">
<fieldset>
<p>
	<label><?php echo _("Host Name");?>:</label>
	<input type="text" name="DB_HOST" value="<?php echo DB_HOST;?>" class="text-long" lang="false" onblur="validateText(this);" />
</p>
<p>
	<label><?php echo _("Database Username");?>:</label>
	<input type="text" name="DB_USER"  value="<?php echo DB_USER;?>" class="text-long" lang="false" onblur="validateText(this);" />
</p>
<p>
	<label><?php echo _("Database Password");?>:</label>
	<input type="password" name="DB_PASS" value="<?php echo DB_PASS;?>" class="text-long" />	
</p>
<p>
	<label><?php echo _("Database Name");?>:</label>
	<input type="text" name="DB_NAME" value="<?php echo DB_NAME;?>" class="text-long" lang="false" onblur="validateText(this);" />
</p>
<p>
	<label><?php echo _("Database Charset");?>:</label>
	<input type="text" name="DB_CHARSET" value="<?php echo DB_CHARSET;?>" class="text-long" lang="false" onblur="validateText(this);" /><?php echo _("IMPORTANT: If you change this be sure you change it in the database structure and maybe you need to change it in the HTML Charset as well");?>
</p>
<p>
	<label><?php echo _("Table Prefix");?>:</label>
	<input type="text" name="TABLE_PREFIX" value="<?php echo TABLE_PREFIX;?>" class="text-long" /><?php echo _("Multiple installations in one database if you give each a unique prefix. Only numbers, letters, and underscores");?>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('smtpconf');"><h3><?php echo _("Mail Server Configuration");?></h3></div>
<div class="settingsTable" id="smtpconf">
<fieldset>
<p>
	<label><?php echo _("Host Name");?>:</label>
	<input type="text" name="SMTP_HOST" value="<?php echo SMTP_HOST;?>" class="text-long" />
</p>
<p>
	<label><?php echo _("Server Port");?>:</label>
	<input type="text" name="SMTP_PORT" value="<?php echo SMTP_PORT;?>" class="text-long" /><?php echo _("Leave blank to use default SMTP port");?>
</p>
<p>
	<label><?php echo _("Authentication");?>:</label>
	<select name="SMTP_AUTH" >
	<option <?php if(SMTP_AUTH)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!SMTP_AUTH)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Enables SMTP authentication");?>
</p>
<p>
	<label><?php echo _("Username");?>:</label>
	<input  type="text" name="SMTP_USER" value="<?php echo SMTP_USER;?>" class="text-long" />
</p>
<p>
	<label><?php echo _("Password");?>:</label>
	<input type="password" name="SMTP_PASS" value="<?php echo SMTP_PASS;?>" class="text-long" />	
</p>
<p>
	<label>GMAIL:</label>
	<select name="GMAIL" >
	<option <?php if(GMAIL)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!GMAIL)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Uses GMAIL for SMTP, perfect if you do not have email server or you cannot manage to configure it");?>
</p>
<p>
	<label>GMAIL <?php echo _("Username");?>:</label>
	<input type="text" name="GMAIL_USER" value="<?php echo GMAIL_USER;?>" class="text-long" /><?php echo _("Account Name");?>
</p>
<p>
	<label>GMAIL <?php echo _("Password");?>:</label>
	<input type="password" name="GMAIL_PASS" value="<?php echo GMAIL_PASS;?>" class="text-long" /><?php echo _("Account Password");?>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('iniconf');"><h3><?php echo _("Initial Settings");?></h3></div>
<div class="settingsTable" id="iniconf">
<fieldset>
<p>
	<label><?php echo _("Site URL");?>:</label>
	<input  type="text" name="SITE_URL" value="<?php echo SITE_URL;?>" class="text-long" lang="false" />
</p>
<p>
	<label><?php echo _("Site Full Path");?>:</label>
	<input  type="text" name="SITE_ROOT" value="<?php echo SITE_ROOT;?>" class="text-long" lang="false" /><?php echo _("IMPORTANT: Path in the server");?>
</p>
<p>
	<label><?php echo _("HTML Charset");?>:</label>
	<input  type="text" name="CHARSET" value="<?php echo CHARSET;?>" lang="false" class="text-long" /><?php echo _("IMPORTANT: maybe you need to change it in your database Charset as well");?>. <a href="http://www.w3.org/International/O-charset-list.html"><?php echo _("List");?></a>
</p>
<p>
	<label><?php echo _("Date Format");?>:</label>
	<input  type="text" name="DATE_FORMAT" value="<?php echo DATE_FORMAT;?>" lang="false" class="text-long" /><?php echo _("Use a date format");?>!
</p>
<p>
	<label><?php echo _("Logon to Post");?>:</label>
	<select name="LOGON_TO_POST" >
	<option <?php if(LOGON_TO_POST)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!LOGON_TO_POST)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Require log on to post");?>
</p>
<p>
	<label><?php echo _("Posts counter");?>:</label>
	<select name="COUNT_POSTS" >
	<option <?php if(COUNT_POSTS)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!COUNT_POSTS)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Count the visitors per post");?>
</p>
<p>
	<label><?php echo _("Minimal search phrase length");?>:</label>
	<input  type="text" name="MIN_SEARCH_CHAR" value="<?php echo MIN_SEARCH_CHAR;?>" lang="false" class="text-small" /><?php echo _("Search less than this number will not be performed");?>
</p>
<p>
	<label><?php echo _("Password Size");?>:</label>
	<input type="text" name="PASSWORD_SIZE" value="<?php echo PASSWORD_SIZE;?>" lang="false" class="text-small" /><?php echo _("Size for the password used in new Posts. Only numbers");?>!
</p>
<p>
	<label><?php echo _("Currency");?>:</label>
	<input type="text" name="CURRENCY" value="<?php echo CURRENCY;?>" lang="false" class="text-small" /><?php echo _("Price currency");?>
</p>
<p>
	<label><?php echo _("Currency Format");?>:</label>
	<input type="text" name="CURRENCY_FORMAT" value="<?php echo CURRENCY_FORMAT;?>" lang="false" class="text-long" /><?php echo _("Format to display price");?>. <?php echo _("For example");?>: CURRENCYAMOUNT
</p>
<p>
	<label><?php echo _("Type Offer");?>:</label>
	<input type="text" name="TYPE_OFFER" value="<?php echo TYPE_OFFER;?>" lang="false" class="text-small" /><?php echo _("Offer type value on database");?>
</p>
<p>
	<label><?php echo _("Type Need");?>:</label>
	<input type="text" name="TYPE_NEED" value="<?php echo TYPE_NEED;?>" lang="false" class="text-small" /><?php echo _("Need type value on database");?>
</p>
<p>
	<label><?php echo _("Location");?>:</label>
	<select name="LOCATION" >
	<option <?php if(LOCATION)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!LOCATION)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Enables location features");?>
</p>
<p>
	<label><?php echo _("Location Root");?>:</label>
	<input type="text" name="LOCATION_ROOT" value="<?php echo LOCATION_ROOT;?>" class="text-long" /><?php echo _("The root of all location. For example: Country name");?>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('look');"><h3><?php echo _("Look and Feel");?></h3></div>
<div class="settingsTable" id="look">
<fieldset>
<p>
	<label><?php echo _("Friendly URL's");?>:</label>
	<select name="FRIENDLY_URL" >
	<option <?php if(FRIENDLY_URL)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!FRIENDLY_URL)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Disabled does not use the .htaccess, and the URLS will not look SEO friendly");?>
</p>
<p>
	<label><?php echo _("Default Theme");?>:</label>
	<select name="DEFAULT_THEME" >
	<option value="<?php echo DEFAULT_THEME;?>"><?php echo DEFAULT_THEME;?></option>
	<?php
	$themes = scandir("../themes");
	foreach ($themes as $theme) {
		if($theme!="" && $theme!=DEFAULT_THEME && $theme!="." && $theme!=".." && $theme!="wordcloud.css"){
			echo "<option value=\"$theme\">".$theme."</option>";
		}
	}
	?>
	</select><?php echo _("For more themes please go to");?> <a href="http://www.open-classifieds.com/">Open Classifieds</a>
</p>
<p>
	<label><?php echo _("Theme Selector");?>:</label>
	<select name="THEME_SELECTOR" >
	<option <?php if(THEME_SELECTOR)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!THEME_SELECTOR)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("If you enable this you allow the user to select theme");?>
</p>
<p>
	<label><?php echo _("Mobile Theme");?>:</label>
	<select name="THEME_MOBILE" >
	<option <?php if(THEME_MOBILE)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!THEME_MOBILE)  echo "selected=selected";?> >FALSE</option>
	</select> <?php echo _("Displays the mobile version of your site  to mobile devices if its enabled, (uses neoMobile theme)");?>
</p>
<p>
	<label><?php echo _("Items per Page");?>:</label>
	<input type="text" name="ITEMS_PER_PAGE" value="<?php echo ITEMS_PER_PAGE;?>" lang="false" class="text-small" /><?php echo _("Only numbers");?>!
</p>
<p>
	<label><?php echo _("Pages to display");?>:</label>
	<input type="text" name="DISPLAY_PAGES" value="<?php echo DISPLAY_PAGES;?>" lang="false" class="text-small" /><?php echo _("How many pages are displayed");?>. <?php echo _("Only numbers");?>!
</p>
<p>
	<label><?php echo _("HTML Editor");?>:</label>
	<select name="HTML_EDITOR" >
	<option <?php if(HTML_EDITOR)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!HTML_EDITOR)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Disable nicEdit HTML editor in the post");?>
</p>
<p>
	<label><?php echo _("HTML Separator");?>:</label>
<input type="text" name="SEPARATOR" value="<?php echo SEPARATOR;?>" lang="false" class="text-small" /><?php echo _("Separator used in a few places");?>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('advert');"><h3><?php echo _("Adsense and Advertising");?></h3></div>
<div class="settingsTable" id="advert">
<fieldset>
<p>
	<?php echo _("In this section you can paste your code of AdSense or any other provider and it will show in the website");?>
</p>
<p>
	<label><?php echo _("Top Advertisement");?>:</label>
	<textarea  name="ADVERT_TOP"  rows=3 cols=55><?php echo ADVERT_TOP;?></textarea>
    <?php echo _("HTML advertisement that appears in the top of the website");?>
</p>
<p>
	<label><?php echo _("Widget Advertisement");?>:</label>
	<textarea  name="ADVERT_SIDEBAR"  rows=3 cols=55><?php echo ADVERT_SIDEBAR;?></textarea>
	<?php echo _("HTML advertisement that appears in the sidebar");?>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('sidebarconf');"><h3><?php echo _("Sidebar");?></h3></div>
<input type="hidden" name="SIDEBAR_ORIG" value="<?php echo SIDEBAR_ORIG;?>" />
<div class="settingsTable" id="sidebarconf">
<fieldset>
<p>
	<table>
    <tr>
    <td>
        <strong><?php echo _("Available Widgets");?></strong><br />
		<select id="available" size="15" multiple="multiple" style="width: 170px;">
		<?php 
		$default_sidebar=explode(",",SIDEBAR_ORIG);
		$sidebar=explode(",",SIDEBAR);
		foreach($default_sidebar as $widget){
			if (!in_array($widget, $sidebar)) echo '<option>'.$widget.'</option>';
		}
		?>
		</select>
    </td>
    <td valign="center">
        <input type="button"  value="&lt;" onclick="swapElement('selected','available')" /><br />
		<input type="button" value="&gt;" onclick="swapElement('available','selected')"  /><br />
    </td>
    <td>
        <strong><?php echo _("Your Sidebar");?></strong><br />
		<select id="selected" name="SIDEBAR[]" size="15" multiple="multiple" style="width: 170px;">
		<?php 
		foreach($sidebar as $widget){
			echo '<option>'.$widget.'</option>';
		}
		?>
		</select>
		<br />
		<input type="button"  value="UP" onclick=" moveUp('selected')" />
		<input type="button"  value="DOWN" onclick="moveDown('selected')" />
    </td>
    </tr>
	</table>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('imgconf');"><h3><?php echo _("Image Settings");?></h3></div>
<div class="settingsTable" id="imgconf">
<fieldset>
<p>
	<label><?php echo _("Number of Images");?>:</label>
	<input type="text" name="MAX_IMG_NUM" value="<?php echo MAX_IMG_NUM;?>" lang="false" class="text-small" /><?php echo _("Number of images that can be posted, 0 disable all the images");?>
</p>
<p>
	<label><?php echo _("Image Size");?>:</label>
	<input type="text" name="MAX_IMG_SIZE" value="<?php echo MAX_IMG_SIZE;?>" lang="false" class="text-long" />b <?php echo _("Max image size allowed");?>
</p>
<p>
	<label><?php echo _("Image Folder");?>:</label>
	<input type="text" name="IMG_UPLOAD" value="<?php echo IMG_UPLOAD;?>" lang="false" class="text-long" /><?php echo _("Image upload directory name");?>
</p>
<p>
	<label><?php echo _("Image Full Path");?>:</label>
	<input type="text" name="IMG_UPLOAD_DIR" value="<?php echo IMG_UPLOAD_DIR;?>" lang="false" class="text-long" /><?php echo _("Full path where the images will be stored");?>
</p>
<p>
	<label><?php echo _("Image Types");?>:</label>
	<input type="text" name="IMG_TYPES" value="<?php echo IMG_TYPES;?>" lang="false" class="text-long" /><?php echo _("Type of images allowed, separated by comma");?>
</p>
<p>
	<label><?php echo _("Image Resize");?>:</label>
	<input type="text" name="IMG_RESIZE" value="<?php echo IMG_RESIZE;?>" lang="false" class="text-small" />px <?php echo _("Size of the images uploaded");?>
</p>
<p>
	<label><?php echo _("Thumbs Resize");?>:</label>
	<input type="text" name="IMG_RESIZE_THUMB" value="<?php echo IMG_RESIZE_THUMB;?>" lang="false" class="text-small" />px <?php echo _("Size of the thumbs generated");?>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('Sitemap');"><h3><?php echo _("Rss & Sitemap");?></h3></div>
<div class="settingsTable" id="Sitemap">
<fieldset>
<p>
	<label><?php echo _("RSS Items");?>:</label>
	<input type="text" name="RSS_ITEMS" value="<?php echo RSS_ITEMS;?>" lang="false" class="text-small" /><?php echo _("Number of items to display in RSS");?>
</p>
<p>
	<label><?php echo _("RSS Images");?>:</label>
	<select name="RSS_IMAGES" >
	<option <?php if(RSS_IMAGES)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!RSS_IMAGES)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Display images in RSS");?>
</p>
<p>
	<label><?php echo _("Sitemap File Path");?>:</label>
	<input type="text" name="SITEMAP_FILE" value="<?php echo SITEMAP_FILE;?>" lang="false" class="text-long" /><?php echo _("Path for the sitemap");?>
</p>
<p>
	<label><?php echo _("Sitemap Expires");?>:</label>
	<input type="text" name="SITEMAP_EXPIRE" value="<?php echo SITEMAP_EXPIRE;?>" lang="false" class="text-long" />seconds <?php echo _("Generates new sitemap after expire");?>
</p>
<p>
	<label><?php echo _("Sitemap deleted on post");?>:</label>
	<select name="SITEMAP_DEL_ON_POST" >
	<option <?php if(SITEMAP_DEL_ON_POST)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!SITEMAP_DEL_ON_POST)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("On new post generate new sitemap");?>
</p>
<p>
	<label><?php echo _("Sitemap on category");?>:</label>
	<select name="SITEMAP_DEL_ON_CAT" >
	<option <?php if(SITEMAP_DEL_ON_CAT)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!SITEMAP_DEL_ON_CAT)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("On new/updated category generate new sitemap");?>
</p>
<p>
	<label><?php echo _("Sitemap ping to Google");?>:</label>
	<select name="SITEMAP_PING" >
	<option <?php if(SITEMAP_PING)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!SITEMAP_PING)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("On any update will ping Google about the changes, before activate register your site at");?> <a href="http://www.google.com/webmasters/tools/"><?php echo _("Google Webmaster Tools");?></a>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('cache');"><h3><?php echo _("Cache Configuration");?></h3></div>
<div class="settingsTable" id="cache">
<fieldset>
<p>
	<label><?php echo _("Cache Active");?>:</label>
	<select name="CACHE_ACTIVE" >
	<option <?php if(CACHE_ACTIVE)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!CACHE_ACTIVE)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Sets on/off the cache system");?>
</p>
<p>
	<label><?php echo _("Cache File Path");?>:</label>
	<input type="text" name="CACHE_DATA_FILE" value="<?php echo CACHE_DATA_FILE;?>" lang="false" class="text-long" /><?php echo _("Path for the cache");?>
</p>
<p>
	<label><?php echo _("Cache Expires");?>:</label>
	<input type="text" name="CACHE_EXPIRE" value="<?php echo CACHE_EXPIRE;?>" lang="false" class="text-long" />seconds
</p>
<p>
	<label><?php echo _("Cache deleted on post");?>:</label>
	<select name="CACHE_DEL_ON_POST" >
	<option <?php if(CACHE_DEL_ON_POST)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!CACHE_DEL_ON_POST)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("On new post generate deletes the cache");?>
</p>
<p>
	<label><?php echo _("Cache deleted on category");?>:</label>
	<select name="CACHE_DEL_ON_CAT" >
	<option <?php if(CACHE_DEL_ON_CAT)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!CACHE_DEL_ON_CAT)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("On new/updated category deletes the cache");?>
</p>
</fieldset>
</div>

<div class="settingsTitle" onclick="openClose('etools');"><h3><?php echo _("External Tools");?></h3></div>
<div class="settingsTable" id="etools">
<fieldset>
<p>
	<label><a href="http://www.google.com/analytics/">Google Analytics</a>:</label>
	<input type="text" name="ANALYTICS" value="<?php echo ANALYTICS;?>" class="text-long" /><?php echo _("Code in the footer for tracking, for example: UA-4562297-13. Empty to disable");?>
</p>
<p>
	<label><a href="http://www.twitter.com/">Twitter</a>:</label>
	<input type="text" name="TWITTER" value="<?php echo TWITTER;?>" class="text-long" /><?php echo _("User Name, any new post would be tweeted with this account");?>
</p>
<p>
	<label>Twitter <?php echo _("Password");?>:</label>
	<input type="password" name="TWITTER_PWD" value="<?php echo TWITTER_PWD;?>" class="text-long" /><?php echo _("Password of your account");?>
</p>
<p>
	<label><a href="http://j.mp">Bit.ly/J.mp</a>:</label>
	<input type="text" name="BIT_USER" value="<?php echo BIT_USER;?>" class="text-long" /><?php echo _("User Name, short URL for");?> Twitter
</p>
<p>
	<label>Bit.ly/j.mp API:</label>
	<input type="text" name="BIT_API" value="<?php echo BIT_API;?>" class="text-long" /><?php echo _("API for your account");?>
</p>
<p>
	<label><a href="http://wordpress.com/api-keys/">Akismet KEY</a>:</label>
	<input type="text" name="AKISMET" value="<?php echo AKISMET;?>" class="text-long" /><?php echo _("Prevent spam");?>
</p>
<p>
	<label><a href="http://code.google.com/apis/maps/signup.html">Google Maps KEY</a>:</label>
	<input type="text" name="MAP_KEY" value="<?php echo MAP_KEY;?>" class="text-long" /><?php echo _("Displays Google Maps with posts");?>
</p>
<p>
	<label><?php echo _("Center Maps at");?>:</label>
	<input type="text" name="MAP_INI_POINT" value="<?php echo MAP_INI_POINT;?>" class="text-long" /><?php echo _("Map would be centered in this address");?>. <?php echo _("For example");?>: Barcelona, Spain
</p>
<p>
	<label><?php echo _("Comments");?>:</label>
	<input type="text" name="DISQUS" value="<?php echo DISQUS;?>" class="text-long" /><?php echo _("Account Name enables comments for posts threads with");?> <a href="http://disqus.com/comments/register/">Disqus</a>.
</p>
<p>
	<label><?php echo _("Video");?>:</label>
	<select name="VIDEO" >
	<option <?php if(VIDEO)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!VIDEO)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Allows YouTube videos on posts using [youtube=URLVIDEO]");?>
</p>
<p>
	<label><a href="http://ocaku.com/">Ocaku</a>:</label>
	<select name="OCAKU" >
	<option <?php if(OCAKU)  echo "selected=selected";?> >TRUE</option>
	<option <?php if(!OCAKU)  echo "selected=selected";?> >FALSE</option>
	</select><?php echo _("Enable/Disable Ocaku Classifieds Community");?>
</p>
<p>
	<label><a href="http://api.ocaku.com/">Ocaku KEY</a>:</label>
	<input name="OCAKU_KEY" value="<?php echo OCAKU_KEY;?>" class="text-long" />
    <?php echo _("API key to use Ocaku. Do not lose this! if you reinstall copy paste here the key.");?> 
    <a href="rememberKEY.php" target="_blank"> REMEMBER KEY</a>
</p>
</fieldset>
</div>
<input type="submit" name="submit" id="submit" value="<?php echo _("Save Settings");?>" />
</form>
<?php
require_once('footer.php');
?>