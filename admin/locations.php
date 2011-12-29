<?php
require_once('access.php');
require_once('header.php');
?>
<script type="application/javascript">
	function newLocation(){
    	d = document.Location;
    	d.lid.value = "";
    	d.cname.value = "";
    	d.cparent.value = "";
    	d.action.value ="new";
    	d.submitLocation.value ="<?php echo _("New Location");?>";
    	document.getElementById("form-tab").innerHTML ="<?php echo _("New Location");?>";
    	show("formLocation");
    	location.href = "#formLocation";
    }	
    function editLocation(lid,cparent){
    	d = document.Location;
    	d.lid.value = lid;
    	d.cname.value = document.getElementById('name-'+lid).innerHTML;
    	d.cparent.value = cparent;
    	d.action.value ="edit";
    	d.submitLocation.value ="<?php echo _("Edit Location");?>";
    	document.getElementById("form-tab").innerHTML ="<?php echo _("Edit Location");?>";
    	show("formLocation");
    	location.href = "#formLocation";
    }	
    function deleteLocation(Location){
    	if (confirm('<?php echo _("Delete Location");?> "' + document.getElementById('name-'+Location).innerHTML + '"?'))
    	window.location = "locations.php?action=delete&lid=" + Location;
    }
</script>
<h2><?php echo _("Locations");?></h2>
<?php
function catSlug($name,$id=""){ //try to prevent duplicated Locations
    global $ocdb;
    $name=friendly_url($name);
    
    if (is_numeric($id)) $query="SELECT friendlyName FROM ".TABLE_PREFIX."locations where (friendlyName='$name') and (idLocation <> $id) limit 1";
    else $query="SELECT friendlyName FROM ".TABLE_PREFIX."locations where (friendlyName='$name') limit 1";
    $res=$ocdb->getValue($query,"none");
    
    if ($res!=false){//exists try adding with parent id
        $name.='-'.cP("cparent");  
        $res=$ocdb->getValue("SELECT friendlyName FROM ".TABLE_PREFIX."locations where friendlyName='$name' limit 1","none");//now with the new location name
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
            $ocdb->insert(TABLE_PREFIX."locations (name,friendlyName,idLocationParent)",
                        "'".cP("cname")."','$nameSlug',".cP("cparent"));
        }
        else echo _("Location already exists");
    }
    elseif ($action=="delete"){
        $ocdb->delete(TABLE_PREFIX."locations","idLocation=".cG("lid"));
        //echo "Deleted";
    }
    elseif ($action=="edit"){
        $nameSlug=catSlug(cP("cname"),cP("lid"));
        if ($nameSlug!=false){  //no exists update
            $query="update ".TABLE_PREFIX."locations set name='".cP("cname")."',friendlyName='$nameSlug',idLocationParent=".cP("cparent")." where idLocation=".cP("lid");
            $ocdb->query($query);
        }
        else echo _("Location already exists");
        //echo "Edit: $query";
    }
    if (CACHE_DEL_ON_CAT) deleteCache();//delete cache on category if is activated
}
?>
<p class="desc"><?php echo _("Location");?></p>
<div id='formLocation' style="display:none;">
	<div id="form-tab" class="form-tab"></div>
	<div class="clear"></div>
    <form name="Location" action="locations.php" method="post" onsubmit="return checkForm(this);">
		<fieldset>
			<p>
				<label><?php echo _("Name");?></label>
				<input name="cname" type="text" class="text-long" lang="false" onblur="validateText(this);" />
			</p>                          
			<p>
				<label><?php echo _("Parent");?></label>
				<?php sqlOption("select idLocation,name from ".TABLE_PREFIX."locations where idLocationParent=0","cparent","");?>
			</p>
			<input id="submitLocation" type="submit" value="" class="button-submit" />
			<input type="submit" value="<?php echo _("Cancel");?>" class="button-cancel" onclick="hide('formLocation');return false;" />
			<input type="hidden" name="lid" value="" />
			<input type="hidden" name="action" value="" />
		</fieldset>
    </form>            
</div>
<div class="add_link"><a href="" onclick="newLocation();return false;"><?php echo _("New Location");?></a></div>
<table>
	<tr class="thead">
		<td><?php echo _("Name");?></td>
		<td><?php echo _("Parent");?></td>
		<td>&nbsp;</td>
	</tr>
	<?php 
        $result = $ocdb->query("SELECT *,(select name from ".TABLE_PREFIX."locations  where idLocation=C.idLocationParent) ParentName 
                            FROM ".TABLE_PREFIX."locations C order by  idLocationParent");
		$row_count = 0;
        while ($row = mysql_fetch_array($result)){
        	$name = $row["name"] ;
        	$idLocation=$row["idLocation"];
        	$parent=$row["idLocationParent"];
        	$parentName=$row["ParentName"];
        	if ($parentName=="") $parentName="None";

        	$row_count++;
        	if ($row_count%2 == 0) $row_class = 'class="odd"';
        	else $row_class = 'class="even"';
    ?>
	<tr <?php echo $row_class;?>>      
		<td><?php echo $name;?></td>
		<td><?php echo $parentName;?></td>
		<td class="action">
        	<a href="" onclick="editLocation('<?php echo $idLocation; ?>','<?php echo $parent;?>');return false;" class="edit"><?php echo _("Edit");?></a> 
        	| <a href="" onclick="deleteLocation('<?php echo $idLocation;?>');return false;" class="delete"><?php echo _("Delete");?></a>
        </td>
	</tr>
	<div style="display:none;" id="name-<?php echo $idLocation; ?>"><?php echo $name;?></div>
	
	<?php } ?>
</table>
<?php
require_once('footer.php');
?>