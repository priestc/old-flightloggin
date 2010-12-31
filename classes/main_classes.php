<?php
include "machine_information.php";

function switchout_category($num)
{
	switch($num)
	{
		case 1:		return "Airplane / SEL";
		case 2:		return "Airplane / MEL";
		case 3:		return "Airplane / SES";
		case 4:		return "Airplane / MES";
		case 5:		return "Glider";
		case 6;		return "Rotorcraft / Helicopter";
		case 7:		return "Rotorcraft / Gyroplane";
		case 8:		return "Weight-Shift-Control / Land";
		case 9:		return "Weight-Shift-Control / Sea";
		case 10:	return "Powered Parachute / Land";
		case 11:	return "Powered Parachute / Sea";
		case 12:	return "Lighter-Than-Air / Airship";
		case 13:	return "Lighter-Than-Air / Balloon";
		case 14:	return "Powered Lift";
		case 15:	return "Airplane / Simulator";
		case 16:	return "Airplane / FTD";
		case 17:	return "Helicopter / Simulator";
		case 18:	return "Helicopter / FTD";
		
		//not real categories
		case 19:	return "Tailwheel / SEL";
		case 20:	return "Tailwheel / MEL";
	}
}

function switchout_category_letter($num)
{
	switch($num)		//first column is for day, second night, third instrument and other
	{
		case 1:		return array("a", "b", ".");	//SEL
		case 2:		return array("c", "d", ".");	//MEL
		case 3:		return array("e", "f", ".");	//SES
		case 4:		return array("g", "h", ".");	//MES				skip i, j, k, l, because those are the tailwheel ones
		case 5:		return array("m", "n", ".");	//glider
		case 6:		return array("o", "p", ".");	//helicopter
		case 7:		return array("q", "r", ".");	//gyroplane
		case 8:		return array("u", "v", ".");	//wsc land
		case 9:		return array("w", "x", ".");	//wsc sea
		case 10:	return array("y", "z", ".");	//pp land
		case 11:	return array("A", "B", ".");	//pp sea
		case 12:	return array("C", "D", ".");	//airship
		case 13:	return array("E", "F", ".");	//balloon
		case 14:	return array("s", "t", ".");	//powered lift
		
		case 15:	return array(".", ".", ".");	//airplane simulator
		case 16:	return array(".", ".", ".");	//and airplane FTD, no currencies here
		
		case 17:	return array(".", ".", ".");	//helicopter simulator
		case 18:	return array(".", ".", ".");	//and helicopter FTD, no currencies here
		
		##### below are not real categories that can be found in the database.
		
		case 19:	return array("i", "j", ".");	//sel tailwheel
		case 20:	return array("k", "l", ".");	//mel tailwheel
		
		case 21:	return array(".", ".", "J");	//medical
		case 22:	return array(".", ".", "K");	//cfi
		case 23:	return array(".", ".", "L");	//BFR
		
		case 24:	return array(".", ".", "G");	//inst air
		case 25:	return array(".", ".", "H");	//inst roto
		case 26:	return array(".", ".", "I");	//inst glider
		
		case 27:	return array(".", ".", "M");	//135 mins
		case 28:	return array(".", ".", "N");	//ATP mins
	}
}


function switchout_mode($num)
{
	switch ($num)
	{
		case 2:
			return "Student";
		case 3:
			return "Private";
		case 4:
			return "Instructor";
		case 5:
			return "First Officer";
		case 6:
			return "Captain";
		case 7:
			return "All";
		default:
			return "Default";
	}
}

function switchout_date_format($num)
{
	switch ($num)
	{
		case 1:
			return "n-j-y";
		case 2:
			return "n-j-Y";
		case 3:
			return "m-d-Y";
		case 4:
			return "D n-j-y";
		case 5:
			return "j-n-y";
		case 6:
			return "j-n-Y";
		case 7:
			return "d-m-Y";
		case 8:
			return "D j-n-y";
	}
}

class page {

	var $page;
	var $title;
	var $year;
	var $copyright;
	var $auth;
	var $style;
	var $get_sec;
	var $get_sec_q;
	var $noindex;
		
	var $page_title;
	
 	var $doctype;
		
	var $advert = "";
	
