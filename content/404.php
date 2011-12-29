<?php
require_once('../includes/header.php');
echo "<h1>".u($_SERVER["REQUEST_URI"])."</h1>"._("Nothing found");
?>
<br /> 
<br /> 
<b><?php echo _("Advanced Search");?></b>
<div class="item">
<?php advancedSearchForm();?>
</div>
<?php
require_once('../includes/footer.php');
?>