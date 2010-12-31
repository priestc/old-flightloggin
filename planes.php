<?php

include "classes/planes_class.php";

$user = new planes(true);

if(!empty($_POST))
{
	//var_dump($_POST);

	if(	!empty($_POST['save_all']))				//the 'save all' button was pressed
		$user->save_all_planes($_POST);
		
	elseif(!empty($_POST['new_plane']))				//the new plane button was pressed
		$user->create_new_plane($_POST);
		
	elseif(!empty($_POST['delete']))				//a delete button was pressed
		$user->delete_plane($_POST['plane_id']);
		
	elseif(!empty($_POST['save']))
		$user->save_single_plane($_POST, $_POST['plane_id']);	//a individual save button was pressed	
	
	header('Location: planes.php');
	exit;
}


$webpage = new page("Planes", 1, $user->auth);

if($user->auth != "share")
{
	$new_button = "<input type=\"button\" value=\"Create New Plane\" onclick=\"document.getElementById('new_plane').style.display = 'block'; this.style.display = 'none'\" />";
}

##########################################################################

	$content .= <<<EOF

		<div class="central_div" style="overflow:hidden">
			<br>
			{$webpage->ads}<br>
		
			$new_button
			
			<script type="text/javascript" src="javascripts/validate_new_plane.js"></script>
	
				<form class="planes_form" id='planes_form' action="planes.php" method="post">
				
					
					
				<div id="new_plane" style="display:none">
					<input type="hidden" name='plane_id' value='ss' />
					<div style="text-align: canter">
						Manufacturer = "Cessna", "Boeing", "Beechcraft", etc<br /> Type = "PA-28-161", "BE-55TC", "C-172R", etc<br />Model = "Skyhawk",
						"Saratoga", "Metroliner III", etc.<br />Tags = "Complex, G1000, High Performance".<br />Each tag must be comma seperated and may contain spaces.
					</div>
					<div class="plane_box">
						<div style="float:left;border:0px solid black;width:100%">
							<div style="width:85%;border:0px solid black;float:left;text-align:left">
								
								<input id="tail_number_new" onclick="this.value = '';" class="planes_tail_number" title="Tail Number" type="text" name="tail_number_new" value="Tailnumber" />							
								<input id="manufacturer_new" onclick="this.value = '';" type="text" title="Manufacturer" name="manufacturer_new" value="Manufacturer" /> - 
								<input id="type_new" onclick="this.value = '';" type="text" title="Type" name="type_new" value="Type" /> - 
								<input id="model_new" onclick="this.value = '';" type="text" title="Model" name="model_new" value="Model" />
							
									<select title="Category/Class" name="category_class_new">
										<option value="1">Airplane / SEL</option>
										<option value="2">Airplane / MEL</option>
										<option value="3">Airplane / SES</option>
										<option value="4">Airplane / MES</option>
										<option value="5">Glider</option>
										<option value="6">Rotorcraft / Helicopter</option>
										<option value="7">Rotorcraft / Gyroplane</option>
										<option value="8">Weight-Shift-Control / Land</option>
										<option value="9">Weight-Shift-Control / Sea</option>
										<option value="10">Powered Parachute / Land</option>
										<option value="11">Powered Parachute / Sea</option>
										<option value="12">Lighter-Than-Air / Airship</option>
										<option value="13">Lighter-Than-Air / Balloon</option>
										<option value="14">Powered Lift</option>
										<option value="15">Airplane / Simulator</option>
										<option value="16">Airplane / FTD</option>
										<option value="17">Helicopter / Simulator</option>	
										<option value="18">Helicopter / FTD</option>
									</select>
							</div>
							
							<div style="width:10%;border:0px solid black;float:right;text-align:right">
								Type Rating: <input type="checkbox" name="type_rating_new" value="1" /><br />					
								Tailwheel: <input type="checkbox" name="tailwheel_new" value="1" />
							</div>
						</div>
																		
						<div style="border:0px solid black;width:100%;float:left;">	
							<div style="width:65%;border:0px solid black;float:left;text-align:left">
								Tags: <input id="tags_new" class="planes_tags" type="text" name="tags_new" value="" />
							</div>
							
							<div style="width:30%;border:0px solid black;float:right;text-align:right">
								<input type="submit" value="Create New Plane" name="new_plane" style="width:10em;margin:5px"
									onclick="return validate_new_plane_form('new');" />
							</div>
						</div>
					</div>
				</div>
				
				<hr />