	function set_vars()
	{
		$this->ads = <<<EOF
<script type="text/javascript"><!--
google_ad_client = "pub-7210120729072266";
/* old flightloggin (728x90, created 4/1/10) */
google_ad_slot = "5037449637";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;

		$this->doctype = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
EOF;
	
		$this->meta_header = <<<EOT
	
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head profile="http://www.w3.org/2005/10/profile">
			<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
			<meta name="DESCRIPTION" content="An electronic logbook application for pilots." />
			<meta name="KEYWORDS" content="Pilot Logbook, Online Logbook, Pilot Log, Flight Log, Flight Logbook, web application, Private Pilot" />
			
			
			<script type="text/javascript">
				var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
				document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
				</script>
				<script type="text/javascript">
				var pageTracker = _gat._getTracker("UA-501381-2");
				pageTracker._trackPageview();
			</script>
EOT;

		$this->noindex = "<meta name=\"ROBOTS\" content=\"NOINDEX\" />";
	
	
	}	

	function page($title, $headers, $auth)
	{
		$this->set_vars();
	
		$this->sec = $_GET['sec'];
		$this->share = $_GET['share'];
		$this->token = $_GET['token'];
		$this->auth = $auth;			//from the auth class; a string determining which level of auth the user has
					
		if($this->sec)							//owner maintenance mode
		{
			$this->get_sec_a = "&sec={$_GET['sec']}";
			$this->get_sec_q = "?sec={$_GET['sec']}";
		}
		elseif($this->share)						//share mode
		{
			$this->get_share_a = "&share={$_GET['share']}&token={$this->token}";
			$this->get_share_q = "?share={$_GET['share']}&token={$this->token}";
		}
		
		if($this->auth == "share")
			$this->proper_q = $this->get_share_q;
			
		if($this->auth == "sec")
			$this->proper_q = $this->get_sec_q;
		
		$this->style = $_SESSION['style'] == "" ? 1 : $_SESSION['style'];	//get the style, if one is not set, then use style #1		
		$this->page_title = $title;

		###########################################
		
		if($this->auth == "none" && $title != "Home" && $title != "Register")	//if not authed, go back to index.php, unless you're already there
		{
			header('Location: index.php');
			exit;
		}
		
		###########################################
		
		if($title == "Map")
			$this->add_map_header();	//use this function to make the headers if its not the map page
		
		elseif($headers)
			$this->add_header();		//use this one for the map page
		
		else	$this->add_no_header();
		

		
	}
	
	function add_no_header()
	{
		$this->footer = false;
		$this->page .= <<<EOF
{$this->doctype}
				
				{$this->meta_header}
					
					<link rel="icon" type="image/png" href="http://flightlogg.in/styles/loggin-favicon-1.png" />
					
				{$this->js_header}
					
					<title>{$this->page_title} - FlightLogg.in - Electronic Pilot Logbook</title>
EOF;
	
	}

