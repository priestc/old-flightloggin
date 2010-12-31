<?php
include "classes/map_class.php";

$user = new map(true);

header("Content-type: text/plain; charset=UTF-8");

/////////////////////////////////////////////////


$map_type = $_GET['type'];


/////////////////////////////////////////////////

if($map_type == "markers")
{
	if($_GET['all'] == "true")
		$user->get_routes("site_wide");
	else	$user->get_routes("all");
	
	$user->get_airport_coordinates("markers");
	
	//var_dump($user->airport_ids);
	
	$display .= "var markers = [\n";
	
	$i = 0;
	
	if(!empty($user->airport_ids))					//skip everything if the user hasn't entered any flights yet
		foreach($user->airport_ids as $airport)
		{
			if($user->airport_coordinates["$airport"]['lat'] != 0 && 
			   $user->airport_coordinates["$airport"]['long'] != 0 &&
			   $user->airport_coordinates["$airport"]['location_type'] == "A")
			{
				if($i>=1)
					$display .= ",\n\n";
					
				$airport_name = $user->airport_coordinates["$airport"]['airport_name'];
				$city = $user->airport_coordinates["$airport"]['city'];
				$sector = $user->airport_coordinates["$airport"]['sector'];					
	
				if(strlen($airport) == 3 && !preg_match("/[0-9]/", $airport) && $sector != "Hawaii" && $sector != "Alaska")		//add the "K" if it needs one.
					$identifier = "K" . $airport;
				else	$identifier = $airport;
		
				$lat = $user->airport_coordinates["$airport"]['lat'];
				$long = $user->airport_coordinates["$airport"]['long'];
							
				$display .= "{\n\t'latitude': $lat,\n\t'longitude': $long,\n\t'city': '$city',\n\t'airport': '$airport_name',\n\t'sector': '$sector',\n\t'identifier': '$identifier'\n}";
			
				$i++;
			}
		}
	
	print utf8_encode($display);
	print "\n];";
}
elseif($map_type == "cat_line" || empty($map_type))
{
	$cat_classes = array("single", "multi", "other", "heli", "glider", "turbine");

	foreach($cat_classes as $cat_class)
		$user->get_routes($cat_class);		//get the routes for each cat_class

	$user->get_airport_coordinates();
	
	//var_dump($user->single_legs);
	//var_dump($user->airport_ids);
	//var_dump($user->airport_coordinates);
	
	#################################################################
	
	$display .= "var lines = [\n";
	
	$i = 0;
	$color = array("single" => "#0000FF", "multi" => "#dc9100", "turbine" => "#c61e1e", "heli" => "#2fa019", "other" => "#9b19a0");
	//heli=green, turb=red, single=blue, multi=orange, other=purple
	
	foreach($cat_classes as $cat_class)
	{
		if(empty($user->single_legs[$cat_class]))				//to avoid a php error if the subarray does not exist
			$corrected_array = array();
		else	$corrected_array = $user->single_legs[$cat_class];
	
		foreach($corrected_array as $leg)
		{
			$leg_array = explode("-", $leg);
		
			$start_point = $leg_array[0];
			$end_point = $leg_array[1];
		
			$start_lat = $user->airport_coordinates[$start_point]['lat'];
			$start_long = $user->airport_coordinates[$start_point]['long'];
			
			$end_lat = $user->airport_coordinates[$end_point]['lat'];
			$end_long = $user->airport_coordinates[$end_point]['long'];
			
			if($start_lat != "0" && $start_long != "0" && $end_lat != "0" && $end_long != "0")
			{
				if($i>=1)
					$display .= ",\n\n";		//the comma between each curly bracket, do not make one if its the first one
					
				$display .= "{\n\t'start_lat': '$start_lat',\n\t'start_long': '$start_long',\n\t'end_lat': '$end_lat',\n\t'end_long': '$end_long',\n\t'color': '{$color[$cat_class]}'\n}";
				$i++;
			}
			
		}
	}
	
	print $display;
	print "\n];";
}

//var_dump($user->airport_coordinates);
//var_dump($user->airport_ids);


if(empty($user->failed_airports) || $_GET['all'] == "true")
	print "\n\nvar missing_planes = new Array('no_failed_airports');";
else
	print "\n\nvar missing_planes = new Array('" . implode("', '", $user->failed_airports) . "');";
		
