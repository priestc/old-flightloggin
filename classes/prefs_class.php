<?php
include "main_classes.php";

class prefs extends auth
{
	function submit_prefs($post)
	{
		//var_dump($post);
		
		$this->email = $post['email'];									//must be escaped
		$this->backup_email = $post['backup_email'];							//must be escaped
		$this->real_name = $post['real_name'];								//must be escaped
		$this->dob = empty($post['dob']) ? "0000-00-00" : date("Y-m-d", strtotime($post['dob']));	//processed, safe
		$this->style = $post['style'];									//dropdown, safe
		$this->mode = $post['mode'];									//same
		$this->date_format = $post['date_format'];							//same
		$this->logbook_per_page = $post['logbook_per_page'];						//same
		$this->backup_frequency = $post['backup_frequency'];						//same
		

		$type_currencies = explode("&&", $post['types']);				//array containing each type rated type. generated in the prefrences page
		
		$currency_string = array();
		
		for($i=0;$i<sizeof($type_currencies);$i++)					//goes through each type, and sees if it has been checked for night and general currency
		{
			if($post["{$type_currencies[$i]}-gen"] == "1")
				$currency_string[] = "{$type_currencies[$i]}^g";
				
			if($post["{$type_currencies[$i]}-night"] == "1")
				$currency_string[] = "{$type_currencies[$i]}^n";
		}
		
		$this->type_currency_string = implode("*", $currency_string);
		
		$this->currency_string = $post['a'] . $post['b'] . $post['c'] . $post['d'] . $post['e'] . $post['f'] . $post['g'] . $post['h'] . $post['i'] . $post['j'] . $post['k'] .
		 				$post['l'] . $post['m'] . $post['n'] . $post['o'] . $post['p'] . $post['q'] . $post['r'] . $post['s'] . $post['t'] . $post['u'] .
		 				$post['v'] . $post['w'] . $post['x'] . $post['y'] . $post['z'] . $post['A'] . $post['B'] . $post['C'] . $post['D'] . $post['E'] .
		 				$post['F'] . $post['G'] . $post['H'] . $post['I'] . $post['J'] . $post['K'] . $post['L'] . $post['M'] . $post['N'];
		 				
		

		$sql = sprintf("UPDATE `pilots` SET
			`email` = '%s',
			`real_name` = '%s',
			`dob` = '%s',
			`currency_string` = '%s',
			`mode` = '%s',
			`style` = '%s',
			`logbook_per_page` = '%s',
			`date_format` = '%s',
			`type_currency_string` = '%s',
			`backup_frequency` = '%s',
			`backup_email` = '%s'
			WHERE `pilot_id` ='%s' AND pass_hash = '%s'",
			
			mres($this->email),
			mres($this->real_name),
				 $this->dob,
				 $this->currency_string,
				 $this->mode,
				 $this->style,
				 $this->logbook_per_page,
				 $this->date_format,
				 $this->type_currency_string,
				 $this->backup_frequency,
			mres($this->backup_email),
				 $this->pilot_id,
				 $this->salted_hash
			);
	
		mysql_query($sql);
		
		if(!empty($post['new_pass1']) && $post['new_pass1'] == $post['new_pass2'])
		{
			$this->change_password($post['old_pass'], $post['new_pass1']);
		}	
	}
	
	function get_categories()
	{
		$sql = "SELECT DISTINCT category_class
			FROM planes
			WHERE pilot_id = {$this->pilot_id}
			ORDER BY category_class";
			
		$result = mysql_query($sql);
		
		$return_array = array();
		
		while($array = mysql_fetch_array($result))
		{
			if(!($array[0] == "15" || $array[0] == "16" || $array[0] == "1" || $array[0] == "18" || $array[0] == "17"))	//dont include sims and ftd's or SEL
				$return_array[] = $array[0];
		
		}
	
		return $return_array;
	
	}
	
	function get_type_ratings()
	{
		$sql = "SELECT DISTINCT `type`
			FROM planes
			WHERE pilot_id = {$this->pilot_id} AND type_rating = 1
			ORDER BY category_class";
			
		$result = mysql_query($sql);
		
		$return_array = array();
		
		while($array = mysql_fetch_array($result))
		{
			$return_array[] = $array[0];
		}
	
		return $return_array;
	
	}
	
	function get_tailwheel_classes()					//returns a fixed category class so it works with the switchout functions
	{
		$sql = "SELECT DISTINCT category_class
			FROM planes
			WHERE pilot_id = {$this->pilot_id} AND tailwheel = 1
			ORDER BY category_class";
			
		$result = mysql_query($sql);
		
		$return_array = array();
		
		while($array = mysql_fetch_array($result))
		{
			if($array[0] == 1)					//if SEL (1) make it 19, if it's MEL (2) make it 20
				$return_array[] = 19;
				
			elseif($array[0] == 2)
				$return_array[] = 20;
		}

		return $return_array;

	}
	
	function change_password($old, $new)
	{
		$old_hash = sha256($old . $this->salt);
		$new_hash = sha256($new . $this->salt);
	
		if($this->salted_hash == $old_hash)				//checks to see if the user's password in the cookie matches the one they just gave.
		{
			$this->password = $new;					//set this variable so the email function can email it to the user.
			
			$sql = "UPDATE pilots SET 
				pass_hash = \"$new_hash\"
				WHERE pilot_id = {$this->pilot_id} LIMIT 1";
				
			mysql_query($sql) ? $this->salted_hash = $new_hash : die("could not change password");
			
			$this->get_prefs();
			$this->set_cookies();
			$this->email_pass();
		}
		else
		{
			print "Error: Incorrect old password";
		}
	}
}
?>
