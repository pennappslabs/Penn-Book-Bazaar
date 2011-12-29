<?php
	if ($_POST){//try to login
	    require_once('../includes/functions.php');//loading functions
	    
	    if (ADMIN==cP('user') && ADMIN_PWD==cP('pwd')){//it's the same as in config.php?
			$_SESSION['admin']=cP('user');//setting the session
			header("Location: index.php");//redirect to the admin home
		}//else echo "MEC!!";	
	}
	require_once('header.php');
?>
<h2><?php echo _("Administration Login");?></h2>
<form action="login.php" method="post" onsubmit="return checkForm(this);" >
	<fieldset>
    	<p>
            <label><?php echo _("User");?>:</label>
            <input name="user" type="text" class="text-long" onblur="validateText(this);" lang="false" value=""  />
        </p>
        <p>
            <label><?php echo _("Password");?>:</label>
            <input name="pwd" type="password" class="text-long" onblur="validateText(this);"  lang="false" value="" />
        </p>
		<input type="submit" value="<?php echo _("Submit")?>" class="button-submit" />
	</fieldset>
</form>
<?php
require_once('footer.php');
?>