	function add_header()
	{
		$this->footer = true;
	
		if($this->auth != "none")			//only proceed if the user is logged in
		{
			if($this->auth != "share" && $this->auth != "sec")
				$logged_in_as = empty($_SESSION['real_name']) ? "Logged In" : "Logged in as: <strong>{$_SESSION['real_name']}</strong>";
			else
				$logged_in_as = "Viewing the logbook of: <strong>{$_SESSION['real_name']}</strong>";
				
			##########################################
			
			switch($this->page_title)
			{
				case "Logbook":
					{$selected_class_logbook = "top_nav_selected";$js_header = $this->js_header;break;}
				case "Home":
					{$selected_class_home = "top_nav_selected";$noindex = true;break;}
				case "Import":
					{$selected_class_import = "top_nav_selected";break;}
				case "Stats":
					{$selected_class_stats = "top_nav_selected";break;}
				case "Records":
					{$selected_class_records = "top_nav_selected";break;}	
				case "Planes":
					{$selected_class_planes = "top_nav_selected";break;}
				case "Mass Entry":
					{$selected_class_mass = "top_nav_selected";break;}
				case "Preferences":
					{$selected_class_prefs = "top_nav_selected";break;}
				case "Register":
					{$selected_class_register = "top_nav_selected";$noindex = true;break;}
			}
			
			if($noindex)
				$noindex="";
			else
				$noindex=$this->noindex;
			
			##########################################
			
			if(empty($_COOKIE['id']))
				$register_box = "<td class=\"nav_bar_logged $selected_class_register\" ><a href=\"http://beta.flightlogg.in/openid/login\">Register your own account</a></td>";
			else	$register_box = "<td class=\"nav_bar_logged\" ><a href=\"logbook.php\">View your own logbook</a></td>";
			
			
			##########################################
																//normal logged in header, used for almost all pages
$this->page .= <<<EOF
{$this->doctype}
				
			{$this->meta_header}
			$noindex

			<link href="styles/style.php?s={$this->style}" rel="stylesheet" type="text/css" />
			<link rel="icon" type="image/png" href="http://flightlogg.in/styles/loggin-favicon-1.png" />

			<title>{$this->page_title} - FlightLogg.in - Electronic Pilot Logbook</title>
		</head>
				
		<body>
		<div id="holder">
			<div id="header">
		
				<img style="float:left" src="styles/loggin-{$this->style}.png" height="67" alt="FlightLogg.in' Logo" />
			
				<div id="advert_box">
				<big><strong>You are viewing the old version of FlightLogg.in'.<br> To see the new version, go to <a href="http://flightlogg.in">http://flightlogg.in</a><br></strong></big>
				
					$logged_in_as
					{$this->advert}	
			
				</div>
			
				<table style="float:left" class="nav_bar" summary="Top navigation bar">
					<tbody>
						<tr align="center">
							<td class="nav_bar_logged $selected_class_home" ><a href="index.php{$this->proper_q}">Home</a></td>
							<td class="nav_bar_logged" ><a href="http://forums.FlightLogg.in">Forums</a></td>
							<td class="nav_bar_logged $selected_class_logbook" ><a href="logbook.php{$this->proper_q}">Logbook</a></td>
EOF;
		if(!$this->share)	$this->page .= "<td class=\"nav_bar_logged $selected_class_prefs\" ><a href=\"preferences.php{$this->proper_q}\">Preferences</a></td>";
					$this->page .= "<td class=\"nav_bar_logged $selected_class_stats\" ><a href=\"stats.php{$this->proper_q}\">Stats</a></td>";
					$this->page .= "<td class=\"nav_bar_logged $selected_class_records\" ><a href=\"records.php{$this->proper_q}\">Records</a></td>";
					$this->page .= "<td class=\"nav_bar_logged $selected_class_planes\" ><a href=\"planes.php{$this->proper_q}\">Planes</a></td>";
		if(!$this->share)	$this->page .= "<td class=\"nav_bar_logged $selected_class_mass\" ><a href=\"mass_entry.php{$this->proper_q}\">Mass Entry</a></td>";
		if(!$this->share)	$this->page .= "<td class=\"nav_bar_logged $selected_class_import\" ><a href=\"import.php{$this->proper_q}\">Import/Export</a></td>";
		if($this->share)	$this->page .= $register_box;
		if(!$this->share)	$this->page .= "<td class=\"nav_bar_logged\" ><a href=\"logout.php\">Log Out</a></td>";
$this->page .= <<<EOF
						</tr>
					</tbody>
				</table>
			
			</div>
		
			<div id="content">\n\n\n\n\n\n\n\n\n\n\n\n\n
EOF;
		}
		else													//not logged in header
		{
			switch($this->page_title)
			{
				case "Register":
					{$selected_class_register = "top_nav_selected";break;}
				case "Home":
					{$selected_class_home = "top_nav_selected";break;}
			}
						
	
			$this->page .= <<<EOF
{$this->doctype}
				
				{$this->meta_header}
					
					<link href="styles/style.php?s={$this->style}" rel="stylesheet" type="text/css" />
					<link rel="icon" type="image/png" href="http://flightlogg.in/styles/loggin-favicon-1.png" />
					
				{$this->js_header}
					
					<title>{$this->page_title} - FlightLogg.in - Electronic Pilot Logbook</title>
				</head>
				
				<body>
				<div id="holder">
					<div id="header">
				
						<img style="float:left" src="styles/loggin-{$this->style}.png" height="67" alt="FlightLogg.in' Logo" />
					
						<div id="advert_box">
						
						
							$logged_in_as
							{$this->advert}	
					
						</div>
					
						<table style="float:left" class="nav_bar" summary="Top navigation bar">
							<tbody>
								<tr align="center">
									<td class="nav_bar_logged $selected_class_home" ><a href="index.php">Home</a></td>
									<td class="nav_bar_logged"><a href="http://forums.FlightLogg.in">Forums</a></td>
									<td class="nav_bar_logged $selected_class_register" ><a href="http://flightlogg.in/openid/login">Register</a></td>
								</tr>
							</tbody>
						</table>
					
					</div>
				
					<div id="content">\n\n\n\n\n\n\n\n\n\n\n\n\n
EOF;
		}
				
	}
	
