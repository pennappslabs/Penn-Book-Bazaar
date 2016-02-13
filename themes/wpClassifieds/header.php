<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>/themes/wpClassifieds/jsclass.js"></script>
<?php
//getting the title in two parts/colors
$pos=strpos(SITE_NAME," ");
$firstH=substr(SITE_NAME,0,$pos);//first part of the site name in green
$secondH=substr(SITE_NAME,$pos);//second part of the name un blue
?>

<div class="container_12" id="wrap">
  <div class="grid_12" id="header">
    <div id="logo">
      <h4><a href="<?php echo SITE_URL; ?>" title="<?php echo SITE_NAME; ?>"><img id="pbblogo" src="<?php echo SITE_URL;?>/pbbbanner.png"/><!-- <span class="firstH"><?php echo $firstH; ?></span><span class="secondH"><?php echo $secondH; ?> --><!-- </span><span style="font-size: 12px; font-family: helvetica; letter-spacing:0px;"> UA/IFC textbook exchange</span></a> --></h4>
      <div style="float:right;">
          <div>
              <a href="http://pennua.org"><img src="http://pennbookbazaar.com/logo.jpg"/></a>
              <a href="http://upennifc.com"><img src="http://pennbookbazaar.com/ifclogo.jpg"/></a>
          </div>
          <p id="postBook"><?php echo '<a title="'._("Post a new book!").'" href="http://pennbookbazaar.com/publish.htm">'._("Post a new book!").'</a>';?></p>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <div class="clear"></div>
  <div class="grid_12" id="top_dropdown">
    <ul id="nav">
    <?php generateMenuJS($selectedCategory);?>
    </ul>
  </div>
  <div class="clear"></div>
  <div class="grid_12" style="position:static;" id="top_cats">
    <?php generateSubMenuJS($idCategoryParent,$categoryParent,$currentCategory);  ?>
  </div>
  <div class="clear"></div>
  <div id="content">
     <div class="grid_12">
      <div class=" breadcrumb">
            <?php if(isset($categoryName)&&isset($categoryDescription)){ ?>

			    <a title="<?php echo _("Post a new book in");?> <?php echo $categoryName;?>" href="<?php echo SITE_URL.newURL();?>"><?php echo _("Post a new book in");?> <?php echo $categoryName;?></a>
	        <?php }
	            else echo date("l d, F Y");
	        ?>
            <div style="float:right;"><b><?php echo _("Filter");?></b>:
		    <?php generatePostType($currentCategory,$type); ?>
		    </div>
		</div>
    </div>
    <div class="clear"></div>
       <div class="grid_8" id="content_main">
