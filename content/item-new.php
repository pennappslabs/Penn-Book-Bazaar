<?php
require_once('../includes/header.php');

if (LOGON_TO_POST){
    $account = Account::createBySession();
    if ($account->exists){
        $name = $account->name;
        $email = $account->email;
    } else {
        header("Location: ".accountLoginURL());
        die();
    }
}

if (!isInSpamList($client_ip)){//no spammer
	require_once('../includes/classes/resize.php');
	if ($_POST){	
		if(cP("math") == $_SESSION["mathCaptcha"])	{
			if (isEmail(cP("email"))){//is email
				if(!isSpam(cP("name"),cP("email"),cP("description"))){//check if is spam!
					
					$image_check=check_images_form();//echo $image_check;
					
					if (is_numeric($image_check)){//if the images were right, or not any image uploaded
					
						if (is_numeric(cP("price"))) $price=cP("price");
						else $price=0;
						//DB insert
						$post_password=generatePassword();					
						if (HTML_EDITOR) $desc=cPR("description");
						else $desc=cP("description");
						if (VIDEO && cp("video")!="" && strpos(cp("video"), "http://www.youtube.com/watch?v=")==0) $desc.='[youtube='.cp("video").']';//youtube video
						$title=cP("title");
						$coursenumber=cP("coursenumber");
						$coursename=cP("coursename");
						$author=cP("author");
						$isbn=cP("isbn");
						$isConfirmed=cP("isConfirmed");
						$email=cP("email");
                        if (cP("location")!="") $location = cP("location");
                        else $location=0;
						
                        if ($image_check>1) $hasImages=1;
                        else $hasImages=0;
                        
						$ocdb->insert(TABLE_PREFIX."posts (idCategory,type,isConfirmed,coursenumber,coursename,title,author,isbn,description,price,idLocation,place,name,email,password,ip,hasImages)","".
												cP("category").",".cP("type").",'$isConfirmed','$coursenumber','$coursename','$title','$author','$isbn','$desc',$price,$location,'".cP("place")."','".cP("name")."','$email','$post_password','$client_ip',$hasImages");
						$idPost=$ocdb->getLastID();
						
						$query="select coursenumber,title,type,friendlyName,password,c.name cname,p.description,p.name,price,hasImages,p.insertDate,p.idLocation,p.place,
		        (select friendlyName from ".TABLE_PREFIX."categories where idCategory=c.idCategoryParent limit 1) parent
				    from ".TABLE_PREFIX."posts p 
				    inner join ".TABLE_PREFIX."categories c
				    on c.idCategory=p.idCategory
				    where idPost=$idPost";
						$result=$ocdb->query($query);
						
						if (mysql_num_rows($result)){
						$row=mysql_fetch_assoc($result);
			$fcategory=$row["friendlyName"];
			$parent=$row["parent"];
			$postPassword=$row["password"];
			}
		 
			$postUrl=itemURL($idPost,$fcategory,$type,$title,$parent); 
			
		    $bodyHTML='NEW Post '.SITE_URL.$postUrl.'<br />
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwd='.$postPassword.'&amp;action=edit">'._("Edit").'</a>'.SEPARATOR.'
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwd='.$postPassword.'&amp;action=deactivate">'._("Deactivate").'</a>'.SEPARATOR.'
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwd='.$postPassword.'&amp;action=spam">'._("Spam").'</a>'.SEPARATOR.'
			<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwd='.$postPassword.'&amp;action=delete">'._("Delete").'</a>';
			sendEmail(NOTIFY_EMAIL,"New post in ".SITE_URL,$bodyHTML);//email to the NOTIFY_EMAIL	
		
			if ($parent!="") $parent='#'.$parent.' ';
			post_to_twitter($title." #$type #$fcategory $parent",SITE_URL.$postUrl);//twitt the post into the set twitter account
			
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
					'coursename'=>$row["coursename"],
					'coursenumber'=>$row["coursenumber"],
					'title'=>$title,
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
					
				$ocaku->newPost($data);
				unset($ocaku);
			}
			//end ocaku
			
// 			alert(_("Your post was successfully activated, thank you"));
			jsRedirect(SITE_URL.$postUrl);
		

						
						//end database insert
						
// 						if ($image_check>1) upload_images_form($idPost,$title);
							
		
						//EMAIL notify
						//generate the email to send to the client , we allow them to erase posts? mmmm
						// if(FRIENDLY_URL) {
// 						    $linkConfirm=SITE_URL."/manage/?post=$idPost&pwd=$post_password&action=confirm";
// 						    $linkDeactivate=SITE_URL."/manage/?post=$idPost&pwd=$post_password&action=deactivate";
// 						    $linkEdit=SITE_URL."/manage/?post=$idPost&pwd=$post_password&action=edit";
// 						}
// 						else{
// 						    $linkConfirm=SITE_URL."/content/item-manage.php?post=$idPost&pwd=$post_password&action=confirm";
// 						    $linkDeactivate=SITE_URL."/content/item-manage.php?post=$idPost&pwd=$post_password&action=deactivate";
// 						    $linkEdit=SITE_URL."/content/item-manage.php?post=$idPost&pwd=$post_password&action=edit";
// 						}
// 						
//                         $message="<p>"._("To confirm your post click here").": ".
// 								"<a href='$linkConfirm'>$linkConfirm</a><br /><br />".
// 								_("If you want to edit your post click here").": 
// 								<a href='$linkEdit'>$linkEdit</a><br />".
// 								_("If this post is no longer available please click here").": 
// 								<a href='$linkDeactivate'>$linkDeactivate</a></p>";
//                         
//                         $array_content[]=array("ACCOUNT", _("User"));
//                         $array_content[]=array("MESSAGE", $message);
//                         
//                         $bodyHTML=buildEmailBodyHTML($array_content);
//                         
// 						echo _("Thank you! Check your email to confirm the post")." ".sendEmail(NOTIFY_EMAIL,_("Confirm")." ".$title." ". $_SERVER['SERVER_NAME'],$bodyHTML);
						
						//for preventing f5
						$_SESSION["mathCaptcha"]="";
						
						require_once('../includes/footer.php');
						exit;
					}
					else echo "<div id='sysmessage'>".$image_check."</div>";//end upload verification
				}//end akismet
				else {//is spam!
					echo "<div id='sysmessage'>"._("Oops! Spam? If it was not spam, contact us")."</div>";
					require_once('../includes/footer.php');
					exit;
				}
			}//email validation
			else echo "<div id='sysmessage'>"._("Wrong email address")."</div>";//Wrong email address
		}//captcha validation
		else echo "<div id='sysmessage'>"._("Wrong captcha")."</div>";//wrong captcha
	}//if post
	