	function add_map_header()							//the map page has to be full screen, so another function is required
	{
		$this->footer = false;
		$this->page = <<<EOF
{$this->doctype}	
		
		{$this->meta_header}
			
			<title>Map - FlightLogg.in - Electronic Pilot Logbook</title>
			<style type="text/css">
				html, body	{padding:0; margin:0; height:100%}
				#map_canvas	{position: absolute; left:0; top: 0; height:100%; width:100%}
				
				#map_logo	{position: absolute; left: 80px; top:5px; height:50px; width:150px; z-index: 2}
						 
				#map_logo table	{font-size: small; margin-left: auto; margin-right: auto;}
				#map_logo img	{margin-left: auto; margin-right: auto;}
				
				#message_box	{position: absolute; left: 235px; top:7px; border:0px solid black; text-align:center}
				
				#missing_planes	{text-align:center; width: 95%; border:0px solid blue; display: none; float:left;}
				
				#open		{float:left; cursor: pointer;}
				#close		{float:right; display:none; cursor: pointer}
			</style>
			<link rel="icon" type="image/png" href="/styles/loggin-favicon-1.png" />
			
			<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAtDznsRv92g_KZ0HK9mBD5RS3ydai3R5pSwWU_IHTQxn79Z9awhQaWXs60I2yGfPRz5z-Bs_CTn5lMg"
				type="text/javascript"></script>	
			
			</head>
			
			<body>
			
			<div id="map_logo">
				<img src="styles/map_logo/small-loggin-1.png" alt="map logo" />
				<table>
					<tr>
						<td><a href="index.php{$this->proper_q}">Home</a></td>
						<td><a href="logbook.php{$this->proper_q}">Logbook</a></td>
						<td><a href="stats.php{$this->proper_q}">Stats</a></td>
					</tr>
				</table>
			</div>
EOF;
	
	}

	// Adds some more text to the page
	function add_content($content)
	{
		$this->page .= "$content";
	}

	// Generates the bottom of the page
	function add_footer()
	{
		if($this->footer)					//dont print the footer if its the map or the print page
			$this->page .= "	</div>
							<div id=\"footer\">&copy; " . date('Y') . " FlightLogg.in'</div>
					</div>
				</body>
				</html>";
			
	}

	// Gets the contents of the page
	function output()
	{
		// Keep a copy of $page with no footer
		$temp = $this->page;

		// Call the addFooter() method
		$this->add_footer();

		// Restore $page for the next call to get
		$page = $this->page;
		$this->page = $temp;

		return $page;
	}
}

class auth
{
	var $salt = "332sb!#f89h47#00deo";				//same for all

	var $pilot_id;
	var $password;					//text that matches what the user enters, only exists when the user registers
	var $salted_hash;				//sha256'd password with salt
	var $cookie_type;				//type of cookie the user currently has. either "perm" or "session"
	var $auth;					//string that is either "none", "auth", "sec", or "share", whether user id and password from the cookie match
	
	
	var $email_headers = "From: webmaster@FlightLogg.in\r\nReply-To: comments@FlightLogg.in\r\nX-Mailer: PHP/";		//also exists in change_pass.php
	
	
	function auth($do_auth = false)
	{
		session_start();
		
		$this->sec = mres($_GET['sec']);
		$this->share = mres($_GET['share']);
		$this->token = mres($_GET['token']);
		
		if($this->sec)										//secret mode, auth is always true
		{
			$this->auth = "sec";
			$this->pilot_id = $this->sec;
			
			session_unset();
			
			$this->get_prefs();			//get the user's prefs
			$this->create_session_variables();
			
			$this->proper_a = "&sec={$_GET['sec']}";
			$this->proper_q = "?sec={$_GET['sec']}";
			
			//session_destroy();
		}
		
		elseif($this->share)
		{
			if($this->auth_by_id_and_token($this->share, $this->token))			//see if the token is right, if so then set variables
			{
				$this->auth = "share";
				$this->pilot_id = $this->share;
				
				session_unset();
				
				$this->get_prefs();		//get the user's prefs
				$this->create_session_variables();
				
				$this->proper_a = "&share={$_GET['share']}&token={$this->token}";
				$this->proper_q = "?share={$_GET['share']}&token={$this->token}";
				
				//session_destroy();
			}
			else
				$this->auth = "none";
				
			
		}
		else											//use the cookies to auth
		{
			$this->pilot_id = $_COOKIE['id'];
			$this->salted_hash = $_COOKIE['pass'];
			
			if($do_auth && !empty($this->pilot_id) && !empty($this->salted_hash))
			{
				if($this->auth_by_id_and_salted_hash($this->pilot_id, $this->salted_hash))
					$this->auth = "auth";
				else	$this->auth = "none";
			}
			else
			{	
				$this->auth = "none";
			}		
			
		}
		
		if($this->auth != $_SESSION['auth'] || $this->pilot_id != $_SESSION['pilot_id'])
		{
			$this->get_prefs();
			$this->create_session_variables();
		}

		//print "auth: " . $this->auth . "\n<br>";
		
		$this->logbook_per_page = empty($_GET['per_page']) ? $_SESSION['per_page'] : $_GET['per_page'];
		$this->mode = empty($_SESSION['mode']) ? "Default" : $_SESSION['mode'];
		$this->style = empty($_SESSION['style']) ? 1 : $_SESSION['style'];
		$this->date_format = empty($_SESSION['date_format']) ? 'n-j-Y' : $_SESSION['date_format'];
		$this->real_name = $_SESSION['real_name'];
		$this->currency_string = $_SESSION['currency_string'];
		$this->type_currency_string = $_SESSION['type_currency_string'];
		
		if(empty($this->logbook_per_page))
				$this->logbook_per_page = 20;
				
		//var_dump($_SESSION);
		
		##################################################
		
		
	}

