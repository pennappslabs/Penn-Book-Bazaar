<?php if (LOGON_TO_POST){
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
<script type="text/javascript" src="<?php echo SITE_URL;?>/includes/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>/includes/greybox/gb_scripts.js"></script>
      <div class="single_area">
		 <div style=" float:right;">
		 <?php if ($itemEmail == $email){
      	  echo '<a href="'.SITE_URL.'/manage/?post='.$itemid.'&amp;pwid='.$itemPassword.'&amp;action=edit" target="_blank">'._("Edit").'</a>'.SEPARATOR.'';
        	echo '<a href="'.SITE_URL.'/manage/?post='.$itemid.'&amp;pwid='.$itemPassword.'&amp;action=delete" target="_blank">'._("Delete").'</a>';
      	}
      	?>
      	</div>
		<h1><?php echo $itemTitle ?> (<?php echo getTypeName($itemType) ?>)</h1>
		 
    	 <div class="book">
   
            <b><?php echo _("Posted on");?>:</b> <?php echo setDate($itemDate);?> <?php echo _("<br />");?>
            <b><?php echo _("Contact"); echo ": </b>".$itemName; ?>
            
     <!--       <?php echo "(".$itemEmail.")"; ?> <br/> -->
            <br/><b>Course</b>: <?php echo $itemCoursenumber ?> <?php echo $itemCoursename ?> <br/>
            <b>Book</b>: <?php echo $itemTitle ?> <br/>
            <?php if ($itemAuthor!=""){ ?> <b>Author(s)</b>: <?php echo $itemAuthor ?> <?php }?> <br/>
           <p id="<?php echo $itemISBN?>"><b>ISBN</b>: <?php echo $itemISBN ?> </p></div>
            
            <script>
      // Construct URL along with required ISBNs
      var isbns = ["<?php echo $itemISBN ?>"];
      // Note: server name should be replaced with prod server.
      var api_url ="http://books.google.com/books?jscmd=viewapi&bibkeys=" +
      isbns.join(",");

      // Talk to the server synchronously and get _GBSBookInfo object
      document.write(unescape("%3Cscript src=" + api_url +
        " type='text/javascript'%3E%3C/script%3E"));

    </script>
    <script>
       var buttonImg =
        'http://code.google.com/apis/books/images/gbs_preview_button1.gif';
      
      // Process response from Google booksearch
      for (i in isbns) {
        var element = document.getElementById(isbns[i]);
        var bookInfo = _GBSBookInfo[isbns[i]];

        // Check whether  server returned any data
        if ( bookInfo == null || element == null ) continue;

        // Linkify the title 
        element.innerHTML = "<a href=\"" + bookInfo.info_url  + "\">"
                            + element.innerHTML + "</a>";
      
        // Google Preview Button HTML
        var previewButtonHTML = "";
        if (bookInfo.preview == "full" || bookInfo.preview == "partial") {
          previewButtonHTML = "<p class=\"gbs_preview_button\">"
            + "<a href=\"" + bookInfo.preview_url + "\">"
            + "<img src=\"" + buttonImg + "\" border=\"0\"/></a></p>";
        }

        // Thumnnail HTML
        var thumbnailHTML = "";
        if (bookInfo.thumbnail_url) {
          var thumbnailHTML = "<img class=\"gbs_thumb_img\" "
            + " border=0 src=\"" + bookInfo.thumbnail_url + "\"/>"
        }

        // Sew it up
        element.parentNode.innerHTML = thumbnailHTML
          + element.parentNode.innerHTML
          + previewButtonHTML 
          + "<div style=\"clear: both;\"></div>";
     }
    </script>
 
		    
		    <!-- <?php echo SEPARATOR;?><?php if (COUNT_POSTS) echo "$itemViews "._("views").SEPARATOR;?> --!>
<!-- 
		    <?php if (DISQUS!=""){ ?><a href="<?php echo $_SERVER["REQUEST_URI"];?>#disqus_thread">Comments</a><?php echo SEPARATOR;?> <?php }?>
 -->
  <!-- 

        <?php if (MAX_IMG_NUM>0){?>
		<div id="pictures">
			<?php 
			foreach($itemImages as $img){
				echo '<a href="'.$img[0].'" title="'.$itemTitle.' '._('Picture').'" rel="gb_imageset['.$idItem.']">
				 		<img class="thumb" src="'.$img[1].'" title="'.$itemTitle.' '._('Picture').'" alt="'.$itemTitle.' '._('Picture').'" /></a>';
			}
			?>
			<div class="clear"></div>
		</div>
	    <?php }?>
 -->
 
		<b>Book Description</b>: <?php echo $itemDescription;?>
		<p><?if ($itemPrice!=0) echo "<strong>Price:</strong> ".getPrice($itemPrice);?></p>
		
		<div>
		
		
        <!-- AddThis Button BEGIN -->
        <div class="addthis_toolbox addthis_default_style">
        <a href="http://www.addthis.com/bookmark.php?v=250" class="addthis_button_compact"><?php echo _("Share");?></a>
        <a class="addthis_button_facebook"></a>
        <a class="addthis_button_twitter"></a>
        <a class="addthis_button_print"></a>
        <a class="addthis_button_email"></a>
        <?php echo SEPARATOR;?><a href="<?php echo SITE_URL."/".contactURL();?>?subject=<?php echo _("Report bad use or Spam");?>: <?php echo $itemName." (".$idItem.")";?>"><?php echo _("Report bad use or Spam");?></a>
        </div>
        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
        <!-- AddThis Button END -->
        
     
	</div>
  </div>
  <br />
  <?php if ($itemAvailable==1){?>
	<h3><?php echo _("Contact");?> <?php echo $itemName.': '.$itemTitle;?></h3>
	<div id="contactmail" class="contactform form" >
		<!-- <?php if ($itemPhone!=""){?><b><?php echo _("Phone");?>:</b> <?php echo encode_str($itemPhone); ?><?php }?> --!>
		<form method="post" action="" id="contactItem" onsubmit="return checkForm(this);">
		 <input id="name" name="name" type="hidden" value="<?php echo $name;?>" /> 
    <input id="email" name="email" type="hidden" value="<?php echo $email;?>" />
		
		
		<p>
            <label><small><strong>MESSAGE</strong></small></label><br />
            Your message will be delivered to <?php echo $itemName ?>'s Penn email account.
		    <textarea rows="10" cols="79" name="msg" id="msg" onblur="validateText(this);"  lang="false"><?php echo str_replace("\r\n","<br/>",$_POST['msg']);?></textarea><br />
		</p>
		<p>
            <label><small><?php  mathCaptcha();?></small></label>
		    <input id="math" name="math" type="text" size="2" maxlength="2"  onblur="validateNumber(this);"  onkeypress="return isNumberKey(event);" lang="false" />
            <br />
            <br />
		</p>
        <p>
		<input type="hidden" name="contact" value="1" />
		<input type="submit" id="submit" value="<?php echo _("Contact");?>" />
		</p>
		</form> 
	</div>
	<?php } else echo "<div id='sysmessage'>"._("This Ad is no longer available")."</div>";?>

	<span style="cursor:pointer;" onclick="openClose('remembermail');"> <?php echo _("Send me an email with links to manage my Ad");?></span><br />
	<div style="display:none;" id="remembermail" >
		<form method="post" action="" id="remember" onsubmit="return checkForm(this);">
		<p>
        	<input type="hidden" name="remember" value="1" />
		<input onblur="this.value=(this.value=='') ? 'email' : this.value;" 
				onfocus="this.value=(this.value=='email') ? '' : this.value;" 
		id="emailR" name="emailR" type="text" value="email" maxlength="120" onblur="validateEmail(this);" lang="false"  />
			<input type="submit"  value="<?php echo _("Remember");?>" />
        </p>
		</form> 
	</div>
	<?php if (DISQUS!=""){ ?>
		<?php if (DEBUG){ ?><script type="text/javascript"> var disqus_developer = 1;</script><?php } ?>
	
	<div id="disqus_thread"></div><script type="text/javascript" src="http://disqus.com/forums/<?php echo DISQUS;?>/embed.js"></script>
	<noscript><a href="http://disqus.com/forums/<?php echo DISQUS;?>/?url=ref">View the discussion thread.</a></noscript>
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
