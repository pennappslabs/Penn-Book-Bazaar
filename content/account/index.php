<?php
require_once('../../includes/header.php');

$account = Account::createBySession();
if ($account->exists){
    $name = $account->name;
    $email = $account->email;
} else {
    header("Location: ".accountLoginURL());
    die();
}
?>
<h3><?php echo _("My Posts");?></h3>
<div class="item">
<?php
$query="select idPost,title,type,friendlyName,password,isConfirmed,isAvailable,
        (select friendlyName from ".TABLE_PREFIX."categories where idCategory=c.idCategoryParent limit 1) parent
		    from ".TABLE_PREFIX."posts p 
		    inner join ".TABLE_PREFIX."categories c
		    on c.idCategory=p.idCategory
            where p.email = '$email'
            order by title";

$result=$ocdb->query($query);
if (mysql_num_rows($result)){
	while ($row=mysql_fetch_assoc($result)){
	    $post_id=$row["idPost"];
    	$title=$row["title"];
    	$postTitle=friendly_url($title);
    	$postTypeName=getTypeName($row["type"]);
    	$fcategory=$row["friendlyName"];
    	$parent=$row["parent"];
    	$postPassword=$row["password"];
        $confirmed=$row["isConfirmed"];
        $active=$row["isAvailable"];
   		
    	$postUrl=itemURL($post_id,$fcategory,$postTypeName,$postTitle,$parent);
    	
        echo '<p><strong><a href="'.SITE_URL.$postUrl.'" target="_blank">'.$title.'</a></strong><br />';
        if ($confirmed){
            echo '<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=edit" target="_blank">'._("Edit").'</a>'.SEPARATOR.'';
            if ($active) echo '<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=deactivate" target="_blank">'._("Deactivate").'</a>'.SEPARATOR.'';
            else echo '<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=activate" target="_blank">'._("Activate").'</a>'.SEPARATOR.'';
        	echo '<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=delete" target="_blank">'._("Delete").'</a>';
        } else echo '<a href="'.SITE_URL.'/manage/?post='.$post_id.'&amp;pwid='.$postPassword.'&amp;action=confirm" target="_blank">'._("Confirm").'</a>';
    	echo '</p>';
    }
}
?>
</div>
<?php
require_once('../../includes/footer.php');
?>