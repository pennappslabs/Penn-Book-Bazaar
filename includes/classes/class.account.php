<?php
/*
 * Name:	account
 * URL:		http:/openclassifieds.com/
 * Version:	v0.1
 * Date:	04/27/2010
 * Author:	Arnaldo Hidalgo
 * Support: http:/openclassifieds.com/forums/
 * License: GPL v3
 * Notes:	account class
 */

/////////////////////class account

class Account {
    public $id = null;
    public $name = null;
    public $email = null;
    public $FBis = null;
    public $location = null;
    public $active = 0;
    public $exists = false;
    public $status_password = false;

    //constructor
    // either an email or a FBid is passed in   
    function __construct($arg) {
      
      if ($arg != "") {
        if (strpos($arg, '@')) {
          $query = "SELECT email, idAccount, name, idLocation, active FROM ".TABLE_PREFIX."accounts
                    WHERE email = '".$arg."' LIMIT 1";
        } else {
          $query = "SELECT email, idAccount, name, idLocation, active FROM ".TABLE_PREFIX."accounts
    	              WHERE FBid = '".$arg."' LIMIT 1";
        }
        global $ocdb;
        $result = $ocdb->query($query);
        if (mysql_num_rows($result)) {
          $row = mysql_fetch_assoc($result);
    	    $this->email = $row['email'];
          $this->id = $row['idAccount'];
          $this->name = $row['name'];
          $this->location = $row['idLocation'];
          $this->active = $row['active'];
          $this->exists = true;
        } else
          $this->exists = false;
      } else 
        $this->exists = false;
    }
    
    public static function createBySession(){ //construct by session
        $account = new Account("");
        
        $id = $_SESSION["ocAccount"];
        if (is_numeric($id))
        {
            global $ocdb;
            
            $query = "SELECT idAccount,name,email,idLocation,active FROM ".TABLE_PREFIX."accounts
    		WHERE
    		idAccount = ".$id."
    		LIMIT 1";
    
    		$result=$ocdb->query($query);
            
    		if (mysql_num_rows($result))
            {               
    			$row=mysql_fetch_assoc($result);
            
    			$account->id = $id;
                $account->name = $row['name'];                
                $account->email = $row["email"];
                $account->location = $row['idLocation'];
                $account->active = $row['active'];
                $account->exists = true;
                
            } else $account->exists = false;
            
        } else $account->exists = false;
        
        return $account;
    }
    
    //Register new account
	public function Register($name,$email,$password)
	{
		$salt = substr(md5(uniqid(rand(), true)), 0, 3);//random hashed substring of length 3
		$password = sha1($salt.sha1($password));
    
	   if (!$this->exists)
       {
    		global $ocdb;
    		
            $token = $this->generateActivationToken();
            
            $ocdb->insert(TABLE_PREFIX."accounts (name,email,passhash,salt,activationToken)","'$name','$email','$password','$salt','$token'");

            $this->id = $ocdb->getLastID();
            $this->name = $name;
            $this->email = $email;
            $this->active = 0;
            $this->exists = true;
            
            return true;
            
        } else return false;
	}
    
    //Activate account by token
	public function Activate($token)
	{
	    if ($this->exists)
        {
    		global $ocdb;
    		
    		$query = "SELECT idAccount FROM ".TABLE_PREFIX."accounts
    		WHERE
    		(activationToken = '".$token."') AND (idAccount = ".$this->id.")";
    
    		$result=$ocdb->query($query);
    		if (mysql_num_rows($result))
            {            
                $query = "UPDATE ".TABLE_PREFIX."accounts
    			SET active = 1
    			WHERE
    			idAccount = ".$this->id."";
    	
    	        $ocdb->query($query);
                
                return true;
            } else return false;
        } else return false;
    }

  function FBlogOn($FBid) {
    global $ocdb;
    $query = "SELECT active FROM ".TABLE_PREFIX."accounts
              WHERE FBid = '".$FBid."' LIMIT 1";
    $result=$ocdb->query($query);
    if(mysql_num_rows($result)) {
      $row = mysql_fetch_assoc($result);
      $this->exists = true;
      $this->status_password = true;
      $_SESSION["ocAccount"] = $this->id;
      $this->active=1;
      //update lastSigninDate
      $query = "UPDATE ".TABLE_PREFIX."accounts
         			  SET lastSigninDate = CURRENT_TIMESTAMP()
        				WHERE idAccount = ".$this->id."";
      $ocdb->query($query);
      return true;
    } else {
      $this->exists = false;
      return false;
    }
  }