?>
<h3><?php echo _("Post a new book");?> - <?php echo $categoryName;?></h3>
<em>*required fields</em> <br/>
To post a <strong>set of books</strong>: Put "Set of # Books" in Book Title. Describe <u>all books</u> in Book Description. <br/>
To post a <strong>bulkpack</strong>: Put "Bulkpack" in Book Title. Describe semester used in Book Description (i.e. Fall 09).<br/>
<form action="" method="post" onsubmit="return checkForm(this);" enctype="multipart/form-data">
<table cellpadding="2" cellspacing="0">
<tr><td><?php echo _("Type");?>:</td><td>
	<select id="type" name="type">
		<option value="<?php echo TYPE_OFFER;?>"><?php echo _("Offer");?></option>
		<option value="<?php echo TYPE_NEED;?>"><?php echo _("Need");?></option>
	</select>
	</td></tr>
	

		<tr><td><?php echo _("Subject*");?>:</td><td>
		<?php 
		$query="SELECT idCategory,description,(select description from ".TABLE_PREFIX."categories where idCategory=C.idCategoryParent) FROM ".TABLE_PREFIX."categories C";
		sqlOptionGroup($query,"category",$idCategory);
		?> Language courses should be listed under Language.
		</td></tr>

   
   <tr><td> <?php echo _("Book Title");?>*:</td><td>
	<input id="title" name="title" type="text" value="<?php echo $_POST["title"];?>" size="35" maxlength="120" onblur="validateText(this);"  lang="false" />
	</tr></td>
	 <tr><td><?php echo _("Author(s)");?>*:</td><td>
	<input id="author" name="author" type="text" value="<?php echo $_POST["author"];?>" size="35" maxlength="50" onblur="validateText(this);"  lang="false" />
	</tr></td>
	 <tr><td><?php echo _("ISBN");?>:</td><td>
	<input id="isbn" name="isbn" type="text" value="<?php echo $_POST["isbn"];?>" size="13" maxlength="13" /> (10 or 13 digits, numbers only, <strong>exclude dashes</strong>)
    </tr></td>
	 <tr><td><?php echo _("Course Name");?>*:</td><td>
	<input id="coursenumber" name="coursename" type="text" value="<?php echo $_POST["coursename"];?>" size="35" maxlength="120" onblur="validateText(this);"  lang="false" />
   </tr></td>
	 <tr><td><?php echo _("Course Number");?>*:</td><td>
	<input id="coursename" name="coursenumber" type="text" value="<?php echo $_POST["coursenumber"];?>" size="20" maxlength="100" onblur="validateText(this);"  lang="false" /> (include all known cross-listings, i.e. COGS101/CIS140/LING105) </tr></td>
	 <tr><td><?php echo _("Price");?>:</td><td>
	<input id="price" name="price" type="text" size="3" value="<?php echo $_POST["price"];?>" maxlength="8"  onkeypress="return isNumberKey(event);"   /><br />
	</tr></td>
	
	 <tr><td><?php echo _("Book(s) Description");?> -</td> <td> Include condition (i.e. brand new, highlighted, marked with scribblings of a pyschotic mind) </td></tr>
