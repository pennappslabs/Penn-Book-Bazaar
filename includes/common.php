<?php
////////////////////////////////////////////////////////////
//Common Functions
////////////////////////////////////////////////////////////
function clean($var){//request string cleaner
	if(get_magic_quotes_gpc()) $var=stripslashes($var); //clean
	$var=mysql_real_escape_string($var); //clean
	return $var;//returning clean var
}
////////////////////////////////////////////////////////////
function cG($name){//clean Get, to prevent mysql injection Get method
	return clean($_GET[$name]);
}
////////////////////////////////////////////////////////////
function cP($name){//clean post, to prevent mysql injection Post method and remove html
	$data = str_replace("\r\n", "<br/>", $_POST[$name]);
	return clean($data);
}
////////////////////////////////////////////////////////////
function cPR($name){//clean post, to prevent mysql injection Post method, but do not remove the htmltags
	return ToHtml(clean($_POST[$name]));
}
////////////////////////////////////////////////////////////
function ToHtml($string){//replaces for special things
	$string = str_replace ("&nbsp;"," ", $string);//problem with spaces
	$string = str_replace ("href=","rel=\"nofollow\" href=", $string);	//nofollow
	return $string;
} 
////////////////////////////////////////////////////////////
function friendly_url($url) {
	// everything to lower no spaces begin or end and replace accent characters
    $url = strtolower(replace_accents(trim($url)));
	// adding '-' for spaces and union characters
	$url = str_replace (array(' ', '&', '\r\n', '\n', '+',','), '-', $url);
	//delete and replace rest of special chars
	$url = preg_replace (array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/'),  array('', '-', ''), $url);
	return $url; //return the friendly url
}
////////////////////////////////////////////////////////////
function u($word){//returns the firendly word with html parsed
	return friendly_url(html_entity_decode($word,ENT_QUOTES,CHARSET));
}
////////////////////////////////////////////////////////////
function replace_accents($var){ //replace for accents catalan spanish and more
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
    $var= str_replace($a, $b,$var);
    return $var; 
}  
////////////////////////////////////////////////////////////
function sqlOption($sql,$name,$option){//generates a select tag with the values specified on the sql, 2nd parameter name for the combo, , 3rd value selected if there's
	global $ocdb;
	$result =$ocdb->query($sql);//1 value needs to be the ID, second the Name, if there's more doens't work
	$sqloption= "<select name='".$name."' id='".$name."'>
				<option value='0'>Home</option>";
	while ($row=mysql_fetch_assoc($result)){
		$first=mysql_field_name($result, 0);
		$second=mysql_field_name($result, 1);

			if ($option==$row[$first]) { $sel="selected=selected";}
			$sqloption=$sqloption .  "<option ".$sel." value='".$row[$first]."'>" .$row[$second]. "</option>";
			$sel="";
	}
		$sqloption=$sqloption . "</select>";
		echo $sqloption;
}
////////////////////////////////////////////////////////////
function sqlOptionGroup($sql,$name,$option){//generates a select tag with the values specified on the sql, 2nd parameter name for the combo, , 3rd value selected if there's
	global $ocdb;
	$result =$ocdb->query($sql);//1 value needs to be the ID, second the Name, 3rd is the group
	//echo $sql;
	$sqloption= "<select name='".$name."' id='".$name."' onChange=\"validateNumber(this);\" lang=false ><option></option>";
	$lastLabel = "";
	while ($row=mysql_fetch_assoc($result)){
		$first=mysql_field_name($result, 0);
		$second=mysql_field_name($result, 1);
		$third= mysql_field_name($result,2);

		if($lastLabel!=$row[$third]){
			if($lastLabel!=""){
				$sqloption.="</optgroup>";
			}
			$sqloption.="<optgroup label='$row[$third]'>";
			$lastLabel = $row[$third];
		}

			if ($option==$row[$first]) { $sel="selected=selected";}
			$sqloption=$sqloption .  "<option ".$sel." value='".$row[$first]."'>" .$row[$second]. "</option>";
			$sel="";
	}
		$sqloption.="</optgroup>";
		$sqloption=$sqloption . "</select>";
		echo $sqloption;
}
////////////////////////////////////////////////////////////
function generatePassword ($length = PASSWORD_SIZE){
	  // start with a blank password
	  $password = "";
	  // define possible characters
	  $possible = "0123456789abcdefghijklmnopqrstuvwxyz"; 
	  // set up a counter
	  $i = 0; 
	  // add random characters to $password until $length is reached
	  while ($i < $length) { 
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);	
		// we do not want this character if it's already in the password
		if (!strstr($password, $char)) { 
		  $password .= $char;
		  $i++;
		}
	  }
	  // done!
	  return $password;
}
////////////////////////////////////////////////////////////
function setDate($L_date,$L_dateFormat=DATE_FORMAT){//sets a date in a format
	if(strlen($L_date)>0){
		$L_arrTemp = explode(" ",$L_date);
		$L_strDate = $L_arrTemp[0]; // 2007-07-21 year month day
		$L_arrDate = explode("-",$L_strDate);// split date 
		$L_strYear =  $L_arrDate[0];
		$L_strMonth = $L_arrDate[1];
		$L_strDay = $L_arrDate[2];
		
		if($L_dateFormat == 'yyyy-mm-dd'){//default
		    return $L_arrTemp[0];
        }
		elseif($L_dateFormat == "dd-mm-yyyy"){//day month year
			$returnDate = $L_strDay."-".$L_strMonth."-".$L_strYear;
			return $returnDate;
		}
		elseif($L_dateFormat == "mm-dd-yyyy"){//month day year
			$returnDate = $L_strMonth."-".$L_strDay."-".$L_strYear;
			return $returnDate;
		}
	}
	else return false;
}
////////////////////////////////////////////////////////////
function getTypeName($type){//get the type name
	if ($type==TYPE_OFFER) $type=_("offer");
	else $type=_("need");
	
	return $type;
}
////////////////////////////////////////////////////////////
function getTypeNum($type){//get the type in number
	if ($type==_("offer")){
		$type=TYPE_OFFER;
	}
	else $type=TYPE_NEED;
	
	return $type;
}
////////////////////////////////////////////////////////////
function getLocationName($location){//get the location name
    if (isset($location)&&is_numeric($location)) {
        global $ocdb;
        $query="select name from ".TABLE_PREFIX."locations where idLocation=$location Limit 1";
		$result=$ocdb->query($query);
		if (mysql_num_rows($result)){
			$row=mysql_fetch_assoc($result);
			return $row["name"] ;
		}
		else return "";//nothing returned for that item
	} else return "";//nothing returned for that item
}
////////////////////////////////////////////////////////////
function getLocationFriendlyName($location){//get the location name
    if (isset($location)&&is_numeric($location)) {
        global $ocdb;
        $query="select friendlyName from ".TABLE_PREFIX."locations where idLocation=$location Limit 1";
		$result=$ocdb->query($query);
		if (mysql_num_rows($result)){
			$row=mysql_fetch_assoc($result);
			return $row["friendlyName"] ;
		}
		else return "";//nothing returned for that item
	} else return "";//nothing returned for that item
}
////////////////////////////////////////////////////////////
function getLocationParent($location){//get the location name
    if (isset($location)&&is_numeric($location)) {
        global $ocdb;
        $query="select idLocationParent from ".TABLE_PREFIX."locations where idLocation=$location Limit 1";
		$result=$ocdb->query($query);
		if (mysql_num_rows($result)){
			$row=mysql_fetch_assoc($result);
			return $row["idLocationParent"] ;
		}
		else return 0;//nothing returned for that item
	} else return 0;//nothing returned for that item
}
////////////////////////////////////////////////////////////
function getLocationNum($location){//get the location in number
    if (isset($location)) {
        global $ocdb;
        $query="select idLocation from ".TABLE_PREFIX."locations where lower(friendlyName)='".strtolower(u($location))."' Limit 1";
		$result=$ocdb->query($query);
		if (mysql_num_rows($result)){
			$row=mysql_fetch_assoc($result);
			return $row["idLocation"] ;
		}
		else return 0;//nothing returned for that item
	} else return 0;//nothing returned for that item
}
////////////////////////////////////////////////////////////
function buildEmailBodyHTML($var_array){
    $filename = SITE_ROOT.'/content/email/'.LANGUAGE.'/template.html';  
    if (!file_exists($filename))
        $filename = SITE_ROOT.'/content/email/en_EN/template.html';
    
    $fd = fopen ($filename, "r");
    $mailcontent = fread ($fd, filesize ($filename));
    
    foreach ($var_array as $key=>$value)
    {
    $mailcontent = str_replace("%$value[0]%", $value[1],$mailcontent);
    }
    
    $array_content[]=array("DATE", Date("l F d, Y"));
    $array_content[]=array("SITE_NAME", SITE_NAME);
    $array_content[]=array("SITE_URL", SITE_URL);
    
    foreach ($array_content as $key=>$value)
    {
    $mailcontent = str_replace("%$value[0]%", $value[1],$mailcontent);
    }
        
    $mailcontent = stripslashes($mailcontent);
    
    fclose ($fd);
    
    return $mailcontent;
}
////////////////////////////////////////////////////////////
function sendEmail($to,$subject,$body){//send email using smtp from gmail
	sendEmailComplete($to,$subject,$body,NOTIFY_EMAIL,SITE_NAME);
}
////////////////////////////////////////////////////////////
function sendEmailComplete($to,$subject,$body,$reply,$replyName){//send email using smtp from gmail
	$mail             = new PHPMailer();
	$mail->IsSMTP();
	
    //SMTP HOST config
	if (SMTP_HOST!=""){
		$mail->Host       = SMTP_HOST;              // sets custom SMTP server
    }
    
    //SMTP PORT config
	if (SMTP_PORT!=""){
		$mail->Port       = SMTP_PORT;              // set a custom SMTP port
    }
    
	//SMTP AUTH config
	if (SMTP_AUTH==true){
		$mail->SMTPAuth   = true;                   // enable SMTP authentication
		$mail->Username   = SMTP_USER;              // SMTP username
		$mail->Password   = SMTP_PASS;              // SMTP password
    }
    
	//GMAIL config
	if (GMAIL==true){
		$mail->SMTPAuth   = true;                   // enable SMTP authentication
		$mail->SMTPSecure = "ssl";                  // sets the prefix to the server
		$mail->Host       = "smtp.gmail.com";       // sets GMAIL as the SMTP server
		$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
		$mail->Username   = GMAIL_USER;                     // GMAIL username
		$mail->Password   = GMAIL_PASS;                     // GMAIL password
    }
	
	$mail->From       = NOTIFY_EMAIL;
	$mail->FromName   = "no-reply ".SITE_NAME;
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	
	$mail->AddReplyTo($reply,$replyName);//they answer here
	$mail->AddAddress($to,$to);
	$mail->IsHTML(true); // send as HTML

	if(!$mail->Send()) {//to see if we return a message or a value bolean
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else return false;
	 // echo "Message sent! $to";	
}
////////////////////////////////////////////////////////////
function encode_str ($input){//converts the input into Ascii HTML, to ofuscate a bit
    for ($i = 0; $i < strlen($input); $i++) {
         $output .= "&#".ord($input[$i]).';';
    }
    //$output = htmlspecialchars($output);//uncomment to escape sepecial chars
    return $output;
}
////////////////////////////////////////////////////////////
function mathCaptcha(){//generates a captcha for the form
	$first_number=mt_rand(1, 94);//first operation number
	$second_number=mt_rand(1, 5);//second operation number
	
	$_SESSION["mathCaptcha"]=($first_number+$second_number);//operation result
	
	$operation=" <b>".encode_str($first_number ." + ". $second_number)."</b>?";//operation codifieds
	
	echo _("How much is")." ".$operation;			
}
////////////////////////////////////////////////////////////
function isEmail($email){//check that the email is correct
	$pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/";
	if(preg_match($pattern, $email) > 0 and strpos($email,"upenn.edu") > 0 || strpos($email,"pennua.org") > 0) return true;
	else return false;
}

////////////////////////////////////////////////////////////
function jsRedirect($url){//simple JavaScript redirect
	echo "<script language='JavaScript' type='text/javascript'>location.href='$url';</script>";
}
////////////////////////////////////////////////////////////
function alert($msg){//simple JavaScript alert
	echo "<script language='JavaScript' type='text/javascript'>alert('$msg');</script>";
}
////////////////////////////////////////////////////////////
function removeRessource($_target) {//remove the done file
    //file?
    if( is_file($_target) ) {
        if( is_writable($_target) ) {
            if( @unlink($_target) ) {
                return true;
            }
        }
        return false;
    }
    //dir recursive
    if( is_dir($_target) ) {
        if( is_writeable($_target) ) {
            foreach( new DirectoryIterator($_target) as $_res ) {
                if( $_res->isDot() ) {
                    unset($_res);
                    continue;
                }
                if( $_res->isFile() ) {
                    removeRessource( $_res->getPathName() );
                } elseif( $_res->isDir() ) {
                    removeRessource( $_res->getRealPath() );
                }
                unset($_res);
            }
            if( @rmdir($_target) ) {
                return true;
            }
        }
        return false;
    }
}
////////////////////////////////////////////////////////////
function hackerDefense(){
	if (!isset($_SESSION['admin']) ){//only for users the admin can do whatever he wants
		// begin hacker defense
		$notAllowedExp = array(	
				'/<[^>]*script.*\"?[^>]*>/','/<[^>]*style.*\"?[^>]*>/',
				'/<[^>]*object.*\"?[^>]*>/','/<[^>]*iframe.*\"?[^>]*>/',
				'/<[^>]*applet.*\"?[^>]*>/','/<[^>]*window.*\"?[^>]*>/',
				'/<[^>]*docuemnt.*\"?[^>]*>/','/<[^>]*cookie.*\"?[^>]*>/',
				'/<[^>]*meta.*\"?[^>]*>/','/<[^>]*alert.*\"?[^>]*>/',
				'/<[^>]*form.*\"?[^>]*>/','/<[^>]*php.*\"?[^>]*>/','/<[^>]*img.*\"?[^>]*>/'
				);//not allowed in the system
							
		foreach ($_POST as $postvalue) {	//checking posts				
			foreach ($notAllowedExp as $exp){ //checking there's no matches
				if ( preg_match($exp, $postvalue) ){//die!!!
					header("Location: ".SITE_URL."/content/error-msg.php?msg=1");
					die (_("There was a problem with your post. Please do not use active code")." <a href=\"javascript:history.go(-1)\">"._("Go Back")."</a>");
				}
			}
		}
		// end hacker defense 	
	}
}
////////////////////////////////////////////////////////////
function getPrice($amount){//returns the price for the item in the correct format
	return str_replace(array("AMOUNT","CURRENCY"),array($amount,CURRENCY),CURRENCY_FORMAT);
	//return $amount;
}
////////////////////////////////////////////////////////////
function isSpam($name,$email,$comment){//return if something is spam or not using akismet, and checking the spam list
	global $ocdb;
	$res=$ocdb->getValue("SELECT idPost FROM ".TABLE_PREFIX."posts p where isAvailable=2  and email='$email' LIMIT 1","none");//check spam tags
	if ($res==false){//nothing found
		if (AKISMET!=""){
			$akismet = new Akismet(SITE_URL ,AKISMET);//change this! or use defines with that name!
			$akismet->setCommentAuthor($name);
			$akismet->setCommentAuthorEmail($email);
			$akismet->setCommentContent($comment);
			return $akismet->isCommentSpam();
		}
		else return false;//we return is not spam since we do not have the api :(	
	}
	else return true;//ohohoho SPAMMER!
	
}
////////////////////////////////////////////////////////////
function isInSpamList($ip){//return is was taged as spam (in /manage is where we tag)
	global $ocdb;
	$res=$ocdb->getValue("SELECT idPost FROM ".TABLE_PREFIX."posts p where isAvailable=2  and ip='$ip' LIMIT 1","none");
	if ($res==false) return false;//nothing found
	else return true;//ohohoho SPAMMER!
} 

////////////////////////////////////////////////////////////
function deleteCache(){//delete cache
	$cache=new fileCache(CACHE_EXPIRE,CACHE_DATA_FILE);
	$cache->deleteCache(0);//deletes everything
	unset ($cache);
}
////////////////////////////////////////////////////////////
function rssReader($url,$maxItems=15,$cache,$begin="",$end=""){//read RSS from the url and cache it
    $cache= (bool) $cache;
    if ($cache){
        $cacheRSS= new fileCache(CACHE_EXPIRE,CACHE_DATA_FILE);//seconds and path
        $out = $cacheRSS->cache($url);//getting values from cache
    }else $out=false;
    
    if (!$out) {	//no values in cache
        $rss = simplexml_load_file($url);
        $i=0;
        if($rss){
            $items = $rss->channel->item;
            foreach($items as $item){
                if($i==$maxItems){
                    if ($cache) $cacheRSS->cache($url,$out);//save cache	
                    return $out; 
                }
                else $out.=$begin.'<a href="'.$item->link.'" target="_blank" >'.$item->title.'</a>'.$end;
                $i++;
            }//for each
        }//if rss      		
    }
    return $out;
}

////////////////////////////////////////////////////////////
function check_images_form(){//get values by reference to allow change them. Used in new item and manage item
    //image check 
    $image_check=1;
    if (MAX_IMG_NUM>0){	//image upload active if there's more than 1
		$types=split(",",IMG_TYPES);//creating array with the allowed types print_r ($types);
		
		for ($i=1;$i<=MAX_IMG_NUM && is_numeric($image_check);$i++){//loop for all the elements in the form
		    
		    if (file_exists($_FILES["pic$i"]['tmp_name'])){//only for uploaded files

			    $imageInfo = getimagesize($_FILES["pic$i"]["tmp_name"]);
			    $file_mime = strtolower(substr(strrchr($imageInfo["mime"], "/"), 1 ));//image mime
			    $file_ext  = strtolower(substr(strrchr($_FILES["pic$i"]["name"], "."), 1 ));//image extension

			    if ($_FILES["pic$i"]['size'] > MAX_IMG_SIZE) {//control the size
				     $image_check=_("Picture")." $i "._("Upload pictures max file size")." ".(MAX_IMG_SIZE/1000000)."Mb";				
			    }
			    elseif (!in_array($file_mime,$types) || !in_array($file_ext,$types)){//the size is right checking type and extension
				     $image_check=_("Picture")." $i no "._("format")." ".IMG_TYPES;				
			    }//end else		
			    
			    $image_check++;

			}//end if existing file
		}//end loop		
	}//end image check
	return $image_check;	
}
////////////////////////////////////////////////////////////
function upload_images_form($idPost,$title,$date=0){//upload image files from the form.  Used in new item and manage item
    //images upload and resize
	if (MAX_IMG_NUM>0){	
		
		//create dir for the images
		$date=explode('-',$date);
		if (count($date)==3){ //there's a date where needs to be uploaded
			$imgDir=$date[2].'/'.$date[1].'/'.$date[0].'/'.$idPost;
		}
		else $imgDir=date("Y").'/'.date("m").'/'.date("d").'/'.$idPost; //no date
		
		$up_path=IMG_UPLOAD_DIR.$imgDir;
		umask(0000);
		mkdir($up_path, 0755,true);//create folder for item
		
		
		$needFolder=false;//to know if it's needed the folder
		//upload images
		for ($i=1;$i<=MAX_IMG_NUM;$i++){
		    if (file_exists($_FILES["pic$i"]['tmp_name'])){//only for uploaded files
			    $file_name = $_FILES["pic$i"]['name'];
			    $file_name = friendly_url($title).'_'.$i. strtolower(substr($file_name, strrpos($file_name, '.')));
			    $up_file=$up_path."/".$_FILES["pic$i"]['name'];
			
			    if (move_uploaded_file($_FILES["pic$i"]['tmp_name'],$up_file)){ //file uploaded
				    //resizing images
				    $thumb=new thumbnail($up_file); 
				    $thumb->size_width(IMG_RESIZE);	   // set width  for thumbnail			   
				    $thumb->save($up_path."/".$file_name);
				    unset($thumb);
				    //create thumb
				    $thumb=new thumbnail($up_file); 
				    $thumb->size_width(IMG_RESIZE_THUMB);	   // set biggest width for thumbnail				   
				    $thumb->save($up_path."/thumb_$file_name");
				    unset($thumb);
				    @unlink($up_file);//delete old file	
				    $needFolder=true;
			    }
			}//end if file exists
		}
		if (!$needFolder) @rmdir($up_path);//the folder is not needed no files uploaded
	}
	//end images
}
////////////////////////////////////////////////////////////
function getPostImages($idPost,$date,$just_one=false,$thumb=false){
	$no_pic=SITE_URL."/images/no_pic.png";
	$date=explode('-',$date);
	if (count($date)==3){//is_date
		$types=split(",",IMG_TYPES);//creating array with the allowed images types
		
		$imgUrl=SITE_URL.IMG_UPLOAD;//url for the image
		$imgPath=SITE_ROOT.IMG_UPLOAD;//path of the image
		
		$imgDir=$date[2].'/'.$date[1].'/'.$date[0].'/'.$idPost.'/';	//$imgDir=$idPost.'/';
		
		$files = scandir($imgPath.$imgDir);
		foreach($files as $img){//searching for images
			$file_ext  = strtolower(substr(strrchr($img, "."), 1 ));//get file ext
			if (in_array($file_ext,$types))$images[]=$img;//we only keep images with allowed ext
		}
		//print_r($images);
		if (count($images)>0){//there's at least 1 image
			foreach($images as $img){
				
				$is_thumb=(substr($img,0,6)=='thumb_');
					
				if ($just_one){//we want just one image
					if (!$thumb && !$is_thumb) return $imgUrl.$imgDir.$img;//first image match
					elseif($thumb && $is_thumb) return $imgUrl.$imgDir.$img;//first thumb match
				}
				else{//we want all the images
					if (!$thumb && !$is_thumb) {//images and thumbs
						$r_images[]=array($imgUrl.$imgDir.$img,$imgUrl.$imgDir.'thumb_'.$img);//images array
					}
					elseif($thumb && $is_thumb){//only thumbs
						$r_images[]=$imgUrl.$imgDir.$img;//thumbs array
					}
				}
					
			}
		}
		elseif($thumb) return $no_pic;//nothing in the folder
		
		return $r_images;
	}//no date :(
	else return $no_pic;
}

////////////////////////////////////////////////////////////
function deletePostImages($idPost,$date=0){
	$dateD=explode('-',$date);
	if (count($dateD)!=3){// we do not have the date for the post retrieving from DB
		global $ocdb;
		$date=setDate($ocdb->getValue("select insertDate from ".TABLE_PREFIX."posts where idPost=$idPost Limit 1",'none'));
		$dateD=explode('-',$date);
	}
	
    if (count($dateD)==3){
    	$imgPath=SITE_ROOT.IMG_UPLOAD.$dateD[2].'/'.$dateD[1].'/'.$dateD[0].'/'.$idPost;//path images
    	if (is_dir($imgPath)) removeRessource($imgPath);//delete	
    }
    
    return $date;//we return the date to reuse in other places
}

////////////////////////////////////////////////////////////
function mediaPostDesc($the_content){//from a description add the media
//using http://www.robertbuzink.nl/journal/2006/11/23/youtube-brackets-wordpress-plugin/
    if (VIDEO){
        $stag = "[youtube=http://www.youtube.com/watch?v=";
        $etag = "]";
        $spos = strpos($the_content, $stag);
        if ($spos !== false){
            $epos = strpos($the_content, $etag, $spos);
            $spose = $spos + strlen($stag);
            $slen = $epos - $spose;
            $file  = substr($the_content, $spose, $slen);    
			//youtube
            $tags = '<object width="425" height="350">
                    <param name="movie" value="'.$file.'"></param>
                    <param name="wmode" value="transparent" ></param>
                    <embed src="http://www.youtube.com/v/'. $file.'" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed>
                    </object>';    
            $new_content = substr($the_content,0,$spos);
            $new_content .= $tags;
            $new_content .= substr($the_content,($epos+1));
           
            if ($epos+1 < strlen($the_content)) {//reciproco
                $new_content = mediaPostDesc($new_content);
            }
            return $new_content;
        }
        else return $the_content;
    }
    else return $the_content;
}
?>
