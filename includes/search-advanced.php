<?php
////////////////////////////////////////////////////////////
function advancedSearchForm($admin=0){//used in  /search and in index.php when an advanced search is done
	global $currentCategory;
	if ($admin==1) $action=SITE_URL.'/admin/listing.php';
	else $action=SITE_URL;
	?>	
	<form action="<?php echo $action;?>" method="get"><table cellpadding="2" cellspacing="0">
	<tr><td><?php echo _("Subject");?>:</td><td> 
	<?php 
	$query="SELECT friendlyName,description,(select description from ".TABLE_PREFIX."categories where idCategory=C.idCategoryParent) FROM ".TABLE_PREFIX."categories C";
	sqlOptionGroup($query,"category",$currentCategory);
	?></td></tr>
	<tr><td><?php echo _("Type");?>:</td><td>
		<select id="type" name="type">
			<option value="<?php echo TYPE_OFFER;?>"><?php echo _("offer");?></option>
			<option value="<?php echo TYPE_NEED;?>"><?php echo _("need");?></option>
		</select>
	</td></tr>
    <?php if (LOCATION){?>
	<tr><td><?php echo _("Location");?>:</td><td>
    	<?php 
    	global $location;
        $query="SELECT idLocation,name,(select name from ".TABLE_PREFIX."locations where idLocation=C.idLocationParent) FROM ".TABLE_PREFIX."locations C order by idLocation, idLocationParent";
    	echo sqlOptionGroup($query,"location",$location);
    	?>
	</td></tr>
    <?php }?>  
    <tr><td><?php echo _("Course Number");?>:</td><td><input type="text" name="coursenumber" value="<?php echo cG("coursenumber");?>" /> (eliminate spaces)</td></tr>
    <tr><td><?php echo _("Course Name");?>:</td><td><input type="text" name="coursename" value="<?php echo cG("coursename");?>" /></td></tr> 
	<tr><td><?php echo _("Book Title");?>:</td><td><input type="text" name="title" value="<?php echo cG("title");?>" /></td></tr>
	<tr><td><?php echo _("Author(s)");?>:</td><td><input type="text" name="author" value="<?php echo cG("author");?>" /></td></tr>
	<tr><td><?php echo _("ISBN");?>:</td><td><input type="text" name="isbn" value="<?php echo cG("isbn");?>" /> (10 or 13 digits, exclude dashes)</td></tr> 
	<tr><td><?php echo _("Description");?>:</td><td><input type="text" name="desc" value="<?php echo cG("desc");?>" /></td></tr>
	<tr><td><?php echo _("Sort");?>:</td>
		<td>
			<select name="sort">
				<option></option>
				<option value="price-desc" <?php if(cG("sort")=="price-desc")  echo "selected=selected";?> ><?php echo _("Price");?> - <?php echo _("High to Low");?></option>
				<option value="price-asc" <?php if(cG("sort")=="price-asc")  echo "selected=selected";?> ><?php echo _("Price");?> - <?php echo _("Low to High");?></option>
			</select>
		</td></tr>
	<tr><td>&nbsp;</td><td><input type="submit" value="<?php echo _("Search");?>" /></td></tr>
	</table></form><?php 
}
////////////////////////////////////////////////////////////
?>
