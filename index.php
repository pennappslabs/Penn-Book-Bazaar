<?php
require_once('includes/header.php');

if (file_exists(SITE_ROOT.'/themes/'.THEME.'/index.php')){//index from the theme!
	require_once(SITE_ROOT.'/themes/'.THEME.'/index.php'); 
}
else{//default not found in theme}
	
?>
	<?php if ($advs) echo '<div class="category">'.advancedSearchForm().'</div>';?>
	
	<?php if(isset($categoryName)&&isset($categoryDescription)){
    if (isset($location)) $locationtitle = " - ".getLocationName($location);
    ?>
	<div class="category">
	    <h1><?php echo $categoryName.$locationtitle;?></h1> 
		<p>
			 <?php echo $categoryDescription;?>
			 <a title="<?php echo _("Post Ad in").' '.$categoryName;?>" href="<?php echo SITE_URL.newURL();?>"><?php echo _("Post book in").' '.$categoryName;?></a> 
		</p>
	</div>
	<?php }?>

<div id="fb-root"></div>
    <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=APP_ID";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>

<div class="item">
<?php 
	if ($resultSearch0){
	foreach ( $resultSearch as $row ){
		$idPost=$row['idPost'];
		$postType=$row['type'];
		$postTypeName=getTypeName($postType);
		$postTitle=$row['title'];
		$postPrice=$row['price'];
		$postDesc= substr(strip_tags(html_entity_decode($row['description'], ENT_QUOTES, CHARSET)), 0, 200)."...";
		$category=$row['category'];//real category name
		$fcategory=$row['fcategory'];//frienfly name category
		$idCategoryParent=$row['idCategoryParent'];
		$fCategoryParent=$row['parent'];
		$postPassword=$row['password'];
		
		if ($insertDate!=setDate($row['insertDate'])){
			$insertDate=setDate($row['insertDate']);
			echo "<h3>".$insertDate."</h3>";
		}
		
		if ($row["hasImages"]==1){
			$postImage=getPostImages($idPost,$insertDate,true,true);
		}
		else $postImage=getPostImages(true,true,true,true);//there's no image
		
		
		$postUrl=itemURL($idPost,$fcategory,$postTypeName,$postTitle,$fCategoryParent);
		 	
		?>
		<div class="post" style="float:left;">
			<?php if (MAX_IMG_NUM>0){?>
				<img style="float:left;margin-right:10px;" class="thumb" title="<?php echo $postTitle." ".$postTypeName." ".$fcategory;?>"  alt="<?php echo $postTitle." ".$postTypeName." ".$fcategory;?>"  src="<?php echo $postImage;?>" />
			<?php }?>
			<a title="<?php echo $postTitle." ".$postTypeName." ".$fcategory;?>" href="<?php echo SITE_URL.$postUrl;?>" ><h2><?php echo $postTitle;?></h2></a>		
			<br />		
			<p>
			    <?php echo $postTypeName;?>
                <?php echo '<a href="'.SITE_URL.catURL($fcategory,$fCategoryParent).'" title="'.$category.' '.$fCategoryParent.'">'.$category.'</a>';?>
			    <a title="<?php echo $postTitle." ".$postTypeName." ".$category;?>" href="<?php echo SITE_URL.$postUrl;?>" >
			        <?php echo $postTitle;?> 	
				</a>
				<?php if ($postPrice!=0) echo " - ".getPrice($postPrice);?>
		    </p>
			<p><?php echo $postDesc;?></p>
			<?php if(isset($_SESSION['admin'])){?><br />
				<a href="<?php echo SITE_URL;?>/manage/?post=<?php echo $idPost;?>&amp;pwd=<?php echo $postPassword;?>&amp;action=edit">
						<?php echo _("Edit");?></a><?php echo SEPARATOR;?>
				<a onClick="return confirm('<?php echo _("Deactivate");?>?');" 
					href="<?php echo SITE_URL;?>/manage/?post=<?php echo $idPost;?>&amp;pwd=<?php echo $postPassword;?>&amp;action=deactivate">
						<?php echo _("Deactivate");?></a><?php echo SEPARATOR;?>
				<a onClick="return confirm('<?php echo _("Spam");?>?');"
					href="<?php echo SITE_URL;?>/manage/?post=<?php echo $idPost;?>&amp;pwd=<?php echo $postPassword;?>&amp;action=spam">
						<?php echo _("Spam");?></a><?php echo SEPARATOR;?>
				<a onClick="return confirm('<?php echo _("Delete");?>?');"
					href="<?php echo SITE_URL;?>/manage/?post=<?php echo $idPost;?>&amp;pwd=<?php echo $postPassword;?>&amp;action=delete">
						<?php echo _("Delete");?></a>
			<?php }?>
				
		</div>
		<?php 
	}
}//end if check there's results
else echo "<p>"._("Nothing found")."</p>";
?>
</div>
	<div class="item">&nbsp;<br />
	<?php //page numbers
		if ($total_pages>1){
			
			//if is a search
			if (strlen(cG("s"))>=MIN_SEARCH_CHAR) $search="&s=".cG("s");
			
			$pag_title=$html_title." "._("Page")." ";

			//getting the url
			if(strlen(cG("s"))>=MIN_SEARCH_CHAR){//home with search
				$pag_url='?s='.cG("s").'&category='.$currentCategory.'&page=';
			}
			elseif ($advs){//advanced search
				$pag_url="?category=$currentCategory&type=".cG("type")."&title=".cG("title")."&desc=".cG("desc")."&price=".cG("price")."&place=".cG("place")."&sort=".cG("sort")."&page=";
			}
			elseif (isset($type)){ //only set type in the home
				$pag_url=typeURL($type,$currentCategory).'&page=';
			}
			elseif (isset($currentCategory)){//category
				$pag_url=catURL($currentCategory,$selectedCategory);//only category
				if(!FRIENDLY_URL) $pag_url.='&page=';
			}
			else {
			    $pag_url="/";//home
			    if(!FRIENDLY_URL) $pag_url.='?page=';
			}
			//////////////////////////////////
		
			if ($page>1){
				echo "<a title='$pag_title' href='".SITE_URL.$pag_url."1'>&laquo;&laquo;</a>".SEPARATOR;//First
				echo "<a title='"._("Previous")." $pag_title".($page-1)."' href='".SITE_URL.$pag_url.($page-1)."'>"._("Previous")."</a>";//previous
			}
			//pages loop
			for ($i = $page; $i <= $total_pages && $i<=($page+DISPLAY_PAGES); $i++) {//for ($i = 1; $i <= $total_pages; $i++) {
		        if ($i == $page) echo SEPARATOR."<b>$i</b>";//not printing link current page
		        else echo SEPARATOR."<a title='$pag_title$i' href='".SITE_URL."$pag_url$i'>$i</a>";//print the link
		    }
		    
		    if ($page<$total_pages){
		    	echo SEPARATOR."<a href='".SITE_URL.$pag_url.($page+1)."' title='"._("Next")." $pag_title".($page+1)."' >"._("Next")."</a>";//next
		    	echo  SEPARATOR."<a title='$pag_title$total_pages' href='".SITE_URL."$pag_url$total_pages'>&raquo;&raquo;</a>";//End
		    }
		}	
	?>
	</div>
<?php
}//if else


require_once('includes/footer.php');
?>
