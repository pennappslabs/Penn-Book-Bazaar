<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>/themes/wpClassifieds/jsclass.js"></script>

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '160063330776916', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      oath       : true,
      xfbml      : true  // parse XFBML
    });
  };
  // Load the SDK Asynchronously
  (function(d){
    var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
  }(document));
</script>

<div class="container_12" id="wrap">
  <div class="grid_12" id="header">
    <div id="logo"> 
      <h4><a href="<?php echo SITE_URL; ?>" title="<?php echo SITE_NAME; ?>"><img src="<?php echo SITE_URL; ?>/pbbbanner.png"/></h4>
      <p><?php echo '<a title="'._("Post a new book!").'" href="'.SITE_URL.'/publish.htm">'._("Post a new book!").'</a>';?></p>
      <div style="float:right;"><a href="http://pennua.org"><img src="<?php echo SITE_URL; ?>/logo.jpg"/></a><a href="http://upennifc.com"><img src="<?php echo SITE_URL; ?>/ifclogo.jpg"/></a></div>
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
   
