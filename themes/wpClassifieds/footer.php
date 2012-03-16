</div>

<div class="grid_4" id="sidebar">
 
      <ul id="sidebar_widgeted">
      <?php getSideBar("<li class='widget widget_recent_entries'><div class='whitebox'>","</div></li>");?>
      </ul>
      
 </div>
    
    
    <div class="clear"></div>

<div class="grid_12" id="footer">
    <ul class="pages">
	    <?php if(FRIENDLY_URL) {?>
	 	     <li><a href="<?php echo SITE_URL;?>/content/faq.php"><?php echo _("FAQS");?></a></li>
		    <li><a href="<?php echo SITE_URL."/".u(_("Privacy Policy"));?>.htm"><?php echo _("Privacy Policy");?></a></li>
		    
		         <li><a href="<?php echo SITE_URL;?>/content/terms.php"><?php echo _("Terms of Use");?></a></li>
		          <li><a href="<?php echo SITE_URL;?>/content/acknowledgments.php"><?php echo _("Acknowledgments");?></a></li>
		     
	    <?php }else { ?>
	    	     <li><a href="<?php echo SITE_URL;?>/content/faq.php"><?php echo _("FAQS");?></a></li>
		         <li><a href="<?php echo SITE_URL;?>/content/terms.php"><?php echo _("Terms of Use");?></a></li>
		          <li><a href="<?php echo SITE_URL."/".u(_("Privacy Policy"));?>.htm"><?php echo _("Privacy Policy");?></a></li>
		          <li><a href="<?php echo SITE_URL;?>/content/acknowledgments.php"><?php echo _("Acknowledgments");?></a></li>
	    <?php } ?>
	        <li><a href="<?php echo SITE_URL."/".u(_("Advanced Search"));?>.htm"><?php echo _("Advanced Search");?></a></li>
	    <li><a href="<?php echo SITE_URL."/".contactURL();?>"><?php echo _("Contact");?></a></li>
	    <li><a href="<?php echo SITE_URL.newURL();?>"><?php echo _("Post a New Book");?></a></li>
	         	    <li><a href="<?php echo SITE_URL."/".u(_("Sitemap"));?>.htm"><?php echo _("Sitemap");?></a></li>  
	</ul>
    <p>
    <?php echo SITE_NAME;?> |
<!-- Open Classifieds License. To remove please visit http://open-classifieds.com/services/  -->
Powered by <a title="free open source php classifieds script" href="http://www.open-classifieds.com">Open Classifieds</a>
<!--End Open Classifieds License-->
  </div>
  <div class="clear"></div>

  </div>
</div>
