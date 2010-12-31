<?php
include "main_classes.php";

class map extends auth
{
	var $routes_array = array();
	var $failed_airports = array();
	var $airport_coordinates = array();
	
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
				{$sql = "SELECT DISTINCT route FROM flights WHERE route IS NOT NULL";break;}
		}
		
		####################################################################
		
		if(!empty($sql_directive))
			$sql = "SELECT DISTINCT route
				FROM flights, planes LEFT JOIN tags ON planes.plane_id = tags.plane_id
				WHERE flights.plane_id = planes.plane_id
				AND $sql_directive
				AND flights.pilot_id = {$this->pilot_id}
				AND flights.route IS NOT NULL";
		
		$result = mysql_query($sql);
		
		$airport_ids = array();
		
		$route_string = mysql_fetch_array($result);
		if(empty($route_string))
			return;
			
		####################################################################
		
		do				//go through each raw route
		{
			//print "{$route_string[0]}\n";
		
			$fixed_route = strtoupper(str_replace(", ","-",$route_string[0]));		//clean up all the crap users use to log their flights
			$fixed_route = str_replace(" TO ","-",$fixed_route);
			$fixed_route = str_replace(" - ","-",$fixed_route);
			$fixed_route = str_replace(",","-",$fixed_route);
			$fixed_route = str_replace(" ","-",$fixed_route);
			$fixed_route = str_replace("- LOCAL","",$fixed_route);
			$fixed_route = str_replace("-LOCAL","",$fixed_route);
			$fixed_route = str_replace("LOCAL","",$fixed_route);
			$fixed_route = str_replace("--","-",$fixed_route);
			
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
		
		$this->single_legs[$type] = $fixed_non_duplicate;	//take all the single legs from the above loop and put them in a member variable indexed to the category_class
		
		####################################################################
		
		if(empty($this->airport_ids))								//no need to create this huge variable if it's not needed
			$this->airport_ids = $acc_airport_ids;						//removes duplicate airport identifiers
		else	$this->airport_ids = array_merge($this->airport_ids, $acc_airport_ids);
		
		$this->airport_ids = array_values(array_unique($this->airport_ids));	
	}