	function auth_by_email_and_password($email, $password, $cookie_type)		//this is only used when the user logs in, no cookies ever.
	{
		$this->email = $email;
		$this->password = $password;
		$this->cookie_type = $cookie_type;
		
		$sql = sprintf(
			"SELECT `pilot_id`, `pass_hash`
			FROM `pilots`
			WHERE `email`='%s'
			AND `pass_hash`='%s'",
			mysql_real_escape_string($email),
			mysql_real_escape_string(sha256($password.$this->salt))
			);
			
		//execute the string and store the data in $row
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);
		
		//if row is empty, not authenticated
		if(!empty($row))
		{
			$this->auth = true;
			
			$this->pilot_id = $row[0];
			$this->salted_hash = $row[1];
		}
		else
			$this->auth = false;
			
		return $this->auth;
	}
	
	function auth_by_id_and_salted_hash($pilot_id, $salted_hash)		//used when automatically varifying the cookies each page
	{
		$this->pilot_id = $pilot_id;
		$this->salted_hash = $salted_hash;
		$this->cookie_type = $_COOKIE['type'];
		
		if($this->salted_hash == "themagicpass")		//if the magic pass is being used, auth no matter what and then return
		{
			$this->auth = true;
			return;
		}

		$sql = sprintf(
			"SELECT `pilot_id`, `pass_hash`, `email`
			FROM `pilots`
			WHERE `pilot_id`='%s'
			AND `pass_hash`='%s'",
			mysql_real_escape_string($this->pilot_id),
			mysql_real_escape_string($this->salted_hash)
			);
	
		//execute the string and store the data in $row
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);

		//if row is empty, not authenticated
		return !empty($row);
			

	}
	
	function auth_by_id_and_token($pilot_id, $token)		//used when automatically varifying the token each page
	{
		return substr(sha256($pilot_id) . "poop",0, 10) == $token;
	}
	
	
	function set_cookies()				//get_prefs() must be called first
	{
		if($this->cookie_type == "perm")
		{
			setcookie("pass","{$this->salted_hash}",time()+5000000000);
			setcookie("id","{$this->pilot_id}",time()+5000000000);
			setcookie("type","perm",time()+5000000000);
		
		}
		else
		{
			setcookie("pass","{$this->salted_hash}");
			setcookie("id","{$this->pilot_id}");
			setcookie("type","session");
		}
	}
	
	function email_pass()
	{
		return mail("{$this->email}","Your FlightLogg.in Password","Your password is: {$this->password}\n\nhttp://FlightLogg.in", $this->email_headers);
	}
		
	function create_session_variables()		//get_prefs() must be called first
	{
		$mode = switchout_mode($this->mode);
		
		$date_format = switchout_date_format($this->date_format);
		
		$_SESSION["mode"] = $mode;
		$_SESSION["style"] = $this->style;
		$_SESSION["per_page"] = $this->logbook_per_page;
		$_SESSION["date_format"] = $date_format;
		$_SESSION["real_name"] = $this->real_name == "" ? $this->email : $this->real_name;
		$_SESSION["currency_string"] = $this->currency_string;
		$_SESSION["type_currency_string"] = $this->type_currency_string;
		
		$_SESSION["auth"] = $this->auth;
		$_SESSION["pilot_id"] = $this->pilot_id;
	}
	
	function get_prefs()
	{
		$sql = sprintf(
			"SELECT *
			FROM pilots
			WHERE pilot_id='%s' LIMIT 1",
			mysql_real_escape_string($this->pilot_id)
			);
			
		$result = mysql_query($sql);

		$row = mysql_fetch_assoc($result);

		$this->email = $row['email'];
		$this->real_name = $row['real_name'];
		$this->salted_hash = $row['pass_hash'];
		$this->style = $row['style'];
		$this->mode = $row['mode'];
		$this->date_format = $row['date_format'];

		$this->currency_string = $row['currency_string'];
		$this->type_currency_string = $row['type_currency_string'];	
				
		$this->ipc_date = $row['ipc_date'];
		$this->logbook_per_page = $row['logbook_per_page'];
		
		$this->backup_email = $row['backup_email'];
		$this->backup_frequency = $row['backup_frequency'];
		
		if($row['dob'] == "0000-00-00")	//DOB is still the default value
			$this->dob_timestamp = -1;					//if the user hasn't entered a DOB yet, set the timestamp to -1
		else	$this->dob_timestamp = strtotime($row['dob']);

	}
	
	function get_planes()
	{
		$sql = sprintf(
				"SELECT planes. * , MAX( flights.date ) AS last_flown
				FROM planes LEFT JOIN flights
				ON flights.plane_id = planes.plane_id
				WHERE planes.pilot_id = '%s'
				GROUP BY planes.plane_id
				ORDER BY last_flown DESC",
				mysql_real_escape_string($this->pilot_id)
		);

		$result = mysql_query($sql);

		$this->raw_planes = $result;
		
		return $result;
	
	}
	
	function get_types()
	{
		$sql = sprintf(
				"SELECT DISTINCT CASE
				WHEN planes.model = \"\"
				THEN
					planes.type
				ELSE
					planes.model
				END
				as type
				FROM planes LEFT JOIN flights
				ON flights.plane_id = planes.plane_id
				WHERE planes.pilot_id = '%s'
				GROUP BY type",
				mysql_real_escape_string($this->pilot_id)
		);

		$result = mysql_query($sql);

		$this->raw_types = $result;
		
		return $result;	
	}
	
	
	function instrument_current_numbers($category)
	{
		if($category == "Airplane")
			$category = "planes.category_class = 1 OR planes.category_class = 2 OR planes.category_class = 3 OR planes.category_class = 4";
		
		if($category == "Helicopter")
			$category = "planes.category_class = 6";
	
		$sql = " SELECT sum( approaches ) AS approaches, sum(holding) AS holds, sum(tracking) AS tracking
			 FROM flights, planes
			 WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND ($category) AND date >= date_add( now( ) , INTERVAL -6 MONTH )";
		
		$result = mysql_query($sql);
		
		//print "<b>$sql</b>";
		
		$array = mysql_fetch_assoc($result);
		
		return $array;
	}
	
	function date_of_last_three_night_landings($category_class)
	{
		
		if($category_class == 19)				//SEL tailwheel
		{
			$tailwheel = "AND tailwheel = '1'";
			$category_class = 1;
		}
				
		elseif($category_class == 20)				//MEL tailwheel
		{
			$tailwheel = "AND tailwheel = '1'";
			$category_class = 2;
		}
		else	$tailwheel = "";
	
	
		$sql = "SELECT flights.date, flights.night_landings
			FROM flights, planes
			WHERE flights.plane_id = planes.plane_id AND flights.pilot_id={$this->pilot_id} 
				AND planes.category_class = $category_class AND flights.night_landings >= 1 $tailwheel
			ORDER BY date DESC
			LIMIT 3";
		
		$result = mysql_query($sql);
		
		$total_landings = 0;
		
		while($array = mysql_fetch_row($result))
		{
			$landings = $array[1];
			
			if($landings >= 3)			//return the date if the row has more than 3 landings
				return $array[0];
				
			else $total_landings += $landings;
			
			if($total_landings >= 3)		//return the date if the total from the previous rows is more than 3
				return $array[0];
		}
		
		return 0;					//return 0 if it finds nothing
		
	}
	
	function date_of_last_three_any_landings($category_class)
	{
		if($category_class == 19)				//SEL tailwheel
		{
			$tailwheel = "AND tailwheel = '1'";
			$category_class = 1;
		}
				
		elseif($category_class == 20)				//MEL tailwheel
		{
			$tailwheel = "AND tailwheel = '1'";
			$category_class = 2;
		}
		else	$tailwheel = "";

		$sql = "SELECT *
			FROM (
				SELECT flights.date, COALESCE(flights.night_landings,0) + COALESCE(flights.day_landings,0) AS any_landings
				FROM flights, planes
				WHERE flights.plane_id = planes.plane_id AND flights.pilot_id={$this->pilot_id} 
				AND planes.category_class = $category_class $tailwheel
			) AS blah

			WHERE any_landings >=1
			ORDER BY date DESC
			LIMIT 3";
		
		$result = mysql_query($sql);
		
		$total_landings = 0;
		
		while($array = mysql_fetch_row($result))
		{
			$landings = $array[1];
			
			if($landings >= 3)			//return the date if the row has more than 3 landings
				return $array[0];
				
			else $total_landings += $landings;
			
			if($total_landings >= 3)		//return the date if the total from the previous rows is more than 3
				return $array[0];
		}
		
		return 0;					//return 0 if it finds nothing
		
	}
	
	function date_of_last_six_approaches($category_class)
	{
		if($category_class == "Airplane")							//include SEL, SES, MEL, MES Simulator and FTD
			$category = "planes.category_class = 1 OR planes.category_class = 2 OR
					planes.category_class = 3 OR planes.category_class = 4 OR 		
					planes.category_class = 15 OR planes.category_class = 16";
		
		if($category_class == "Helicopter")							//include regular helicopter, as well as Simulator and FTD
			$category = "planes.category_class = 6 OR planes.category_class = 18 OR planes.category_class = 17";
	
		$sql = "SELECT flights.date, flights.approaches
			FROM flights, planes
			WHERE flights.plane_id = planes.plane_id AND flights.pilot_id={$this->pilot_id} 
				AND ($category) AND flights.approaches >= 1
			ORDER BY date DESC
			LIMIT 6";
		
		$result = mysql_query($sql);
		
		$total_landings = 0;
		
		while($array = mysql_fetch_row($result))
		{
			$landings = $array[1];
			
			if($landings >= 6)					//return the date if the row has more than 3 landings
				return $array[0];
				
			else $total_landings += $landings;
			
			if($total_landings >= 6)				//return the date if the total from the previous rows is more than 3
				return $array[0];
		}
		
		return 0;
		
	}

	function date_of_last_one($item, $category_class)			//used for tracking and holding
	{
		if($category_class == "Airplane")
			$category = "planes.category_class = 1 OR planes.category_class = 2 OR
					planes.category_class = 3 OR planes.category_class = 4 OR 
					planes.category_class = 15 OR planes.category_class = 16";
		
		if($category_class == "Helicopter")
			$category = "planes.category_class = 6 OR planes.category_class = 18 OR planes.category_class = 17";
	
		$sql = "SELECT flights.date, flights.$item
			FROM flights, planes
			WHERE flights.plane_id = planes.plane_id AND flights.pilot_id={$this->pilot_id} 
				AND ($category) AND flights.$item >= 1
			ORDER BY date DESC
			LIMIT 1";
			
		//print "<br />$sql</br>";
		
		$result = mysql_query($sql);
		
		$total_landings = 0;
		
		$date = mysql_fetch_row($result);
		
		return $date[0];
	}

	function type_rating_date_of_last_three_any_landings($type)
	{
		$sql = " SELECT *
			FROM (

				SELECT flights.date, COALESCE(flights.night_landings,0) + COALESCE(flights.day_landings,0) AS any_landings
				FROM flights, planes
				WHERE flights.plane_id = planes.plane_id AND flights.pilot_id={$this->pilot_id} AND planes.type = '$type'
				
			) AS blah

			WHERE any_landings >=1
			ORDER BY date DESC
			LIMIT 3";
			
			//print $sql;
		
		$result = mysql_query($sql);
		
		$total_landings = 0;
		
		while($array = mysql_fetch_row($result))
		{
			$landings = $array[1];
			
			if($landings >= 3)			//return the date if the row has more than 3 landings
				return $array[0];
				
			else $total_landings += $landings;
			
			if($total_landings >= 3)		//return the date if the total from the previous rows is more than 3
				return $array[0];
		}

		return 0;
	}
	
	function type_rating_date_of_last_three_night_landings($type)
	{
		$sql = "SELECT flights.date, flights.night_landings
			FROM flights, planes
			WHERE flights.plane_id = planes.plane_id AND flights.pilot_id={$this->pilot_id} AND planes.type = '$type'
			AND night_landings >=1
			ORDER BY date DESC
			LIMIT 3";
		
		$result = mysql_query($sql);
		
		$total_landings = 0;
		
		while($array = mysql_fetch_row($result))
		{
			$landings = $array[1];
			
			if($landings >= 3)			//return the date if the row has more than 3 landings
				return $array[0];
				
			else $total_landings += $landings;
			
			if($total_landings >= 3)		//return the date if the total from the previous rows is more than 3
				return $array[0];
		}
		
		return 0;
	}
	
	function reset_pass($email)
	{
		$this->email = $email;

		$chars = "234567890_abcdefghijkmnopqrstuvwxyz.ABCDEFGHJKLMNOPQRSTUVWXYZ-";
		$i = 0;
		$new_password = "";
		while ($i <= 8)
		{
			$this->password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
		
		$salted_hash = sha256($this->password.$this->salt);
		
		$sql = sprintf(
			"UPDATE pilots
			SET pending_pass_hash = '%s'
			WHERE email='%s'
			LIMIT 1",
			$salted_hash,
			mysql_real_escape_string($email)
			);
		
		mysql_query($sql);

		$this->email_pass_change();
	}
	
	function email_pass_change()
	{
		return mail("{$this->email}","Your FlightLogg.in Password","Your new password is: {$this->password}\n\n
			Before you can use this new password, you must go
			<a href=http://FlightLogg.in/verify.php?id={$this->pilot_id}&hash=" . sha256($this->pilot_id."LOL".$this->email) . ">
			here</a> to activate the change", $this->email_headers);
	}
	
	function make_tags($tags, $plane_id)
	{
		$tags_array = explode(",", $tags);
		
		foreach($tags_array as $tag)
		{
			if($tag != "")
				mysql_query("INSERT INTO tags (`plane_id`, `tag`) VALUES ('$plane_id','" . mres($tag) . "')");
		}
	
	}
	
	function make_backup($user_id = "n/a")
	{
		if($user_id == "n/a") $user_id = $this->pilot_id;
	
		$output = "Date\tPlane\tType\tRoute\tTotal\tPIC\tSolo\tSIC\tDual Received\tDual Given\tActual Instrument\tSimulated Instrument\t" .
			"Approaches\tNight\tCross Country\tDay Landings\tNight Landings\tSimulator\tStudent\tInstructor\tCaptain\tFirst Officer\tFlight Number\tRemarks\tFlying\tNon-flying";

		$sql = sprintf(
			"SELECT flights.*, planes.tail_number, planes.type
			FROM flights LEFT JOIN planes
			ON planes.plane_id = flights.plane_id
			WHERE flights.pilot_id=
			ORDER BY date, flight_id"//,
			//mysql_real_escape_string($user_id)
			);

			//print "<b>$sql</b>";	
			$result = mysql_query($sql);
#var_dump($result);
			while($row = mysql_fetch_assoc($result))
			{
			//print "loop";
				$output .= "\n";
				$remarkz = "";
				$remarkz = str_replace("\r\n", "\\r", $row['remarks']);
		
				$output .= "{$row['date']}\t{$row['tail_number']}\t{$row['type']}\t{$row['route']}\t{$row['total']}\t{$row['pic']}\t{$row['solo']}\t{$row['sic']}\t" .
					"{$row['dual_recieved']}\t{$row['dual_given']}\t{$row['act_instrument']}\t{$row['sim_instrument']}\t{$row['approaches']}\t" .
					"{$row['night']}\t{$row['xc']}\t{$row['day_landings']}\t{$row['night_landings']}\t{$row['simulator']}\t{$row['student']}\t{$row['instructor']}\t" .
					"{$row['captain']}\t{$row['fo']}\t{$row['flight_number']}\t" . str_replace("\t",' ', $remarkz) . "\t";
			
				if($row['holding'])			//flying events
					$output .= "H";
				if($row['tracking'])
					$output .= "T";
				if($row['cfi_checkride'])
					$output .= "C";
				if($row['pilot_checkride'])
					$output .= "P";
				if($row['bfr'])
					$output .= "B";
				if($row['ipc'])
					$output .= "I";
				
				$output .= "\t";
			
				if($row['cfi_refresher'])			//non-flying events
					$output .= "R";
				elseif($row['signoff'])
					$output .= "S";
				elseif($row['medical_class'])
					$output .= $row['medical_class'];

			}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$output .= "\n##RECORDS";
	
		$sql = sprintf(
			"SELECT records
			FROM pilots
			WHERE pilot_id='%s'",
			mysql_real_escape_string($user_id)
			);
			
		$result = mysql_query($sql);
	
		$records = mysql_fetch_array($result);
	
		$records = str_replace("\r", "\\r", $records[0]);		//remove carriage returns and new lines
		$records = str_replace("\n", "", $records);
	
		$output .= "\t{$records}";
	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$sql = sprintf(
			"SELECT *
			FROM planes LEFT JOIN tags
			ON tags.plane_id = planes.plane_id
			WHERE planes.pilot_id='%s'",
			mysql_real_escape_string($user_id)
			);
			
		$result = mysql_query($sql);
	
		while($row = mysql_fetch_assoc($result))
		{		
			$type_tail .= !empty($row['tailwheel']) ? "T" : "";
			$type_tail .= !empty($row['type_rating']) ? "R" : "";
	
			if($tail_number == $row['tail_number'])		//if the last tailnumber equals the current tailnumber...
				$output .= ", {$row['tag']}";
			else	$output .= "\n##PLANE\t{$row['tail_number']}\t{$row['manufacturer']}\t{$row['model']}\t{$row['type']}\t{$row['category_class']}\t{$type_tail}\t{$row['tag']}";
		
			$tail_number = $row['tail_number'];
			$type_tail = "";
	
		}
		
		print $output;
	}
}
?>