</table>
	<?php if (HTML_EDITOR){?>
	    <script type="text/javascript">var SITE_URL="<?php echo SITE_URL;?>";</script>
		<script type="text/javascript" src="<?php echo SITE_URL;?>/includes/js/nicEdit.js"></script>
		<script type="text/javascript">
		//<![CDATA[
			bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
			//]]>
		</script>
		<textarea rows="8" cols="73" name="description" id="description"><?php echo stripslashes($_POST['description']);?></textarea>
	<?php }
	    else{?>
		<textarea rows="8" cols="73" name="description" id="description" onblur="validateText(this);"  lang="false"><?php echo strip_tags($_POST['description']);?></textarea><?php }?>
	<br />

    <input id="name" name="name" type="hidden" value="<?php echo $name;?>" /> 
    <input id="email" name="email" type="hidden" value="<?php echo $email;?>" />
    <input id="isConfirmed" name="isConfirmed" type="hidden" value="1" />
    
	<!-- 
<?php 
	if (MAX_IMG_NUM>0){
		echo "<input type='hidden' name='MAX_FILE_SIZE' value='".MAX_IMG_SIZE."' />";
		echo "<br />"._("Upload pictures max file size").": ".(MAX_IMG_SIZE/1000000)."Mb "._("format")." ".IMG_TYPES."<br />";
		for ($i=1;$i<=MAX_IMG_NUM;$i++){?>
			<label><?php echo _("Picture");?> <?php echo $i?>:</label><input type="file" name="pic<?php echo $i?>" id="pic<?php echo $i?>" value="<?php echo $_POST["pic".$i];?>" /><br />
	<?php }
	}
	?>
	<br />
 -->
	<?php  mathCaptcha();?>
	<p><input id="math" name="math" type="text" size="2" maxlength="2"  onblur="validateNumber(this);"  onkeypress="return isNumberKey(event);" lang="false" /></p>
	<br />
	<input type="submit" id="submit" value="<?php echo _("Post it!");?>" />
</form>



<?php
}
else {//is spammer
	alert(_("NO Spam!"));
	jsRedirect(SITE_URL);
}
require_once('../includes/footer.php');
?>