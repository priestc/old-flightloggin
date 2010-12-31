<?php
include "classes/map_class.php";

$map_type = $_GET['type'];
$user = new map(true);

header("Content-type: text/plain");

/////////////////////////////////////////////////

if($map_type == "markers")
{
	$user->get_routes("all");
	$user->get_airport_coordinates();
	
	$display .= "var markers = [\n";

	foreach($user->airport_ids as $airport)
	{
		if(!empty($user->airport_coordinates["$airport"][1]) || !empty($user->airport_coordinates["$airport"][0]) || !empty($user->airport_coordinates["$airport"][2]))
		{
			
	
			if(strlen($airport) == 3 && !preg_match("/[0-9]/", $airport))		//add the "K" if it needs one.
				$identifier = "K" . $airport;
			else	$identifier = $airport;
		
			$airport_name = $user->airport_coordinates["$airport"][2];
			$city = $user->airport_coordinates["$airport"][3];
			$sector = $user->airport_coordinates["$airport"][4];
		
			$lat = $user->airport_coordinates["$airport"][1];
			$long = $user->airport_coordinates["$airport"][0];
							
			$display .= "{\n\t'lat': $lat,\n\t'long': $long,\n\t'city': $city,\n\t'sector': $sector,\n\t'identifier': $identifier\n},\n\n";
		}
	}
}



	
print $display;
	
print "];";
		