EOF;
	

	$sql = sprintf(
			"SELECT planes.*, SUM(flights.total) AS total_total, MAX(flights.date) AS last_flown, SUM(flights.simulator) AS total_simulator
			FROM planes LEFT JOIN flights
			ON flights.plane_id = planes.plane_id
			WHERE planes.pilot_id='%s'
			GROUP BY planes.plane_id
			ORDER BY CASE WHEN manufacturer IS NULL THEN 1 ELSE 0 END, manufacturer, model, tail_number",
			mysql_real_escape_string($user->pilot_id)
			);

	//print $sql;

	$result = mysql_query($sql);
	
	while($row = mysql_fetch_assoc($result))
	{
		$planes[] = $row;
	}
	
	if(empty($planes))
	{
		$content .= "<div class=\"empty_message\">There are currently no planes in your database</div>";
	}
	else
	{

		//each loop males one plane box
		for($i=0;$i<sizeof($planes);$i++)
		{
			##############################################

			$plane_id = $planes[$i]['plane_id'];
			$tail_number = $planes[$i]['tail_number'];
			$type = $planes[$i]['type'];
			$type_rating = $planes[$i]['type_rating'] == "0" ? "&nbsp" : " / Type Rating";
			$tailwheel = $planes[$i]['tailwheel'] == "0" ? "&nbsp" : " / Tailwheel";
			$manufacturer = $planes[$i]['manufacturer'];
			$model = $planes[$i]['model'];
			$total_in_plane = $planes[$i]['total_total'];
			$sim_in_plane = $planes[$i]['total_simulator'];
			
			$type_sel = $planes[$i]['type_rating'] ? "checked = \"checked\"" : "";
			$tail_sel = $planes[$i]['tailwheel'] ? "checked = \"checked\"" : "";
			
			$all_ids .= $plane_id . " ";
			
			##############################################
			
			$sql = "SELECT tags.tag
				FROM tags, planes
				WHERE tags.plane_id = planes.plane_id AND planes.plane_id = $plane_id AND planes.pilot_id = {$user->pilot_id}";
				
			$result = mysql_query($sql);
			
			unset($tags_array);
			unset($tags);
			
			while($row = mysql_fetch_array($result))
				$tags_array[] = $row[0];

			if(!empty($tags_array))
			{
				sort($tags_array);			//sort the tags so they are alphabetical
				$tags = implode(", ", $tags_array);
			}
			
			##############################################
			
			$cat_1_sel = $cat_8_sel = $cat_9_sel = $cat_13_sel =
			$cat_2_sel = $cat_7_sel = $cat_10_sel = $cat_14_sel =
			$cat_3_sel = $cat_6_sel = $cat_11_sel = $cat_15_sel =
			$cat_4_sel = $cat_5_sel = $cat_12_sel = $cat_16_sel = "";

			switch($planes[$i]['category_class'])
			{
				case 1:
					$cat_1_sel = "selected  = \"selected\"";break;
				case 2:
					$cat_2_sel = "selected = \"selected\"";break;
				case 3:
					$cat_3_sel = "selected = \"selected\"";break;
				case 4:
					$cat_4_sel = "selected = \"selected\"";break;
				case 5:
					$cat_5_sel = "selected = \"selected\"";break;
				case 6:
					$cat_6_sel = "selected = \"selected\"";break;
				case 7:
					$cat_7_sel = "selected = \"selected\"";break;
				case 8:
					$cat_8_sel = "selected = \"selected\"";break;
				case 9:
					$cat_9_sel = "selected = \"selected\"";break;
				case 10:
					$cat_10_sel = "selected = \"selected\"";break;
				case 11:
					$cat_11_sel = "selected = \"selected\"";break;
				case 12:
					$cat_12_sel = "selected = \"selected\"";break;
				case 13:
					$cat_13_sel = "selected = \"selected\"";break;
				case 14:
					$cat_14_sel = "selected = \"selected\"";break;
				case 15:
					$cat_15_sel = "selected = \"selected\"";break;
				case 16:
					$cat_16_sel = "selected = \"selected\"";break;
			}

		
		
			#############################################
		
			if($planes[$i]['last_flown'] == "")
				$last_flown = "Never";
			else
				$last_flown = date('l, F jS, Y', strtotime($planes[$i]['last_flown']));
				
				
			if($planes[$i - 1]['manufacturer'] != $planes[$i]['manufacturer'] || $i == 0)			//if the last one was different, or its the first plane
			{
				if($planes[$i]['manufacturer'] == "")
					$content .= "<div class=\"planes_manufacturer\">Unknown:</div>";
				else
					$content .= "<div class=\"planes_manufacturer\">" . $planes[$i]['manufacturer'] . ":</div>";
					
				$align_count = 0;

			}
		

			
			
			if($total_in_plane == 0 && $sim_in_plane == 0)			//no time at all
			{
				$display_total = "None";
				$delete_button = "<input type=\"submit\" name=\"delete\" value=\"Delete Plane\"
							onclick=\"document.getElementById('planes_form').plane_id.value = '$plane_id';
							return alert('Are you sure? This is unreversable.');\" style=\"width:7em;margin:5px\" />";
			}
			elseif($total_in_plane == 0 && $sim_in_plane != 0)		//must be a sim
			{
				$display_total = "(" . $sim_in_plane . ")";
				$delete_button = "";
			}
					
			else								//there is flight time
			{
				$display_total = $total_in_plane;
				$delete_button = "";
			}
			
			$save_all_button = "<input type=\"submit\" name=\"save_all\" value=\"Save All\" onmouseover=\"return validate_all_planes();\" />";
			
			$save_button = "<input type=\"submit\" value=\"Save\" name=\"save\" onclick=\"document.getElementById('planes_form').plane_id.value = '$plane_id';
					return validate_new_plane_form('$plane_id');\" style=\"width:5em;margin:5px\" />";
					
			if($user->auth == "share")
				$save_button = $save_all_button = $delete_button = "";
				
				
					
			##############################################
							
			$content .= <<<EOF
					<div class="plane_box">
						<div style="float:left;border:0px solid black;width:100%">
						
							<div style="width:85%;border:0px solid black;float:left;text-align:left">
								<input id="tail_number_{$plane_id}" class="planes_tail_number" title="Tail Number" type="text" name="tail_number_{$plane_id}" value="$tail_number" />							
								<input type="text" title="Manufacturer" name="manufacturer_{$plane_id}" value="{$planes[$i]['manufacturer']}" /> - 
								<input id="type_{$plane_id}" type="text" title="Type" name="type_{$plane_id}" value="$type" /> - 
								<input id="model_{$plane_id}" type="text" title="Model" name="model_{$plane_id}" value="$model" />
							
									<select title="Category/Class" name="category_class_{$plane_id}">
										<option value="1" $cat_1_sel>Airplane / SEL</option>
										<option value="2" $cat_2_sel>Airplane / MEL</option>
										<option value="3" $cat_3_sel>Airplane / SES</option>
										<option value="4" $cat_4_sel>Airplane / MES</option>
										<option value="5" $cat_5_sel>Glider</option>
										<option value="6" $cat_6_sel>Rotorcraft / Helicopter</option>
										<option value="7" $cat_7_sel>Rotorcraft / Gyroplane</option>
										<option value="8" $cat_8_sel>Weight-Shift-Control / Land</option>
										<option value="9" $cat_9_sel>Weight-Shift-Control / Sea</option>
										<option value="10" $cat_10_sel>Powered Parachute / Land</option>
										<option value="11" $cat_11_sel>Powered Parachute / Sea</option>
										<option value="12" $cat_12_sel>Lighter-Than-Air / Airship</option>
										<option value="13" $cat_13_sel>Lighter-Than-Air / Balloon</option>
										<option value="14" $cat_14_sel>Powered Lift</option>
										<option value="15" $cat_15_sel>Airplane / Simulator</option>
										<option value="16" $cat_16_sel>Airplane / FTD</option>
										<option value="17" $cat_17_sel>Helicopter / Simulator</option>	
										<option value="18" $cat_18_sel>Helicopter / FTD</option>
									</select>
							</div>
							
							<div style="width:10%;border:0px solid black;float:right;text-align:right">
								Type Rating: <input type="checkbox" name="type_rating_{$plane_id}" value="1" $type_sel /><br />					
								Tailwheel: <input type="checkbox" name="tailwheel_{$plane_id}" value="1" $tail_sel />
							</div>
						</div>
																		
						<div style="border:0px solid black;width:100%;float:left;">	
							<div style="width:80%;border:0px solid black;float:left;text-align:left">
								Tags: <input id="tags_$plane_id" class="planes_tags" type="text" name="tags_{$plane_id}" value="$tags" />
							</div>
							
							<div style="width:20%;border:0px solid black;float:right;text-align:right">
								$delete_button $save_button
							</div>
						</div>
				</div>
				
				<hr />
EOF;

		}

		$content .= "	<div style=\"float:left;width:100%;text-align:center;padding-top:10px;padding-bottom:20px\">
					<input type=\"hidden\" name=\"all_ids\" value=\"" . trim($all_ids) . "\" />
		
				$save_all_button
		
			     </div>
					</form>";

	}

	$content .= "</div>";



###############################################################

	$webpage->add_content($content);

###############################################################

$html = $webpage->output();

echo $html;

?>
