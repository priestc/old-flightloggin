<?php

include "classes/main_classes.php";

class register extends auth
{
	function put_in_database($email, $password, $cookie_type)
	{
		$this->email = $email;
		$this->password = $password;
		$this->cookie_type = $cookie_type;
		$this->salted_hash = sha256($password.$this->salt);
		
		$sql = sprintf("INSERT INTO pilots (pass_hash, email)
				VALUES('%s','%s')",
				mysql_real_escape_string($this->salted_hash),
				mysql_real_escape_string($email)
				);
				
		//print $sql;

		$result = mysql_query($sql);
			
		if($result)				//registration successful, email user the password, authenticate, and set the cookies
		{
			$this->auth_by_email_and_password($email, $password, $cookie_type);
			$this->email_pass();
			$this->set_cookies();
		}
		else	return false;			//failure, most likely email already exists
	}
}
