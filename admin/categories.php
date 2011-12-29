<?php
require_once('access.php');
require_once('header.php');
?>
<script type="application/javascript">
	function newCategory(){
		d = document.category;
		d.cid.value = "";
		d.cname.value = "";
		d.corder.value = "";
		d.cparent.value = "";
		d.cdesc.value = "";
		d.action.value ="new";
		d.submitCat.value ="<?php echo _("New Category");?>";
		document.getElementById("form-tab").innerHTML ="<?php echo _("New Category");?>";
		show("formCat");
		location.href = "#formCat";
	}	
	function editCategory(cid, corder,cparent){
		d = document.category;
		d.cid.value = cid;
		d.cname.value = document.getElementById('name-'+cid).innerHTML;
		d.corder.value = corder;
		d.cparent.value = cparent;
		d.cdesc.value = document.getElementById('desc-'+cid).innerHTML;//cdesc;
		d.action.value ="edit";
		d.submitCat.value ="<?php echo _("Edit Category");?>";
		document.getElementById("form-tab").innerHTML ="<?php echo _("Edit Category");?>";
		show("formCat");
		location.href = "#formCat";
	}	
	function deleteCategory(category){
		if (confirm('<?php echo _("Delete Category");?> "'+document.getElementById('name-'+category).innerHTML+'"?'))
		window.location = "categories.php?action=delete&cid=" + category;
	}
</script>
<h2><?php echo _("Categories"); ?></h2>
<?php
function catSlug($name,$id=""){ //try to prevent duplicated categories
	global $ocdb;
	$name=friendly_url($name);

	if (is_numeric($id)) $query="SELECT friendlyName FROM ".TABLE_PREFIX."categories where (friendlyName='$name') and (idCategory <> $id) limit 1";
	else $query="SELECT friendlyName FROM ".TABLE_PREFIX."categories where (friendlyName='$name') limit 1";
	$res=$ocdb->getValue($query,"none");

	if ($res!=false){//exists try adding with parent id
		$name.='-'.cP("cparent");  
		$res=$ocdb->getValue("SELECT friendlyName FROM ".TABLE_PREFIX."locations where friendlyName='$name' limit 1","none");//now with the new cat name
	}

	if ($res==false) return $name;
	else return false;	
}

//actions
if (cP("action")!=""||cG("action")!=""){
	$action=cG("action");
	if ($action=="")$action=cP("action");

	if ($action=="new"){
		$nameSlug=catSlug(cP("cname"));
		if ($nameSlug!=false){  //no exists insert
			$ocdb->insert(TABLE_PREFIX."categories (name,friendlyName,`order`,idCategoryParent,description)",
			"'".cP("cname")."','$nameSlug',".cP("corder").",".cP("cparent").",'".cP("cdesc")."'");
		}
		else echo _("Category already exists");

	}
	elseif ($action=="delete"){
		$ocdb->delete(TABLE_PREFIX."categories","idCategory=".cG("cid"));
		//echo "Deleted";
	}
	elseif ($action=="edit"){
		$nameSlug=catSlug(cP("cname"),cP("cid"));
		if ($nameSlug!=false){  //no exists update
			$query="update ".TABLE_PREFIX."categories set name='".cP("cname")."',friendlyName='$nameSlug'
					,`order`=".cP("corder").",idCategoryParent=".cP("cparent").",description='".cP("cdesc")."' 
					where idCategory=".cP("cid");
			$ocdb->query($query);
		}
		else echo _("Category already exists");
		//echo "Edit: $query";
	}

	if (CACHE_DEL_ON_CAT) deleteCache();//delete cache on category if is activated
	if (SITEMAP_DEL_ON_CAT) generateSitemap();//new/update cat generate sitemap
}
?>
<p class="desc"><?php echo _("Manage your website categories");?></p>
<div id='formCat' style="display:none;">
	<div id="form-tab" class="form-tab"></div>
	<div class="clear"></div>
	<form name="category" action="categories.php" method="post" onsubmit="return checkForm(this);">
		<fieldset>
			<p>
				<label><?php echo _("Categories");?></label>
				<input name="cname" type="text" class="text-long" lang="false" onblur="validateText(this);" xml:lang="false" />
			</p>                          
			<p>
            	<label><?php echo _("Order");?></label>
                <input  name="corder" type="text" class="text-small" lang="false"  onblur="validateNumber(this);" onkeypress="return isNumberKey(event);" value="" maxlength="5" xml:lang="false" />
			</p>
			<p>
            	<label><?php echo _("Parent");?></label>
                <?php sqlOption("select idCategory,name from ".TABLE_PREFIX."categories where idCategoryParent=0","cparent","");?>
        	</p>
			<p>
            	<label><?php echo _("Description");?></label>
                <textarea rows="1" cols="1" name="cdesc" ></textarea>
        	</p>
			<input id="submitCat" type="submit" value="" class=" button-submit" />
			<input type="submit" value="<?php echo _("Cancel");?>" class="button-cancel" onclick="hide('formCat');return false;" />
			<input type="hidden" name="cid" value="" />
			<input type="hidden" name="action" value="" />
		</fieldset>
	</form>
</div>
<div class="add_link"><a href="" onclick="newCategory();return false;"><?php echo _("New Category");?></a></div>
<table>
	<tr class="thead">
		<td><?php echo _("Categories");?></td>
		<td><?php echo _("Order");?></td>
		<td><?php echo _("Parent");?></td>
		<td>&nbsp;</td>
	</tr>
	<?php 
		$result = $ocdb->query("SELECT *,(select name from ".TABLE_PREFIX."categories  where idCategory=C.idCategoryParent) ParentName 
								FROM ".TABLE_PREFIX."categories C order by  idCategoryParent,`order`");
		$row_count = 0;
		while ($row = mysql_fetch_array($result)){
			$name = $row["name"] ;
			$desc = $row["description"];
			$order =  $row["order"];
			$idCategory=$row["idCategory"];
			$parent=$row["idCategoryParent"];
			$parentName=$row["ParentName"];
			if ($parentName=="") $parentName="Home";

			$row_count++;
			if ($row_count%2 == 0) $row_class = 'class="odd"';
			else $row_class = 'class="even"';

	?>
	<tr <?php echo $row_class;?>>      
		<td><?php echo $name;?></td>
		<td><?php echo $order;?></td>
		<td><?php echo $parentName;?></td>
		<td class="action">
			<a href="" onclick="editCategory('<?php echo $idCategory; ?>', '<?php echo $order;?>','<?php echo $parent;?>');return false;" class="edit"><?php echo _("Edit");?></a> 
			| <a href="" onclick="deleteCategory('<?php echo $idCategory;?>');return false;" class="delete"><?php echo _("Delete");?></a>
		<div style="display:none;" id="name-<?php echo $idCategory; ?>"><?php echo $name;?></div>
		<div style="display:none;" id="desc-<?php echo $idCategory; ?>"><?php echo $desc;?></div>
		</td>
	</tr>
	<?php } ?>
</table>
<?php
require_once('footer.php');
?>