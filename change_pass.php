<?
include "machine_information.php";

		if(!empty($_GET))
		{
			$email = $_GET['email'];
			$rot_password = $_GET['w'];
			$activate_hash = $_GET['hash'];
			
			$sql = "SELECT pending_pass_hash, pass_hash
				FROM pilots
				WHERE email = '" . mysql_real_escape_string($email) . "'";
				
			$result = mysql_query($sql);
			$row = mysql_fetch_row($result);
			
			$sql_pending = $row[0];
			$sql_hash_in_use = $row[1];
			
			if($sql_pending == $activate_hash)	//if pending hash equals the GET hash, then copy the pending hash to passhash, then get rid of pending hash, then login
			{
				$sql2 = "UPDATE pilots
					SET pass_hash = '" . mysql_real_escape_string($activate_hash) . "', 
					pending_pass_hash = NULL
					WHERE email = '" . mysql_real_escape_string($email) . "'";
					
				//print $sql2;
					
				mysql_query($sql2);

				header("Location: index.php?email=$email&w=$rot_password");
				exit;
				
			}
			elseif($sql_hash_in_use == $activate_hash)				//the activation hash is already set as the users pass hash, go to login
			{
				header("Location: index.php?email=$email&w=$rot_password");
				exit;
			}
			else									//incorrect hash, just go to index.php
			
				header("Location: index.php?activate_fail=1");
				exit;
		}

#########################################################################################################################################################################

		$email = $_POST['email'];
		
		$salt = "332sb!#f89h47#00deo";
		
		

		$chars = "abcdefghijkmnopqrstuvwxyz023456789ABCDEFGHJKLMNPQRSTUVWXYZ";

		srand((double)microtime()*1000000);

		$i = 0;
		$password = '' ;

		while ($i <= 9)
		{
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$password = $password . $tmp;
			$i++;
		}

		
		$salted_hash = sha256($password.$salt);
		
		######################################
		
		$sql = sprintf(
			"UPDATE pilots
			SET pending_pass_hash = '%s'
			WHERE email='%s'
			LIMIT 1",
			$salted_hash,
			mysql_real_escape_string($email)
			);
			
		mysql_query($sql);
		
		//print mysql_affected_rows();
		
		if(mysql_affected_rows() <= 0)					//if no rows are effected, then the email doesnt exist in the system
		{
			header("Location: index.php?email_dont_exist=1");		//phebitneod
			exit;
		}
	
		mail("$email","Please activate your OnlineLogbook.net password.",
		
			"<html>
				<head><title>Password Reset</title></head>
				<body>
				<p>
					Your new password is: <strong>$password</strong>
				</p>
				<p>
					Before you can use this new password, you must go
					<a href=http://onlinelogbook.net/change_pass.php?email={$email}&hash=". sha256($password.$salt) . "&w=" . str_rot13($password) . ">
					here</a> to activate the change.
				</p>
				</body>
			</html>",
		"From: webmaster@onlinelogbook.net\r\nContent-type: text/html; charset=iso-8859-1\r\nMIME-Version: 1.0\r\nReply-To: comments@onlinelogbook.net\r\nX-Mailer: PHP/" . phpversion() . "\r\n",
		"-f webmaster@onlinelogbook.net");
		
		//print "<a href=\"change_pass.php?email={$email}&hash=". sha256($password.$salt) . "&w=" . str_rot13($password) . "\">aaa</a><br>$password";
			
		header("Location: index.php?reset_success=1");
?>
