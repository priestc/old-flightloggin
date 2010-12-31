<?php
include "main_classes.php";

class map extends auth
{
	var $routes_array = array();
	var $failed_airports = array();
	
	function get_routes($type)		//this function gets the routes for the type passed, and also creates a master list of all airport ids in the logbook
	{
		switch($type)
		{
			case "single":
				{$sql_directive = "(planes.category_class = 1 OR planes.category_class = 3)
							AND (planes.plane_id not in (SELECT plane_id FROM tags WHERE UPPER(tag) = 'TURBINE'))";break;}	//non-turbine SEL or SES
			case "glider":
				{$sql_directive = "planes.category_class = 5";break;}									//glider only
				
			case "multi":
				{$sql_directive = "(planes.category_class = 2 OR planes.category_class = 4)
							AND (planes.plane_id not in (SELECT plane_id FROM tags WHERE UPPER(tag) = 'TURBINE'))";break;}	//non-turbine MEL or MES
			case "turbine":
				{$sql_directive = "(planes.category_class != 6 AND planes.category_class != 7)
							AND (planes.plane_id in (SELECT plane_id FROM tags WHERE UPPER(tag) = 'TURBINE'))";break;}	//all turbine, except for helicopters
			case "heli":
				{$sql_directive = "(planes.category_class = 6 OR planes.category_class = 7)";break;}					//all helicopters, turbine or not
				
			case "other":
				{$sql_directive = "(planes.category_class >= 7 AND planes.category_class < 16)";break;}					//anything not all the above, or FTD's or Sim's
				
			case "all":
				{$sql_directive = "1";break;}

			case "site_wide":
				{$sql = "SELECT DISTINCT fix_route(`route`) FROM flights WHERE route IS NOT NULL";break;}
		}
		
		####################################################################
		
		if(!empty($sql_directive))
			$sql = "SELECT DISTINCT fix_route(route)
				FROM flights, planes LEFT JOIN tags ON planes.plane_id = tags.plane_id
				WHERE flights.plane_id = planes.plane_id
				AND $sql_directive
				AND flights.pilot_id = {$this->pilot_id}
				AND flights.route IS NOT NULL";
		
		$result = mysql_query($sql);
		
		//print $sql;
		
		$airport_ids = array();
		
		$route_string = mysql_fetch_array($result);
		if(empty($route_string))
			return;
			
		####################################################################
		
		do				//go through each raw route
		{
			//print "{$route_string[0]}\n";
		
			$fixed_route = $route_string[0];
			
			//print "$fixed_route\n\n";
			
			$route_ids = explode("-", $fixed_route);
			
			foreach($route_ids as $key => $value)						//go thorugh each identifier in the route
			{
				$route_ids[$key] = trim($value);					//remove whitespace
				
				$acc_airport_ids[] = $route_ids[$key];					//add the trimed value to the accumulated list
				
				if(empty($value))
					unset($route_ids[$key]);					//remove empty entries
					
				if(strlen($airport) == 4 && substr($airport,0,1) == "K")		//if its a 4 letter identifier starting with a 'K'
					$route_ids[$key] = substr($airport,1,3);			//remove the 'k'
			}
			
			####################################################################
			
			if(!empty($route_ids[1]) && $route_ids[1] != $route_ids[0])	//if there is more than one item in the route...
			{
				$i = 1;
				
				do							//go through each identifier, starting with the second
				{
					if(!empty($route_ids[$i]))					//if it's not empty...
					{
						$all_single_legs[] = $route_ids[$i-1] ."-". $route_ids[$i];	//print to the screen
					}
				$i++;
				}while(!empty($route_ids[$i]));
			}
			
		}while($route_string = mysql_fetch_array($result));
		
		############################################################################################################################################
		
		$this->single_legs[$type] = $this->remove_all_duplicates($all_single_legs);
		
		####################################################################
		
		if(empty($this->airport_ids))								//no need to create this huge variable if it's not needed
			$this->airport_ids = $acc_airport_ids;						//removes duplicate airport identifiers
		else	$this->airport_ids = array_merge($this->airport_ids, $acc_airport_ids);
		
		$this->airport_ids = array_values(array_unique($this->airport_ids));
		
		//var_dump($this->airport_ids);	
	}
	
	///////////////////////////////////////////////////////////////////////////
	
	function remove_all_duplicates($all_single_legs)
	{
		if(empty($all_single_legs))
			return;
			
		$non_duplicate = array();						//initialization, to surpress php error
		
		foreach($all_single_legs as $value)					//remove reverse duplicates and regular duplicates
		{
			$reversed = implode("-", array_reverse(explode("-", $value)));
			
			if (!array_key_exists($value, $non_duplicate) && !array_key_exists($reversed, $non_duplicate))
			{
				$non_duplicate[$value] = 's';
				$fixed_non_duplicate[] = $value;
			}
		}
		
		return $fixed_non_duplicate;
	}

	function get_airport_coordinates()
	{
		$this->airport_coordinates = array();							//initialize array
		
		for($i=0;$i<sizeof($this->airport_ids);$i++)						//$airport_ids was created by get_routes(), this loop goes through each airport
		{
			$lat_long_loc = $this->check_database($this->airport_ids[$i]);			//look in database, returns a six item array, or empty is failure
			
			//var_dump($lat_long_loc);
			
			if(empty($lat_long_loc))							##not in database, start crawling
			{
				$lat_long_loc = $this->crawl($this->airport_ids[$i]);			//if the airport is not in the database, then crawl the innanet to get the coordinates
					
				if($lat_long_loc["lat"] == 0 && $lat_long_loc["long"] == 0)				//if its still empty after the crawel, then just give up
				{
					$this->failed_airports[] = $this->airport_ids[$i];				//add this airport to the list of failures to be printed later
					$this->airport_coordinates[$this->airport_ids[$i]] = array(0,0,0,0,0,0);
				}
				else
					$this->airport_coordinates[$this->airport_ids[$i]] = $lat_long_loc;
			}
			elseif($lat_long_loc["lat"] == 0 && $lat_long_loc["long"] == 0)					##in the database, but still not found
			{
				$this->failed_airports[] = $this->airport_ids[$i];
				$this->airport_coordinates[$this->airport_ids[$i]] = $lat_long_loc;
			}				
			elseif($lat_long_loc["lat"] != 0)							//in the database and not ignored
				$this->airport_coordinates[$this->airport_ids[$i]] = $lat_long_loc;
				
		}
	
		$this->failed_airports = array_unique($this->failed_airports);				//remove duplicates
		//var_dump($this->failed_airports);
	
	}
	
	//ABQIAAAAtDznsRv92g_KZ0HK9mBD5RQP8UQ8N6UpfdQUQk4fJpBajrrOdBRDD64KmnL42FModJ5TwsXszwoT5A
	
	function check_database($airport, $markers = "")			//returns either "ignore", empty, or a 5 item array (lat/long/name/city/sector)
	{
		$airport = strtoupper($airport);
			
		$sql = "SELECT * FROM airports WHERE identifier ='" . mres($airport) . "' LIMIT 1";
		$result = mysql_query($sql);
		$result_line = mysql_fetch_assoc($result);
		
		if(empty($result_line))
		{
			$sql = "SELECT * FROM airports WHERE identifier ='K" . mres($airport) . "' LIMIT 1";
			$result = mysql_query($sql);
			$result_line = mysql_fetch_assoc($result);
		}
		
		#######################################################
		
		if(!empty($result_line))
			return array(   "lat" => $result_line['lat'],
					"long" => $result_line['long'],
					"airport_name" => $result_line['airport_name'],
					"city" => $result_line['city'],
					"sector" => $result_line['sector'],
					"location_type" => $result_line['location_type']);
		else
			return;		//not in database, return nothing, need to crawl
	}
	
	function crawl($airport)	//returns 3 item array, lat/long/name
	{
		$airport = $orig_airport = strtoupper($airport);					//make uppercase
		
		if(strlen($airport) == 3 ||
		   preg_match("/[0-9]/", $airport) ||
		   (strlen($airport) == 4 && substr($airport,0,1) == "P") ||
		   (strlen($airport) == 4 && substr($airport,0,1) == "K"))
		   
		{					//if it's three letters, or is 4 letters and starts with "P" or "K", or has numbers, assume its an american airport, and start with airnav
		
			if(substr($airport,0,1) == "K" && strlen($airport) == 4)			//if the first letter is "K" and its 4 letters long, strip off the k
				$airport = substr($airport,1,3);
		
			$lat_long_loc = $this->crawl_airnav($airport, "regular");			//crawl airnav with the normal method
					
			if($lat_long_loc[0] != 0 && $lat_long_loc[1] != 0)				//if the coordinates are not empty, then...
			{
				$entry_method = 'A';							//success with the A method
				$success = true;
			}
			
			elseif(strlen($orig_airport) == 3 && !$success)					//if crawling airnav didnt work, and the identifier is 3 letters, do a navaid search
			{
				//print "o:$orig_airport - n:$airport\n";
				$lat_long_loc = $this->crawl_navaid($airport);
				
				$success = $lat_long_loc[0] != "0";					//this is what great circle returns as a failure

				if($success)
					$entry_method = "N";
			}
			
			elseif(strlen($orig_airport) == 4 && !$success)
			{
				$lat_long_loc = $this->crawl_great_circle($airport);			//if there are 4 letters, and it failed the first try, try the great circle method
				
				$success = ($lat_long_loc[0] != "0" && $lat_long_loc[1] != "0");

				if($success)
					$entry_method = "C";
			}
				
			elseif(!$success)
			{
				$lat_long_loc = $this->crawl_airnav($airport, "formerly");		//try again, this time using the "formerly" method
				
				if(!empty($lat_long_loc))						//if the coordinates are not ""
					$entry_method = 'F';						//success with the "F" method
			
				else
					$lat_long_loc = array(0,0,0,0,0,0);				//failed both times
			}
		}
		else											//if it's a four letter identifier, its international, so use google
		{
			$entry_method = 'C';								//entry method will be "G" no matter what, because google always returns a hit
			
			$lat_long_loc = $this->crawl_great_circle($airport);				//crawl google, this will always return a hit, latlong_loc will never be empty
			
			
		}
		
		#################################
		
		//var_dump($latlong_loc);
				
		$lat = $lat_long_loc[0];
		$long = $lat_long_loc[1];
		
		$airport_name = str_replace("'", "&#39;", $lat_long_loc[2]);	//replace apostrophes with html code in the location part of the array
		$airport_name = str_replace("\"", "&quot;", $airport_name);	//replace quotation marks too
		
		$city_name = str_replace("'", "&#39;", $lat_long_loc[3]);	//replace apostrophes with html code in the location part of the array
		$city_name = str_replace("\"", "&quot;", $city_name);		//replace quotation marks too
		
		$sector = str_replace("'", "&#39;", $lat_long_loc[4]);		//replace apostrophes with html code in the location part of the array
		$sector = str_replace("\"", "&quot;", $sector);			//replace quotation marks too
		
		//var_dump($lat_long_loc);
		
		##################################
		
		if($entry_method == "N")
			$location_type = "N";
		else	$location_type = "A";
		
		$sql = "INSERT INTO airports (`lat`, `long`, `airport_name`, `city`, `sector`, `identifier`, `entry_method`, `location_type`)
			VALUES('$lat', '$long', '" . mres($airport_name) . "', '" . mres($city_name) . "', '" . mres($sector) . "', '". mres($airport) . "', '$entry_method', '$location_type')";
		
		mysql_query($sql);
		
		//print "\n\n<br>$sql<br>";
		
		##################################
		
		return array("lat" => $lat,
			     "long" => $long,
			     "airport_name" => $airport_name,
			     "city" => $city_name,
			     "sector" => $sector,
			     "location_type" => $location_type);
	}
	
	function crawl_google($airport)
	{
		//$page = file_get_contents(
		//		"http://maps.google.com/maps/geo?q={$airport}&output=xml&key=ABQIAAAAtDznsRv92g_KZ0HK9mBD5RS3ydai3R5pSwWU_IHTQxn79Z9awhQaWXs60I2yGfPRz5z-Bs_CTn5lMg");

		$xml = simplexml_load_string(utf8_encode($page));
		
		$location = $xml->Response->Placemark->address . "";		
		$coord_string = $xml->Response->Placemark->Point->coordinates . "";		//cast to a string
		
		if($coord_string == "")
			$coord_string = "0,0";
			
		if($location == "")
			$location = "0";
		
		return array($coord_string, $location);
	}
	
	function crawl_navaid($navaid)
	{
		//print "\n\n\n$navaid\n";
	
		ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 Firefox/3.0');
		$page = file_get_contents("http://worldaerodata.com/wad.cgi?nav=$navaid",FALSE, NULL, 150, 3800);
	
		#######################
		
		$snipplet_after = strstr($page, "<th bgcolor=\"99CCFF\" colspan=7>");		//all the stuff after this string
		$end_pos = strpos($snipplet_after, "</b>");					//position of the end of what we want
		$navaid_name_snipplet = substr($snipplet_after, 0, $end_pos);			//snipplet of raw HTML of the stuff we want, 

		$navaid_name = ucwords(strtolower(strip_tags($navaid_name_snipplet)));		//now strip the tags, and do proper capitalization
		
		######################
		
		$snipplet_after = strstr($page, "<tr>\n <td width=\"40%\" bgcolor=\"F0F0F0\">");	//all the stuff after this string
		$end_pos = strpos($snipplet_after, "<br>");						//position of the end of what we want
		$lat_snipplet = substr($snipplet_after, 0, $end_pos);					//snipplet of raw HTML of the stuff we want, 

		$lat = trim(strip_tags(str_replace("\n","",$lat_snipplet)));						//now strip the tags
		
		######################
		
		$snipplet_after = strstr($page, "</td>\n <td width=\"40%\" bgcolor=\"F0F0F0\">");	//all the stuff after this string
		$end_pos = strpos($snipplet_after, "<br>");						//position of the end of what we want
		$long_snipplet = substr($snipplet_after, 0, $end_pos);					//snipplet of raw HTML of the stuff we want, 

		$long = trim(strip_tags(str_replace("\n","",$long_snipplet)));				//now strip the tags
		
		######################
		
		//print "$page\n\n";
		
		return array($lat, $long, $navaid_name,0,0);
		
	}
	
	function crawl_great_circle($airport)
	{
		ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 Firefox/3.0');
		
		$page = file_get_contents("http://gc.kls2.com/airport/". trim($airport),FALSE, NULL, 10, 3800);
		
		##############################

		$snipplet_after = strstr($page, "Name:");
		
		$airport_name_snipplet = strip_tags($snipplet_after);
		$end_pos = strpos($airport_name_snipplet, "\n");
		
		$airport_name = substr($airport_name_snipplet, 0, $end_pos);
		$airport_name = substr($airport_name, 5, strlen($airport_name)-5);
		
		//print "icao = @@$airport@@\n\n<br>";
		
		//print "airport name = @@$airport_name@@\n\n<br>";
		
		#############################
		
		$city_name_snipplet = strstr($page, "City:");
			
		$city_name_snipplet = strip_tags($city_name_snipplet);
		$end_pos = strpos($city_name_snipplet, "\n");
		
		$city_name = substr($city_name_snipplet, 0, $end_pos);
		$city_name = substr($city_name, 17, strlen($city_name)-17);
		
		//print "city name = @@$city_name@@\n\n<br><br>";
		
		#############################
		
		$lat_snipplet = strstr($page, "<abbr class=\"latitude\" title=");
		$lat_snipplet = strip_tags($lat_snipplet);
		$end_pos = strpos($lat_snipplet, "\n");
		
		$lat_snipplet = substr($lat_snipplet, 0, $end_pos);
		
		$lat = strstr($lat_snipplet, "(");
		
		$lat = substr($lat, 1, strlen($lat)-2);
		
		//print "lat = @@$lat@@\n\n";
		
		#############################
		
		$long_snipplet = strstr($page, "<abbr class=\"longitude\" title=");
		$long_snipplet = strip_tags($long_snipplet);
		$end_pos = strpos($long_snipplet, "\n");
		
		$long_snipplet = substr($long_snipplet, 0, $end_pos);
		
		$long = strstr($long_snipplet, "(");
		
		$long = substr($long, 1, strlen($long)-2);
		
		//print "long = @@$long@@\n\n";
		
		#############################
			
		$city_sector = explode(", ", $city_name);
		
		$city_name = $city_sector[0];
		
		$sector_name = !empty($city_sector[2]) ? $city_sector[2] : $city_sector[1];
		
		//print "{$city_sector[0]} - {$city_sector[1]} - {$city_sector[2]}\n<br>";
				
		
		return array($lat, $long, $airport_name, $city_name, $sector_name);
		
	
	}
	
	function crawl_airnav($airport, $type)		//returns an array, first element is a string with the coordinates, and the second element is a string for the location name
	{
		//print "$airport<br>\n";
		
		switch($type)
		{
			case "formerly":
				{$page = file_get_contents("http://www.google.com/search?q=\"(formerly+" . trim($airport) . ")\"+site%3Aairnav.com&btnI=745",FALSE, NULL, 500, 4800);break;}
			case "regular":
				{$page = file_get_contents("http://airnav.com/airport/" . trim($airport),FALSE, NULL, 500, 4800);break;}
			case "navaid":
				//{$page = file_get_contents("http://airnav.com/airport/" . trim($airport),FALSE, NULL, 500, 4800);break;}
		}
					
		$coord_sniplet = strstr($page, "<TR><TD valign=top align=right>Lat/Long:&nbsp;</TD><TD valign=top>");
		$coordinates = substr(strip_tags($coord_sniplet),77,25);
		
		$coordinates = str_replace("(", "", $coordinates);
		
		$coordinates = explode(" / ", $coordinates);
		
		$coordinates = $coordinates[1] . "," . $coordinates[0];
		
		//--------------------------------------
		
		$location_sniplet = strstr($page, "<td width=\"99%\" bgcolor=\"#FFFFFF\"> <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"+1\"><b>");
		$location = substr($location_sniplet, 0, strpos($location_sniplet, "</font> </td>"));
		
		$location = explode("</b><br>",$location);
		
		$airport_name = $location[0];
		$sector = $location[1];
					
		$airport_name = trim(strip_tags($airport_name));
		$sector = trim(strip_tags($sector));
		
		if($coordinates == ",")
			return null;
			
		#####################################################################
		
		$sector = explode(", ", $sector);
			
		$lat_long = explode(",",$coordinates);
		
		return array($lat_long[1], $lat_long[0], $airport_name, $sector[0], $sector[1]);		//lat, long, airport name, city, sector
	}	
}
