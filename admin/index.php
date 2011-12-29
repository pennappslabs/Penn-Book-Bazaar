<?php
require_once('access.php');
require_once('header.php');
?>
<h2><?php echo _("Administration");?></h2>
<div class="form-tab"><?php echo _("Quick View");?></div>
<div class="clear"></div>
<div class="dashboard">
    <ul>
    <li><?php echo _("Version");?>: <?php echo VERSION;?></li>
    <li><?php echo _("Language");?>: <?php echo LANGUAGE;?></li>
    <li><?php echo _("Theme");?>: <?php echo THEME;?></li>
    <li><?php echo _("Total Ads").': '.totalAds();?>
    <li><?php echo _("Total Views").': '.totalViews();?></li>
    </ul>
</div>
<?php
   echo '<br /><b><a href="http://open-classifieds.com/blog/" target="_blank">'._("Blog Updates").':</a></b><ul>'.rssReader('http://feeds.feedburner.com/OpenClassifieds',5,CACHE_ACTIVE,'<li>','</li>').'</ul>';
   echo '<br /><b><a href="http://open-classifieds.com/forum" target="_blank">'._("Support Forum").':</a></b><ul>'.rssReader('http://feeds.feedburner.com/Forum-OpenClassifiedsRecentTopics',5,CACHE_ACTIVE,'<li>','</li>').'</ul>';
?>
<?php
require_once('footer.php');
?>