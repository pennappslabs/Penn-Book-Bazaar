<?php
////////////////////////////////////////////////////////////
//Functions: call to all the includes
////////////////////////////////////////////////////////////
session_start();

//Initial defines
define('VERSION','1.7.0.1');
define('DEBUG', false);//if you change this to true, returns error in the page instead of email, also enables debug from phpMyDB and disables disqus

if (!DEBUG){//do not display any error message and expire Headers for 24hours
    error_reporting(0);
    ini_set('display_errors','off');
}
else{//displays error messages 
	ini_set('display_errors','on');
}

if (extension_loaded('zlib')) {//check extension is loaded
    if(!ob_start('ob_gzhandler')) ob_start();//start HTML compression, if not normal buffer input mode
}


//config includes
require_once('config.php');//configuration file
require_once('error.php');//handler for errors

//language locales	
	putenv('LC_ALL='.LANGUAGE);
	setlocale(LC_ALL, LANGUAGE.LOCALE_EXT);
	bindtextdomain('messages',SITE_ROOT.'/languages/');
	bind_textdomain_codeset('messages', CHARSET);
	textdomain('messages');
//end language locales	

//loading all classes
require_once('classes/fileCache.php');//cache
require_once('classes/phpMyDb.php');//class for database handling
require_once('classes/phpSEO.php');//class for SEO handling
if (AKISMET!="") require_once('classes/Akismet.class.php');//akismet class 
require_once('classes/wordcloud.class.php');//tag generator
require_once('classes/class.phpmailer.php');//mailer
require_once('classes/class.account.php');//account
require_once('classes/class.ocaku.php');//ocaku integration
//end loading classes


require_once('common.php');//common functions
require_once('search-advanced.php');//common functions
require_once('controller.php');//loads the value of the items/categories  if there's , and starts system variables
require_once('theme.php');//loads the selected theme, see define in config.php
require_once('menu.php');//menu functions generation and some functions that returns stats
require_once('sidebar.php');//sidebar functions generation
require_once('seo.php');//metas for the html, title,description, keywords
require_once('sitemap.php');//sitemap generation
require_once('twitter.php');//twitter post with bit.ly/j.mp

//special functions from the theme if they exists
if (file_exists(SITE_ROOT.'/themes/'.THEME.'/functions.php')){
	require_once(SITE_ROOT.'/themes/'.THEME.'/functions.php'); 
}


?>
