<?php
if ($advs){//advanced search form
	echo '<div>';
	    advancedSearchForm();
	echo '</div>';
}


if (isset($idItem)||isset($currentCategory)||isset($type)||cG("s")!=""){
?>
<div id="listings">
<?php
	if (isset($location)) $locationtitle = " - ".getLocationName($location);
// 	if (isset($categoryName)) echo '<h1>'.$categoryName.$locationtitle.'</h1>';
	            if(isset($categoryName)&&isset($categoryDescription)){
			echo '<h1>'.$categoryDescription.'</h1>';
			}

	if ($resultSearch){
		foreach ( $resultSearch as $row ){
			$idPost=$row['idPost'];
			$postType=$row['type'];
			$postTypeName=getTypeName($postType);
			$postCoursenumber=$row['coursenumber'];
			$postTitle=$row['title'];
			$postAuthor=$row['author'];
			$postPrice=$row['price'];
			$postDesc= substr(strip_tags(html_entity_decode($row['description'], ENT_QUOTES, CHARSET)), 0, 300)."...";
			$category=$row['category'];//real category name
			$fcategory=$row['fcategory'];//frienfly name category
			$idCategoryParent=$row['idCategoryParent'];
			$fCategoryParent=$row['parent'];
			$postImage=$row['image'];
			$postName=$row['name'];
			$postPassword=$row['password'];
			$insertDate=setDate($row['insertDate']);
			$postUrl=itemURL($idPost,$fcategory,$postTypeName,$postTitle,$fCategoryParent);
			if ($row["hasImages"]==1){
				$postImage=getPostImages($idPost,$insertDate,true,true);
			}
			else $postImage=getPostImages(true,true,true,true);//there's no image
			?>
			<div class="post">


			    <h2><a title="<?php echo " [ ".$postCoursenumber." ] ".$postTitle." ".$postTypeName." ".$category;?>" href="<?php echo SITE_URL.$postUrl;?>"  rel="bookmark" >
						<?php echo " [".$postCoursenumber."] ".$postTitle;?></a><?php echo " by ".$postAuthor?></h2>

			     <div class="post-detail">
	                <p><?php if ($postPrice!=0) echo '<span class="post-price">'.getPrice($postPrice).'</span> | ';?><?php echo getTypeName($postType)." | " ?><span class="post-cat"><?php echo '<a href="'.SITE_URL.catURL($fcategory,$fCategoryParent).'" title="'.$category.' '.$fCategoryParent.'">'.$category.'</a>';?></span> | <span class="post-date"><?php echo "posted by ".$postName." on ".$insertDate;?></span></p>
	             </div>


	          <?php if(isset($_SESSION['admin'])){?>
					<br />
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
	          <div class="clear"></div>
	        </div>

			<?php
		}
	}//end if check there's results
else echo "<p>"._("Nothing found")."</p>";
?>
</div>


	<div class="pagination">
	 <div class="wp-pagenavi">
	<?php //page numbers echo $_SERVER["REQUEST_URI"];
		if ($total_pages>1){

			//if is a search
			if (strlen(cG("s"))>=MIN_SEARCH_CHAR) $search="&s=".cG("s");

			$pag_title=$html_title." ".T_PAGE." ";

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
				echo "<a title='$pag_title' href='".SITE_URL.$pag_url."1'>&lt;&lt;</a>";//First
				echo "<a title='"._("Previous")." $pag_title".($page-1)."' href='".SITE_URL.$pag_url.($page-1)."'>&lt;</a>";//previous
			}
			//pages loop
			for ($i = $page; $i <= $total_pages && $i<=($page+DISPLAY_PAGES); $i++) {//for ($i = 1; $i <= $total_pages; $i++) {
		        if ($i == $page) echo "<span class='current'>$i</span>";//not printing link current page
		        else echo "<a class='page' title='$pag_title$i' href='".SITE_URL."$pag_url$i'>$i</a>";//print the link
		    }

		    if ($page<$total_pages){
		    	echo "<a href='".SITE_URL.$pag_url.($page+1)."' title='"._("Next")." $pag_title".($page+1)."' >&gt;</a>";//next
		    	echo  "<a title='$pag_title$total_pages' href='".SITE_URL."$pag_url$total_pages'>&gt;&gt;</a>";//End
		    }
		}
	?>
	</div>
	</div>

<?php
}//if not home
else {//home page carousel and categories?>

     <div id="welcome">
     <h1> Welcome to the Penn Book Bazaar!</h1>
    <?php

    $account = Account::createBySession();
	if ($account->exists){
	}
	else{ ?>
    <p> To post or view a new book, you must <a href="http://pennbookbazaar.com/login.htm">login</a> or <a href="http://pennbookbazaar.com/register.htm">register for an account</a>. </p>

    <?php } ?>
    <p>
<strong>COMPETITIVE PRICES</strong> buy at market rates and avoid middleman costs<br/>
<strong>PROTECT THE ENVIRONMENT</strong>  reuse books and cut shipping-generated greenhouse gases<br/>
<strong>EFFICIENCY</strong>  You don't have to pay for shipping or wait two weeks for a book to come in the mail<br/>
<strong>SAFETY</strong>  trade with fellow students and inspect a book before paying</p>

<p>
<strong style="color:#F04949;">Searching for Books</strong><br/>
Please check to see if someone wants to buy/sell your book before you <a href="http://pennbookbazaar.com/publish.htm">add a listing</a>! Under Quick Search, you can search by book title, author, course name, course number, and ISBN. If no results are found, try multiple authors and possible alternative course listings. Also, the <a href="http://pennbookbazaar.com/advanced-search.htm">Advanced Search</a> feature may yield more accurate results.
</p>

<p><strong style="color:#F04949;">Buying a Book</strong><br/>
All transactions occur outside Penn Book Bazaar. You can contact a seller either by sending her a message in the contact box (under each book) or emailing her directly. Responses are sent to your Penn email address. You should confirm price and discuss the logistics of the exchange in the following correspondence.</p>


<p><strong style="color:#F04949;">Selling a Book</strong><br/>
<a href="http://pennbookbazaar.com/publish.htm">Post a book for "offer"</a>. Fill in the details of the book, including authors, course information, ISBN, and a description of condition. Submit, and all you have to do is wait for buyers to contact you via email. </p>
     </div>
<!--
      <div id="frontpage_cats">
        <?php echo getCategoriesList();?>
        <div class="clear"></div>
      </div>
 -->


     <?php
     }
?>