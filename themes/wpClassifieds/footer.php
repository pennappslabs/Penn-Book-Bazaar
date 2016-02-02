  </div>
  
 <div class="grid_4" id="sidebar">
 
      <ul id="sidebar_widgeted">
      <?php getSideBar("<li class='widget widget_recent_entries'><div class='whitebox'>","</div></li>");?>
      </ul>
      
 </div>
    
    
    <div class="clear"></div> 

<div id="footer" style="padding-left: 0px">
    <ul style="" class="pages">
	    <?php if(FRIENDLY_URL) {?>
	 	        <li><a href="<?php echo SITE_URL;?>/content/faq.php"><?php echo _("Frequently Asked Questions");?></a></li>
                <li> | </li>
                <li><a href="<?php echo SITE_URL."/".u(_("Privacy Policy"));?>.htm"><?php echo _("Privacy Policy");?></a></li>
		        <li> | </li> 
		        <li><a href="<?php echo SITE_URL;?>/content/terms.php"><?php echo _("Terms of Use");?></a></li>
		        <li> | </li>
                <li><a href="<?php echo SITE_URL;?>/content/acknowledgments.php"><?php echo _("Acknowledgments");?></a></li>
		        <li> | </li> 
	    <?php }else { ?>
	    	    <li><a href="<?php echo SITE_URL;?>/content/faq.php"><?php echo _("Frequently Asked Questions");?></a></li>
                <li> | </li> 
                <li><a href="<?php echo SITE_URL;?>/content/terms.php"><?php echo _("Terms of Use");?></a></li>
                <li> | </li>
                <li><a href="<?php echo SITE_URL."/".u(_("Privacy Policy"));?>.htm"><?php echo _("Privacy Policy");?></a></li>
                <li> | </li>  
                <li><a href="<?php echo SITE_URL;?>/content/acknowledgments.php"><?php echo _("Acknowledgments");?></a></li>
                <li> | </li>
        <?php } ?>
	            <li><a href="<?php echo SITE_URL."/".u(_("Advanced Search"));?>.htm"><?php echo _("Advanced Search");?></a></li>
                <li> | </li>
                <li><a href="<?php echo SITE_URL."/".contactURL();?>"><?php echo _("Contact");?></a></li>
                <li> | </li>
                <li><a href="<?php echo SITE_URL.newURL();?>"><?php echo _("Post a New Book");?></a></li>
                <li> | </li>
                <li><a href="<?php echo SITE_URL."/".u(_("Sitemap"));?>.htm"><?php echo _("Sitemap");?></a></li>  
	</ul>
    <p>
<!-- Open Classifieds License. To remove please visit http://open-classifieds.com/services/  -->
<div style="text-align: right; font-size: 8px"><?php echo SITE_NAME;?> | Powered by <a style="font-size: 8px" title="free open source php classifieds script" href="http://www.open-classifieds.com">Open Classifieds</a></div>
<!--End Open Classifieds License-->
  </div>
  <div class="clear"></div>

  </div>
</div>
<!-- Uservoice widget-->
<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/pQjMHXYTGSr9HPmQDwA6w.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>
