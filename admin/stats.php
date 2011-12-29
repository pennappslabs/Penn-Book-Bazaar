<?php
require_once('access.php');
require_once('header.php');
?>
<h2><?php echo _("Site Usage Statistics");?></h2>
<blockquote>
<b><?php echo _("Ads Views");?></b><br />
<?php echo _("Yesterday");?>: <?php echo totalViews("all",1);?><br />
<?php echo _("Last week");?>: <?php echo totalViews("all",8);?><br />
<?php echo _("Last month");?>: <?php echo totalViews("all",30);?><br />
<?php echo _("Total");?>: <?php echo totalViews();?><br />
</blockquote>
<blockquote>
<b><?php echo _("Ads");?></b><br />
<?php echo _("Yesterday");?>: <?php echo totalAds("all",1);?><br />
<?php echo _("Last week");?>: <?php echo totalAds("all",8);?><br />
<?php echo _("Last month");?>: <?php echo totalAds("all",30);?><br />
<?php echo _("Total Ads").': '.totalAds();?><br />
</blockquote>
<?php
require_once('footer.php');
?>