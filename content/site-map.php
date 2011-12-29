<?php
require_once('../includes/header.php');
?>
<h3><?php echo SITE_NAME.' '._("Sitemap");?>:</h3>
<h4><?php echo _("Categories");?></h4>
<ul>
<?php
	foreach($resultSitemap as $row){
		 echo '<li><a title="'.htmlentities($row['description'], ENT_QUOTES, CHARSET).'" href="'.SITE_URL.catURL($row['friendlyName'],$row['parent']).'">'.$row['name'].'</a></li>';
	}
?>
</ul>
<br	/>
<h4><?php echo _("Links");?>:</h4>
<ul>
    <?php if(FRIENDLY_URL) {?>
	    <li><a href="<?php echo SITE_URL."/".u(_("Advanced Search"));?>.htm"><?php echo _("Advanced Search");?></a></li>
	    <li><a href="<?php echo SITE_URL."/".u(_("Sitemap"));?>.htm"><?php echo _("Sitemap");?></a></li>
	    <li><a href="<?php echo SITE_URL."/".u(_("Privacy Policy"));?>.htm"><?php echo _("Privacy Policy");?></a></li>
    <?php }else { ?>
        <li><a href="<?php echo SITE_URL;?>/content/search.php"><?php echo _("Advanced Search");?></a></li>
        <li><a href="<?php echo SITE_URL;?>/content/site-map.php"><?php echo _("Sitemap");?></a></li>
	    <li><a href="<?php echo SITE_URL;?>/content/privacy.php"><?php echo _("Privacy Policy");?></a></li>
    <?php } ?>
    <li><a href="<?php echo SITE_URL."/".contactURL();?>"><?php echo _("Contact");?></a></li>
    <li><a href="<?php echo SITE_URL."/".contactURL()."?subject="._("Suggest new category");?>"><?php echo _("Suggest new category");?></a></li>
    <li><a href="<?php echo SITE_URL.newURL();?>"><?php echo _("Publish a new Ad");?></a></li>
	<li><a href="<?php echo SITE_URL;?>/admin/"><?php echo _("Administrator");?></a></li>
</ul>
<?php
require_once('../includes/footer.php');
?>