<?php
require_once('access.php');
require_once('header.php');
?>
<h2><?php echo _("Sitemap Generator");?></h2>
<p>
<?php echo _("Click");?> <a href="admin_sitemap.php?action=renew" onClick="return confirm('<?php echo _("Are you sure");?>?');"><?php echo _("Sitemap");?> <?php echo round((time()-filemtime(SITEMAP_FILE))/60,1);?> <?php echo _("minutes");?></a>
</p>
<?php 
if (cG("action")=="renew") {
	$sitemap=generateSitemap();
	echo "<br/><textarea cols=60 rows=30>$sitemap</textarea>";
}
?>
<p>
<a target="_blank" href="<?php echo SITE_URL;?>/sitemap.xml.gz"><?php echo _("Open Sitemap");?></a>
</p>
<?php
require_once('footer.php');
?>
