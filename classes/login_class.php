<?php
include "main_classes.php";

class register extends auth
{
	function register($email, $password, $cookie_type)
	{
		$this->email = $email;
		$this->password = $password;
		$this->cookie_type = $cookie_type;
		$this->salted_hash = md5($password . $salt);
		
		if($this->does_exists())
			return false;
		else
		{
			$sql = sprintf("INSERT INTO pilots (pass_hash, email)
					VALUES('%s','%s')",
					mysql_real_escape_string($this->salted_hash),
					mysql_real_escape_string($email)
					);

			$result = mysql_query($sql);
			
			if($result)				//registration successful, email user the password, authenticate, and set the cookies
			{
				$this->auth_by_email_and_password($email, $password, $cookie_type);
				$this->email_pass();
				$this->set_cookies();
			}
			else	return false;			//failure
		}
			
	}
	
	function does_exists()
	{
		$sql = "SELECT pilot_id FROM pilots WHERE `email` = '{$this->email}'";
		
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);
		
		return !empty($row);
	}
	
	function email_pass()
	{
		return mail("{$this->email}","Your OnlineLogbook.net Password","your password is {$this->password}\n\nhttp://onlinelogbook.net");
	}
	
	function reset_pass($email)
	{
		$this->email = $email;

		$chars = "234567890_abcdefghijkmnopqrstuvwxyz.ABCDEFGHIJKLMNOPQRSTUVWXYZ-";
		$i = 0;
		$new_password = "";
		while ($i <= 8)
		{
			$new_password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
	
		$this->password = $password;
		
		$salted_hash = md5($password . $salt);
		
		$sql = sprintf(
			"UPDATE pilots
			SET pass_hash = '%s'
			WHERE email='%s'
			LIMIT 1",
			$salted_hash,
			mysql_real_escape_string($email)
			);
		
		mysql_query($sql);

		$this->email_pass();
	}
}
?>