    //Logon
	function logOn($password,$remember=false,$rememberCookie="") {
    global $ocdb;
        
    $query = "SELECT active, passhash, salt FROM ".TABLE_PREFIX."accounts
		          WHERE email = '".$this->email."' LIMIT 1";

		$result=$ocdb->query($query);
		if (mysql_num_rows($result)) {            
			$row = mysql_fetch_assoc($result);
      $this->exists = true;
      $securepassword = sha1($row['salt'].sha1($password));
      if ($row["passhash"]==$securepassword) {
        $this->status_password = true;
        if ($row["active"]==1) {
          $_SESSION["ocAccount"] = $this->id;
          if ($remember) {
            if ($rememberCookie!="") {
              $expire=time()+60*60*24*30;
              setcookie($rememberCookie, $this->email, $expire);
            }
          } else if ($rememberCookie!="") setcookie($rememberCookie, "", time()-3600);
          $this->active = 1;
          //update lastSigninDate
          $query = "UPDATE ".TABLE_PREFIX."accounts
            			  SET lastSigninDate = CURRENT_TIMESTAMP()
            				WHERE idAccount = ".$this->id."";
          $ocdb->query($query);
          return true;
        } else {
          $this->active = 0;
          return false;
        }
      } else {
        $this->status_password = false;
        return false;
      }
    } else {
      $this->exists = false;
      return false;
    }
  }
    
	//Logout
	public static function logOut()
    {
		if(isset($_SESSION["ocAccount"]))
        {
			$_SESSION["ocAccount"] = null;
			
			unset($_SESSION["ocAccount"]);
		}
	}

    //Return account's activation token
	public function token()
	{
		global $ocdb;
		
		$query = "SELECT
				activationToken
				FROM
				".TABLE_PREFIX."accounts
				WHERE
				idAccount = ".$this->id."";
		       
        $result=$ocdb->query($query);
        
		if (mysql_num_rows($result))
        {
			$row=mysql_fetch_assoc($result);
            $token = $row['activationToken'];
            
			return $token;
        } else return null;
	}
    
	//Return the timestamp when the account was registered
	public function signupTimeStamp()
	{
		global $ocdb;
		
		$query = "SELECT
				createdDate
				FROM
				".TABLE_PREFIX."accounts
				WHERE
				idAccount = ".$this->id."";
		
        $result=$ocdb->query($query);
        
		if (mysql_num_rows($result))
        {
			$row=mysql_fetch_assoc($result);
	
			return ($row['createdDate']);
        } else return null;
	}
	
    //Return account's passsword
	public function password()
	{
		global $ocdb;
		
		$query = "SELECT
				password
				FROM
				".TABLE_PREFIX."accounts
				WHERE
				idAccount = ".$this->id."";
		
        $result=$ocdb->query($query);
        
		if (mysql_num_rows($result))
        {
			$row=mysql_fetch_assoc($result);
	
			return ($row['password']);
        } else return null;
	}
    
	//Update an account's email
	public function updateName($name)
	{
		global $ocdb;
		
		$query = "UPDATE ".TABLE_PREFIX."accounts
				SET name = '".$name."'
                ,lastModifiedDate = CURRENT_TIMESTAMP()
				WHERE
				idAccount = ".$this->id."";
		
        $this->name = $name;
        
		return $ocdb->query($query);
	}
	
	public function resetPassword()
	{
		global $ocdb;
    
	    //generates random new password
	    $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ023456789!@#$%";
	    srand((double)microtime()*1000000);
	    $i = 0;
	    $password = '' ;
	    while ($i < 8) {
	        $password = $password.substr($chars, rand() % strlen($chars), 1);
	        $i++;
	    }
    	
    	$salt = substr(md5(uniqid(rand(), true)), 0, 3);//random hashed substring of length 3
		$hashed_pwd = sha1($salt.sha1($password));
    
	    $query = "UPDATE ".TABLE_PREFIX."accounts
	    SET passhash = '".$hashed_pwd."'
	            ,salt = '".$salt."'
	            ,lastModifiedDate = CURRENT_TIMESTAMP()
	    WHERE
	    idAccount = ".$this->id."";
	    $ocdb->query($query);
	        
	    return $password;
	}
	
    //Update an account's password
	public function updatePassword($password)
	{
		global $ocdb;

    	$salt = substr(md5(uniqid(rand(), true)), 0, 3);//random hashed substring of length 3
		$hashed_pwd = sha1($salt.sha1($password));

	    $query = "UPDATE ".TABLE_PREFIX."accounts
	    		SET passhash = '".$hashed_pwd."'
	            ,salt = '".$salt."'
	            ,lastModifiedDate = CURRENT_TIMESTAMP()
	    		WHERE
	    		idAccount = ".$this->id."";
               
        return $ocdb->query($query);
	}
    
    //Helper functions
    
	//Function lostpass var if set will check for an active account.
	private function validateActivationToken($token,$lostpass=null)
	{
		global $ocdb;
		
		if($lostpass == null) 
		{	
			$query = "SELECT activationToken
					FROM ".TABLE_PREFIX."accounts
					WHERE active = 0
					AND
					activationToken ='".trim($token)."'
					LIMIT 1";
		} else {
			 $query = "SELECT activationToken
			 		FROM ".TABLE_PREFIX."accounts
					WHERE active = 1
					AND
					activationToken ='".trim($token)."' 
					LIMIT 1";
		}
		
		$result=$ocdb->query($query);
        
		if (mysql_num_rows($result)) return true;
		else return false;
	}
    
    //Generate an activation key 
	private function generateActivationToken()
	{
		$gen;
	
		do
		{
			$gen = md5(uniqid(mt_rand(), false));
		}
		while($this->validateActivationToken($gen));
	
		return $gen;
	}
}

?>
