<?php
require_once('../includes/header.php');
require_once('../includes/classes/resize.php');
?>
<?php
if (cG("pwid")&&is_numeric(cG("post"))){//delete ,activate or deactivate
	$action=cG("action");
	$post_password=cG("pwid");
	$post_id=cG("post");
	
	if ($action=="confirm"){//confirm a new post
		//update table
		$ocdb->update(TABLE_PREFIX."posts","isConfirmed=1","idPost=$post_id and password='$post_password'");
		
		//redirect to the item
		$query="select coursenumber,title,type,friendlyName,password,c.name cname,p.description,p.name,price,hasImages,p.insertDate,p.idLocation,p.place,
		        (select friendlyName from ".TABLE_PREFIX."categories where idCategory=c.idCategoryParent limit 1) parent
				    from ".TABLE_PREFIX."posts p 
				    inner join ".TABLE_PREFIX."categories c
				    on c.idCategory=p.idCategory
				where idPost=$post_id and password='$post_password' and isConfirmed=1 Limit 1";
		$result=$ocdb->query($query);
		if (mysql_num_rows($result)){
			$row=mysql_fetch_assoc($result);
			$title=$row["title"];
			$postTitle=friendly_url($title);
			$postTypeName=getTypeName($row["type"]);
			$fcategory=$row["friendlyName"];
			$parent=$row["parent"];
			$postPassword=$row["password"];
				
			$postUrl=itemURL($post_id,$fcategory,$postTypeName,$postTitle,$parent);
			
		    $bodyHTML='NEW Post '.SITE_URL.$postUrl.'<br />
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=edit">'._("Edit").'</a>'.SEPARATOR.'
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=deactivate">'._("Deactivate").'</a>'.SEPARATOR.'
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=spam">'._("Spam").'</a>'.SEPARATOR.'
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=delete">'._("Delete").'</a>';
			sendEmail(NOTIFY_EMAIL,"NEW ad in ".SITE_URL,$bodyHTML);//email to the NOTIFY_EMAIL	
			
			if ($parent!="") $parent='#'.$parent.' ';
			post_to_twitter($title." #$postTypeName #$fcategory $parent",SITE_URL.$postUrl);//twitt the post into the set twitter account
			
			if (CACHE_DEL_ON_POST) deleteCache();//delete cache on post if is activated
			if (SITEMAP_DEL_ON_POST) generateSitemap();//new item generate sitemap
			
			//ocaku insert new
			if (OCAKU){
				$ocaku=new ocaku();
				
				if ($row["hasImages"]==1){//images
					$itemImages=getPostImages($post_id,setDate($row["insertDate"]));//getting the images
					$numImages=count($itemImages);
					if ($numImages>0) $imagePost=$itemImages[0][1];//thumb
					else $imagePost='';
				}
				
				if (LOCATION) $oplace=getLocationName($row["idLocation"]);
				else  $oplace=$row["place"];
				
				$data=array(
					'KEY'=>OCAKU_KEY,
					'idPostInClass'=>$post_id,
					'Category'=>$row["cname"],
					'URL'=>SITE_URL.$postUrl,
					'type'=>$postTypeName,
					'coursenumber'=>$row["coursenumber"],
					'title'=>$title,
					'description'=>$row["description"],
					'name'=>$row["name"],
					'price'=>$row["price"],
					'currency'=>CURRENCY,
					'language'=>substr(LANGUAGE,0,2),
					'image'=>$imagePost,
					'num_images'=>$numImages
					);
					
				$ocaku->newPost($data);
				unset($ocaku);
			}
			//end ocaku
			
			alert(_("Your post was successfully activated, thank you"));
			jsRedirect(SITE_URL.$postUrl);
		}
	}	
	elseif ($action=="deactivate"){
		$ocdb->update(TABLE_PREFIX."posts","isAvailable=0","idPost=$post_id and password='$post_password'");
		if (CACHE_DEL_ON_POST) deleteCache();//delete cache
		//ocaku deactivate post
		if (OCAKU){
			$ocaku=new ocaku();
			$data=array(
				'KEY'=>OCAKU_KEY,
				'idPostInClass'=>$post_id,
			);
			$ocaku->deactivatePost($data);
			unset($ocaku);
		}
		//end ocaku
		echo "<div id='sysmessage'>"._("Your Ad was successfully deactivated")."</div>";
	}
	elseif ($action=="activate"){
		$ocdb->update(TABLE_PREFIX."posts","isAvailable=1","idPost=$post_id and password='$post_password'");
		if (CACHE_DEL_ON_POST) deleteCache();//delete cache 
		echo "<div id='sysmessage'>"._("Your Ad was successfully activated")."</div>";
	}
	elseif ($action=="delete"&&(isset($_SESSION['admin']) || isset($_SESSION['ocAccount']))){
		deletePostImages($post_id);//delete images! and folder
		$ocdb->delete(TABLE_PREFIX."posts","idPost=$post_id and password='$post_password'");
		if (CACHE_DEL_ON_POST) deleteCache();//delete cache
		//ocaku delete post
		if (OCAKU){
			$ocaku=new ocaku();
			$data=array(
				'KEY'=>OCAKU_KEY,
				'idPostInClass'=>$post_id,
			);
			$ocaku->deletePost($data);
			unset($ocaku);
		}
		//end ocaku
		echo "<div id='sysmessage'>"._("Your post was successfully deleted")."</div>";
	}
	elseif ($action=="spam"&&isset($_SESSION['admin'])){//only for admin mark as spam
		
		if (AKISMET!=""){//report akismet
			$query="select name,email,description,ip from ".TABLE_PREFIX."posts where idPost=$post_id and password='$post_password' Limit 1";
			$result=$ocdb->query($query);
			if (mysql_num_rows($result)){
				$row=mysql_fetch_assoc($result);
					$akismet = new Akismet(SITE_URL ,AKISMET);
					$akismet->setCommentAuthor($row["name"]);
					$akismet->setCommentAuthorEmail($row["email"]);
					$akismet->setCommentContent($row["description"]);
					$akismet->setUserIP($row["ip"]);//ip of the bastard!
					$akismet->submitSpam();
					$akismet->submitHam();
			}
		}
		
		$ocdb->update(TABLE_PREFIX."posts","isAvailable=2","idPost=$post_id and password='$post_password'");//set post as spam state 2
		deletePostImages($post_id);// delete the images cuz of spammer
		if (CACHE_DEL_ON_POST) deleteCache();//delete cache
		
		//ocaku spam post
		if (OCAKU){
			$ocaku=new ocaku();
			$data=array(
				'KEY'=>OCAKU_KEY,
				'idPostInClass'=>$post_id,
			);
			$ocaku->spamPost($data);
			unset($ocaku);
		}
		//end ocaku
		
		echo "<div id='sysmessage'>"._("Spam reported")."</div>";
	}
	elseif ($action=="edit"){//edit post
		if ($_POST){//update post
			if(cP("math") == $_SESSION["mathCaptcha"])	{//everything ok

			    $image_check=check_images_form();//echo $image_check;
				
			    if (is_numeric($image_check)){//if the images were right, or not any image uploaded
				    if (is_numeric(cP("price"))) $price=cP("price");
				    else $price=0;
				    //DB update				
				    if (HTML_EDITOR) $desc=cPR("description");
				    else $desc=cP("description");
                    
				    if ($image_check>1) $hasImages= " ,hasImages=1";
				    
				    $title=cP("title");	
				    $coursenumber=cP("coursenumber");
				    $coursename=cP("coursename");
				    $author=cP("author");
				    $isbn=cP("isbn");
                    if (cP("location")!="") $location = cP("location");
                    else $location=0;
                    
                    $param = "idCategory=".cP("category").",type=".cP("type").",coursenumber='$coursenumber',coursename='$coursename',
							    title='$title',author='$author',isbn='$isbn',description='$desc',price=$price,
							    place='".cP("place")."',name='".cP("name")."',
							    ip='$client_ip'".$hasImages;
                    if (is_numeric(cP("location"))) $param .= ",idLocation=".$location;
				    $ocdb->update(TABLE_PREFIX."posts",$param,"idPost=$post_id and password='$post_password' Limit 1");
				    if (CACHE_DEL_ON_POST) deleteCache();//delete cache on post
				    //end database update
				
				    if ($image_check>1){//something to upload
					    $date=deletePostImages($post_id); //delete previous images
					    upload_images_form($post_id,$title,$date);//upload new ones
				    }
				    //end images	
			
				    echo "<div id='sysmessage'>"._("Your Ad was successfully updated")."</div>";
				    //for preventing f5
				    $_SESSION["mathCaptcha"]="";
				}//image check
			    else echo "<div id='sysmessage'>".$image_check."</div>";//end upload verification
			}//end captcha
		    else echo "<div id='sysmessage'>"._("Wrong captcha")."</div>";
		}

		$query="select p.*,friendlyName,c.name cname,p.description,
		        (select friendlyName from ".TABLE_PREFIX."categories where idCategory=c.idCategoryParent limit 1) parent
				    from ".TABLE_PREFIX."posts p 
				    inner join ".TABLE_PREFIX."categories c
				    on c.idCategory=p.idCategory
				where idPost=$post_id and password='$post_password' and isAvailable!=2 Limit 1";
		$result=$ocdb->query($query);
		if (mysql_num_rows($result)){
			$row=mysql_fetch_assoc($result);
			
			if($row['isConfirmed']!=1) {//the ad is not confirmed!
				$linkConfirm=SITE_URL."/manage/?post=$post_id&pwid=$post_password&action=confirm";
				echo "<b><a href='$linkConfirm'>"._("To confirm your Ad click here")."</a></b><br />";
			}
			
			if($row['isAvailable']==1){//able to deactivate it
				$linkDeactivate=SITE_URL."/manage/?post=$post_id&pwid=$post_password&action=deactivate";
				echo "<a href='$linkDeactivate'>"._("If this Ad is no longer available please click here")."</a>";
			}
			else {//activate it
				$linkActivate=SITE_URL."/manage/?post=$post_id&pwid=$post_password&action=activate";
				echo "<a href='$linkActivate'>"._("Activate")."</a>";
			}
			
			$postTitle=$row["title"];
			$postTitleF=friendly_url($postTitle);
			$postTypeName=getTypeName($row["type"]);
			$fcategory=$row["friendlyName"];
			$parent=$row["parent"];
			$insertDate=setDate($row['insertDate']);
			
			$postUrl=itemURL($post_id,$fcategory,$postTypeName,$postTitleF,$parent);	

			 //ocaku update post
			if (OCAKU && $_POST && $action=="edit" ){
				$ocaku=new ocaku();
				
				if ($row["hasImages"]==1){//images
					$itemImages=getPostImages($post_id,$insertDate);//getting the images
					$numImages=count($itemImages);
					if ($numImages>0) $imagePost=$itemImages[0][1];//thumb
					else $imagePost='';
				}
				
				if (LOCATION) $oplace=getLocationName(cP("location"));
				else  $oplace=cP("place");
				
				$data=array(
					'KEY'=>OCAKU_KEY,
					'idPostInClass'=>$post_id,
					'Category'=>$row["cname"],
					'Place'=>$oplace,
					'URL'=>SITE_URL.$postUrl,
					'type'=>$postTypeName,
					'coursename'=>$row["coursename"],
					'coursenumber'=>$row["coursenumber"],
					'title'=>$postTitle,
					'author'=>$row["author"],
					'isbn'=>$row["isbn"],
					'description'=>$row["description"],
					'name'=>$row["name"],
					'price'=>$row["price"],
					'currency'=>CURRENCY,
					'language'=>substr(LANGUAGE,0,2),
					'image'=>$imagePost,
					'num_images'=>$numImages
					);
				$ocaku->updatePost($data);
				unset($ocaku);
			}
			//end ocaku
			?>
<h3><?php echo _("Edit Post");?>: <a target="_blank" href="<?php echo SITE_URL.$postUrl;?>"><?php echo $postTitle;?></a></h3>
<form action="" method="post" onsubmit="return checkForm(this);" enctype="multipart/form-data">
	<table cellpadding="2" cellspacing="0">
	<tr><td><?php echo _("Type");?>: </td>
	<td><select id="type" name="type">
		<option value="<?php echo TYPE_OFFER;?>" <?php if($row['type']==TYPE_OFFER)echo 'selected="selected"';?> ><?php echo _("offer");?></option>
		<option value="<?php echo TYPE_NEED;?>"  <?php if($row['type']==TYPE_NEED)echo 'selected="selected"';?> ><?php echo _("need");?></option>
	</select></td></tr>
	<tr><td><?php echo _("Subject");?>:</td>
	<td><?php 
	$query="SELECT idCategory,description,(select description from ".TABLE_PREFIX."categories where idCategory=C.idCategoryParent) FROM ".TABLE_PREFIX."categories C";
	sqlOptionGroup($query,"category",$row["idCategory"]);
	?></td></tr>
	<tr><td><?php echo _("Book Title");?>*:</td>
    <td><input id="title" name="title" type="text" value="<?php echo $postTitle;?>" size="61" maxlength="120" onblur="validateText(this);"  lang="false" />
	</td></tr>
	<tr><td><?php echo _("Author(s)");?>*:</td>
    <td><input id="author" name="author" type="text" value="<?php echo $row[author];?>" size="61" maxlength="120" onblur="validateText(this);"  lang="false" />
	</td></tr>
	<tr><td><?php echo _("ISBN");?>*:</td>
    <td><input id="isbn" name="isbn" type="text" value="<?php echo $row[isbn];?>" size="61" maxlength="120" onblur="validateText(this);"  lang="false" />
	</td></tr>
    <tr><td><?php echo _("Course Number");?>*:</td>
    <td><input id="coursenumber" name="coursenumber" type="text" value="<?php echo $row[coursenumber];?>" size="61" maxlength="120" onblur="validateText(this);"  lang="false" />
	</td></tr>
	<tr><td><?php echo _("Course Name");?>*:</td>
	<td><input id="coursename" name="coursename" type="text" value="<?php echo $row[coursename];?>" size="61" maxlength="120" onblur="validateText(this);"  lang="false" />
	</td></tr>
	<tr><td><?php echo _("Price");?>*:</td>
	<td><input id="price" name="price" type="text" size="3" value="<?php echo $row["price"];?>" maxlength="8"  onkeypress="return isNumberKey(event);"   />
	</td></tr>
	</table>
	 <input id="name" name="name" type="hidden" value="<?php echo $row["name"];?>" maxlength="75"  lang="false"  /><br />
	<?php echo _("Book(s) Description");?>*:<br />
	<?php if (HTML_EDITOR){?>
	    <script type="text/javascript">var SITE_URL="<?php echo SITE_URL;?>";</script>
		<script type="text/javascript" src="<?php echo SITE_URL;?>/includes/js/nicEdit.js"></script>
		<script type="text/javascript">
		//<![CDATA[
			bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
			//]]>
		</script>
		<textarea rows="10" cols="73" name="description" id="description"><?php echo stripslashes($row['description']);?></textarea>
	<?php }else{?>
		<textarea rows="10" cols="73" name="description" id="description" onblur="validateText(this);"  lang="false"><?php echo strip_tags($row['description']);?></textarea><?php }?>
	<br />
	
<!-- 
	<?php if (MAX_IMG_NUM>0){
	echo "<br />"._("Upload pictures max file size").": ".(MAX_IMG_SIZE/1000000)."Mb "._("format")." ".IMG_TYPES."<br />";
	echo "<input type='hidden' name='MAX_FILE_SIZE' value='".MAX_IMG_SIZE."' />";
	echo "<b>"._("These images will be permanently removed if you upload new ones")."</b><br />";?>
	<?php 
		$images=getPostImages($post_id,$insertDate);
		foreach($images as $img){
			echo '<a href="'.$img[0].'" title="'.$itemTitle.' '._("Picture").'" target="_blank">
			 		<img class="thumb" src="'.$img[1].'" title="'.$itemTitle.' '._("Picture").'" alt="'.$itemTitle.' '._("Picture").'" /></a>';
		}
		for ($i=1;$i<=MAX_IMG_NUM;$i++){
			?><br /><label><?php echo _("Picture");?> <?php echo $i?>:</label><input type="file" name="pic<?php echo $i?>" id="pic<?php echo $i?>" value="<?php echo $_POST["pic".$i];?>" /><?php
		 }
	 }?>
 -->
	<br /><br />
	<?php  mathCaptcha();?>
	<p><input id="math" name="math" type="text" size="2" maxlength="2"  onblur="validateNumber(this);"  onkeypress="return isNumberKey(event);" lang="false" /></p>
	<br />
	<input type="submit" id="submit" value="<?php echo _("Update");?>" />
</form>		
<?php 
		}
		else echo _("Nothing found");//nothing returned for that item		
	}
}
require_once('../includes/footer.php');
?>