<?php
require_once('includes/header.php');
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
			else echo "<div id='sysmessage'>"._("Wrong email address")."</div>";//Wrong email address
		}
		else echo "<div id='sysmessage'>"._("Wrong Captcha")."</div>";//wrong captcha
	}
	//remember form
	
	if (cP("remember")==1&&cP("emailR")==$itemEmail){
		//generate the email to send to the client for remember
					$subject=_("Remember")." ".html_entity_decode($itemTitle, ENT_QUOTES, CHARSET).SEPARATOR. SITE_NAME;

                    $message="<p>"._("To edit the post click here").": 
							 ".SITE_URL."/manage/?post=$idItem&pwd=$itemPassword&action=edit</p>" ;
                    
                    $array_content[]=array("ACCOUNT", _("User"));
                    $array_content[]=array("MESSAGE", $message);
                    
                    $bodyHTML=buildEmailBodyHTML($array_content);
                    
					sendEmailComplete($itemEmail,$subject,$bodyHTML,"","");
					//for preventing f5
					$_SESSION["mathCaptcha"]="";
					echo "<div id='sysmessage'>"._("Message sent, thank you").".</div>";//
	}
	

if (file_exists(SITE_ROOT.'/themes/'.THEME.'/item.php')){//itemfrom the theme!
	require_once(SITE_ROOT.'/themes/'.THEME.'/item.php'); 
}
else{//default not found in theme
?>
<script type="text/javascript" src="<?php echo SITE_URL;?>/includes/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>/includes/greybox/gb_scripts.js"></script>
<div class="item">
	<div class="item">
		<h1><a title="<?php echo $itemCoursenumber; ?>" href="<?php echo $_SERVER["REQUEST_URI"];?>">
			<?php echo $itemTitle; ?> <?if ($itemPrice!=0) echo " - ".getPrice($itemPrice);?></a>
		</h1>
	</div>
	<div class="item">
	<p>
		<b><?php echo _("Publish Date");?>:</b> <?php echo setDate($itemDate);?> <?php echo substr($itemDate,strlen($itemDate)-8);?><?php echo SEPARATOR;?>
        <b><?php echo _("Contact name");?>:</b> 
        <?php
        $account=new Account($itemEmail);
        if ($account->exists){ ?>
        <a href="<?php echo SITE_URL."/".accountPostsURL($itemType,$currentCategory,$itemEmail);?>" target="_blank"><?php echo $itemName; ?></a>
        <?php 
        } else {
            echo $itemName;
        } ?>
        <?php echo SEPARATOR;?>
        <?php if ($itemLocation!="0"){?>
        <b><?php echo _("Location");?>:</b> <?php echo getLocationName($itemLocation); ?><?php echo SEPARATOR;?>
        <?php }?>
		<?php if ($itemPlace!=""){?>
			<b><?php echo _("Place");?>:</b> 
			<?php if (MAP_KEY!=""){?>
				<a title="Map <?php echo $itemPlace;?>" href="<?php echo SITE_URL."/".mapURL()."?address=".$itemPlace;?>" rel="gb_page_center[640, 480]"><?php echo $itemPlace;?></a>
			<?php } else echo $itemPlace;?>
			<?php echo SEPARATOR;?> 
		<?php }?>
		<?php if (COUNT_POSTS) echo "$itemViews "._("views").SEPARATOR;?>
		<?php if (DISQUS!=""){ ?><a href="<?php echo $_SERVER["REQUEST_URI"];?>#disqus_thread"><?php echo _("Comments");?></a><?php echo SEPARATOR;?> <?php }?>
	</p>	
	</div>
	<?php if (MAX_IMG_NUM>0){?>
		<div id="item">
			<?php 
			foreach($itemImages as $img){
				echo '<a href="'.$img[0].'" title="'.$itemTitle.' '._("Picture").'" rel="gb_imageset['.$idItem.']">
				 		<img class="thumb" src="'.$img[1].'" title="'.$itemTitle.' '._("Picture").'" alt="'.$itemTitle.' '._("Picture").'" /></a>';
			}
			?>
		</div>
	<?php }?>
	<div class="item">	
		<?php echo $itemDescription;?>
		<br /><br />

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a href="http://www.addthis.com/bookmark.php?v=250" class="addthis_button_compact"><?php echo _("Share");?></a>
<a class="addthis_button_facebook"></a>
<a class="addthis_button_myspace"></a>
<a class="addthis_button_google"></a>
<a class="addthis_button_twitter"></a>
<a class="addthis_button_print"></a>
<a class="addthis_button_email"></a>
<a href="<?php echo SITE_URL."/".contactURL();?>?subject=<?php echo _("Report bad use or Spam");?>: <?php echo $itemName." (".$idItem.")";?>"><?php echo _("Report bad use or Spam");?></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<!-- AddThis Button END -->
	</div>
	<?php if ($itemAvailable==1){?>
	<h2 style="cursor:pointer;" onclick="openClose('contactmail');"><?php echo _("Contact");?> <?php echo $itemName.': '.$itemTitle;?></h2>
	<div id="contactmail">
		<?php if ($itemPhone!=""){?><b><?php echo _("Phone");?>:</b> <?php echo encode_str($itemPhone); ?><?php }?>
		<form method="post" action="" id="contactItem" onsubmit="return checkForm(this);">
		<p>
		<?php echo _("Your Name");?>*:<br />
		<input id="name" name="name" type="text" value="<?php echo cP("name");?>" maxlength="75" onblur="validateText(this);"  onkeypress="return isAlphaKey(event);" lang="false"  /><br />
		
		<?php echo _("Email");?>*:<br />
		<input id="email" name="email" type="text" value="<?php echo cP("email");?>" maxlength="120" onblur="validateEmail(this);" lang="false"  /><br />
		
		<?php echo _("Message");?>*:<br />
		<textarea rows="10" cols="79" name="msg" id="msg" onblur="validateText(this);"  lang="false"><?php echo strip_tags(stripslashes($_POST['msg']));?></textarea><br />
		
		<?php  mathCaptcha();?>
		<input id="math" name="math" type="text" size="2" maxlength="2"  onblur="validateNumber(this);"  onkeypress="return isNumberKey(event);" lang="false" />
		<br />
		<br />
		<input type="hidden" name="contact" value="1" />
		<input type="submit" id="submit" value="<?php echo _("Contact");?>" />
		</p>
		</form> 
	</div>
	<?php } else echo "<div id='sysmessage'>"._("This Ad is no longer available")."</div>";?>
	<br /><span style="cursor:pointer;" onclick="openClose('remembermail');"> <?php echo _("Reminder email with links to edit and deactivate");?></span><br />
	<div style="display:none;" id="remembermail" >
		<form method="post" action="" id="remember" onsubmit="return checkForm(this);">
		<input type="hidden" name="remember" value="1" />
		<input onblur="this.value=(this.value=='') ? 'email' : this.value;" 
				onfocus="this.value=(this.value=='email') ? '' : this.value;" 
		id="emailR" name="emailR" type="text" value="email" maxlength="120" onblur="validateEmail(this);" lang="false"  />
		<input type="submit"  value="<?php echo _("Remember");?>" />
		</form> 
	</div>
	<?php if (DISQUS!=""){ ?>
		<?php if (DEBUG){ ?><script type="text/javascript"> var disqus_developer = 1;</script><?php } ?>
	    <div id="disqus_thread"></div>
	    <script type="text/javascript">
          (function() {
           var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
           dsq.src = 'http://<?php echo DISQUS;?>.disqus.com/embed.js';
           (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
          })();
        </script>
	    <script type="text/javascript">
	    //<![CDATA[
	    (function() {
		    var links = document.getElementsByTagName('a');
		    var query = '?';
		    for(var i = 0; i < links.length; i++) {
		    if(links[i].href.indexOf('#disqus_thread') >= 0) {
			    query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
		    }
		    }
		    document.write('<script charset="utf-8" type="text/javascript" src="http://disqus.com/forums/<?php echo DISQUS;?>/get_num_replies.js' + query + '"></' + 'script>');
	    })();
	    //]]>
	    </script>
	<?php } ?>
</div>
<?php
	}//else theme
}
else jsRedirect(SITE_URL); //if is numeric
require_once('includes/footer.php');
?>