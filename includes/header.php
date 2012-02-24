<?php
////////////////////////////////////////////////////////////
//Common header for all the themes
////////////////////////////////////////////////////////////
require_once('functions.php');

function getItemTitle() {
  return $itemTitle;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo substr(LANGUAGE,0,2);?>" lang="<?php echo substr(LANGUAGE,0,2);?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
  <title><?php echo $html_title; ?></title>
  <?php    
    echo "<!-- Specify information for Facebook Share -->
  		  <meta property='og:title' content='";
  		  echo $html_title;
  		  echo "'>";
  ?>
  <meta property="og:description" content="Posted a textbook for sale on Penn Book Bazaar. View the ad by clicking this link.">
  <meta property="og:image" content="<?php echo SITE_URL; ?>/pbbthumbfb.jpg">
  
  <meta name="title" content="<?php echo $html_title;?>" />
  <meta name="description" content="<?php echo $html_description;?>" />
  <meta name="keywords" content="<?php echo $html_keywords;?>" />		
  <meta name="generator" content="Open Classifieds <?php echo VERSION;?>" />
  
	<?php if (isset($type)){?>
<!-- 
		<link rel="stylesheet" type="text/css" href="//code.google.com/css/dev_docs.css"/> 
 -->
	
	<link rel="alternate" type="application/rss+xml" title="<?php echo _("Latests Ads");?> <?php echo ucwords(getTypeName($type));?> <?php echo ucwords($currentCategory);?>" href="<?php echo SITE_URL;?>/rss/?type=<?php echo $type;?>&amp;category=<?php echo $currentCategory;?>" />
	<?php }?>
	<?php if (isset($currentCategory)){?>
		<link rel="alternate" type="application/rss+xml" title="<?php echo _("Latests Ads");?> <?php echo ucwords($currentCategory);?>" href="<?php echo SITE_URL;?>/rss/?category=<?php echo $currentCategory;?>" />
	<?php }?>
		<link rel="alternate" type="application/rss+xml" title="<?php echo _("Latests Ads");?>" href="<?php echo SITE_URL;?>/rss/" />
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>/themes/<?php echo THEME;?>/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>/themes/wordcloud.css" media="screen" />
			    <style type="text/css">
      .book p { margin: 0px; padding: 0px;}
      .gbs_preview_button img { border: 0; margin: 0 }
      .gbs_thumb_img { float: right; margin: 0 0 10px 10px;
                       border: 1px solid #666; padding: 2px }
    </style>

	<?php if (isset($idItem)) {//only in the item the greybox?>
		<script type="text/javascript">var GB_ROOT_DIR = "<?php echo SITE_URL;?>/includes/greybox/";</script>
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>/includes/greybox/gb_styles.css" media="screen" />
	<?php }?>
		<script type="text/javascript" src="<?php echo SITE_URL;?>/includes/js/common.js"></script>
	<?php if (ANALYTICS!=""){?>
        <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', '<?php echo ANALYTICS;?>']);
          _gaq.push(['_trackPageview']);
          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>
    <?php }?>
</head>
<body>
<?php
require_once(SITE_ROOT.'/themes/'.THEME.'/header.php');
require_once(SITE_ROOT.'/facebook.html');
?>
<!--googleoff: index-->
<noscript>
	<div style="height:30px;border:3px solid #6699ff;text-align:center;font-weight: bold;padding-top:10px">
		Your browser does not support JavaScript!
	</div>
</noscript>
<!--googleon: index-->
<?php if (ADVERT_TOP!='') echo ADVERT_TOP;?>
