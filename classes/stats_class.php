<?php
include "main_classes.php";

function trim_value(&$value) 
	{ 
	    $value = trim($value); 
	}

class stats extends auth
{
	function get_tags()
	{
		$sql = "SELECT DISTINCT tag FROM `tags`, `planes` WHERE planes.plane_id = tags.plane_id AND planes.pilot_id =  {$this->pilot_id}";
		$result = mysql_query($sql);
	
		//print "<br /><br />$sql<br /><br />";

		$tags = array();
	
		while($row = mysql_fetch_row($result))
		{
			if(!empty($row[0]))
				$tags[] = $row[0];
		}

		array_walk($tags, 'trim_value');

		return array_values(array_unique($tags));
	}
	
	function get_years()
	{
		$sql = "SELECT DISTINCT YEAR(date) as 'year' FROM flights WHERE pilot_id = {$this->pilot_id} ORDER by year DESC";
		$result = mysql_query($sql);
	
		$years = array();
	
		while($row = mysql_fetch_row($result))
		{
			$years[] = $row[0];
		}
	
		return $years;
	}

	function get_mans()
	{
		$sql = "SELECT DISTINCT manufacturer FROM planes WHERE pilot_id = {$this->pilot_id} AND manufacturer != \"\"";
		$result = mysql_query($sql);
	
		$manufacturer = array();
	
		while($row = mysql_fetch_row($result))
		{
			$manufacturer[] = $row[0];
		}
	
		return $manufacturer;
	}

	function get_models()
	{
		$sql = "SELECT DISTINCT type FROM planes WHERE pilot_id = {$this->pilot_id}";
		$result = mysql_query($sql);
	
		$models = array();
	
		while($row = mysql_fetch_row($result))
		{
			$models[] = $row[0];
		}
	
		return $models;
	}

	function get_category_classes()
	{
		$sql = "SELECT DISTINCT category_class FROM planes WHERE pilot_id = {$this->pilot_id}";
		$result = mysql_query($sql);
	
		$categories = array();
	
		while($row = mysql_fetch_row($result))
		{
			$categories[] = switchout_category($row[0]);
		}
	
		return $categories;
	}
	
	function make_tags_list($type_of_time)
	{
		$tags = $this->get_tags();
		
		$all_tags[] = array();
		
		foreach($tags as $tagz)
		{
			$group_by = str_replace(" ", "_", $tagz);
		
			$sql = "SELECT SUM(total) AS total
				FROM (

				SELECT SUM(flights.$type_of_time) AS $type_of_time, tags AS $group_by
				FROM `planes`, flights
				WHERE match (tags)
				AGAINST (\"$tagz\") AND flights.plane_id = planes.plane_id AND planes.pilot_id = {$this->pilot_id}
				GROUP BY $group_by

				) AS SUB";
				
			$result = mysql_query($sql);
			$rows = mysql_fetch_array($result);
			
			$all_tags[] = array($rows[0], $rows[1]);
		
		}

	}
	
	########################################################################################################################################
	########################################################################################################################################
	########################################################################################################################################
	########################################################################################################################################

	function get_normal_list_sql($x, $y)			//xc, night, dual given + recieved
	{
		if($x == "year")	//non typical
		{
			return "SELECT *
				FROM (
					SELECT YEAR(date) as year, COALESCE(sum($y),0) AS total_total
					FROM flights
					WHERE flights.pilot_id = {$this->pilot_id}
					GROUP BY year
					ORDER BY total_total DESC
					) as sdfjkdfs
				WHERE total_total > 0";
		}
		
		elseif($x == "month")	//non typical
		{
			return "SELECT y_m, total_total
				FROM (
					SELECT DATE_FORMAT(date,'%M %Y') as y_m, COALESCE(sum($y),0) AS total_total,
						DATE_FORMAT(date,'%Y%m') as sort
					FROM flights
					WHERE flights.pilot_id = {$this->pilot_id}
					GROUP BY sort
					) as sdff
				WHERE total_total > 0
				ORDER BY total_total DESC";
		}
		else
			return "SELECT *
				FROM(
					SELECT $x, COALESCE(sum($y),0) AS total_y
					FROM planes, flights
					WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $x != \"\"
					GROUP BY $x) as sdfsfdjk
				WHERE total_y > 0
				ORDER BY total_y DESC";
	}

	function get_total_list_sql($x)				//only total time
	{
		if($x == "year")	//non typical
		{
			return "SELECT YEAR(date) as year, COALESCE(sum(total),0) AS total_total
				FROM flights
				WHERE flights.pilot_id = {$this->pilot_id} AND total > 0
				GROUP BY year
				ORDER BY total_total DESC";
		}
		
		elseif($x == "month")	//non typical
		{
			return "SELECT y_m, (total_simulator + total_total) as them_both
				FROM (
					SELECT DATE_FORMAT(date,'%M %Y') as y_m, COALESCE(sum(total),0) AS total_total,
						COALESCE(SUM(flights.simulator),0) AS total_simulator, DATE_FORMAT(date,'%Y%m') as sort
					FROM flights
					WHERE flights.pilot_id = {$this->pilot_id}
					GROUP BY sort
					) as sdff
				WHERE (total_simulator > 0 OR total_total > 0)
				ORDER BY them_both DESC";
		}
		else
			return "SELECT $x, (total_total + total_simulator) as the_total FROM (
					SELECT $x, COALESCE(sum(total),0) AS total_total, COALESCE(SUM(flights.simulator),0) AS total_simulator
					FROM planes, flights
					WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $x != \"\"
					GROUP BY $x
				) AS mytable	ORDER BY the_total DESC";
	}
	
	function get_composite_list_sql($x, $type)		// pic/sic, instrument and landings
	{
		if($type == "pic/sic")
			{$item1 = "pic"; $item2 = "sic";}
		
		if($type == "landings")
			{$item1 = "day_landings"; $item2 = "night_landings";}
			
		if($type == "instrument")
			{$item1 = "sim_instrument"; $item2 = "act_instrument";}
			
		if($x == "year")	//non typical
		{
			return "SELECT *
				FROM (	
					SELECT YEAR(date) as year, COALESCE(SUM($item1),0) as total_1, COALESCE(SUM($item2),0) as total_2
					FROM flights
					WHERE flights.pilot_id = {$this->pilot_id}
					GROUP BY year
					) AS dfgdf
				WHERE (total_1 > 0 OR total_2 > 0 )
				ORDER BY (total_1 + total_2) DESC";
		}
		
		elseif($x == "month")	//non typical
		{
			return "SELECT y_m, total_1, total_2
				FROM (
					SELECT DATE_FORMAT(date,'%M %Y') as y_m, COALESCE(sum($item1),0) AS total_1,
						COALESCE(SUM($item2),0) AS total_2
					FROM flights
					WHERE flights.pilot_id = {$this->pilot_id}
					GROUP BY y_m
					) as sdff
				WHERE (total_1 > 0 OR total_2 > 0 )
				ORDER BY (total_1 + total_2) DESC";
		}
		
		else
			return "SELECT *, COALESCE(total_1,0) + COALESCE(total_2,0) AS them_both
				FROM (
					SELECT $x, COALESCE(sum($item1),0) AS total_1, COALESCE(SUM(flights.$item2),0) AS total_2
					FROM planes, flights
					WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $x != \"\"
					GROUP BY $x
					) AS mytable
				WHERE (total_1 > 0 OR total_2 > 0 )
				ORDER BY (total_1 + total_2) DESC";
	}
	
	########################################################################################################################################
	########################################################################################################################################
	########################################################################################################################################
	########################################################################################################################################
	
	function get_list_sql($item, $type_of_time)
	{
		if($item == "student" || $item == "instructor" || $item == "fo" || $item == "captain")
			$is_not_blank = $group_by = $sql_item = "flights.$item";
		else
			$is_not_blank = $group_by = $sql_item = "planes.$item";
	
		if($type_of_time == "total")			//group simulator and total time together
		
			return "SELECT *, COALESCE(total_total,0) + COALESCE(total_simulator,0) AS them_both
				FROM (
					SELECT $sql_item, sum(total) AS total_total, SUM(flights.simulator) AS total_simulator					
					FROM planes, flights
					WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $is_not_blank != \"\"
					GROUP BY $group_by
					) AS mytable
					
				ORDER BY them_both DESC";
					
		elseif($type_of_time == "instrument")		//group sim instrument and actual instrument time together
			return "SELECT *, COALESCE(total_actual,0) + COALESCE(total_hood,0) AS them_both
				FROM (
					SELECT $sql_item, COALESCE(sum(sim_instrument),0) AS total_hood, COALESCE(SUM(flights.act_instrument),0) AS total_actual
					FROM planes, flights
					WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $is_not_blank != \"\"
					GROUP BY $group_by
					) AS mytable
					
				WHERE (total_hood > 0 OR total_actual > 0 ) 
					
				ORDER BY them_both DESC";
				
		elseif($type_of_time == "landings")		//night and day landings are grouped together
			return "SELECT *, COALESCE(total_day,0) + COALESCE(total_night,0) AS them_both
				FROM (
					SELECT $sql_item, COALESCE(sum(day_landings),0) AS total_day, COALESCE(SUM(flights.night_landings),0) AS total_night
					FROM planes, flights
					WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $is_not_blank != \"\"
					GROUP BY $group_by
					) AS mytable
					
				WHERE (total_day > 0 OR total_night > 0 ) 
					
				ORDER BY them_both DESC";
				
		elseif($type_of_time == "pic_sic")		//group pic and sic
			return "SELECT *, COALESCE(total_pic,0) + COALESCE(total_sic,0) AS them_both
				FROM (
					SELECT $sql_item, COALESCE(sum(pic),0) AS total_pic, COALESCE(SUM(flights.sic),0) AS total_sic
					FROM planes, flights
					WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $is_not_blank != \"\"
					GROUP BY $group_by
					) AS mytable
					
				WHERE (total_pic > 0 OR total_sic > 0 ) 
					
				ORDER BY them_both DESC";
		
		else						//all other types of time: night, xc, dual
			return "SELECT $sql_item, SUM(flights.$type_of_time) AS the_total
				FROM planes, flights
				WHERE flights.pilot_id = {$this->pilot_id} AND planes.plane_id = flights.plane_id AND $is_not_blank != \"\"
					AND `$type_of_time` IS NOT NULL
				GROUP BY $group_by
				ORDER by the_total DESC";				
	}
	
	function get_line_sql()
	{
	
		return "SELECT DATE_FORMAT(date,'%Y %M') as y_m, COALESCE(sum(total),0) AS total_total, COALESCE(SUM(flights.simulator),0) AS total_simulator, DATE_FORMAT(date,'%Y%m') as sort
		FROM flights
		WHERE flights.pilot_id = 1
		GROUP BY sort";
	
	
	
	
	}
	
	function make_8710()
	{
		foreach(array("airplane", "rotor", "pl", "glider", "lta") as $category)
		{
			switch($category)
			{
				case "airplane": {$nums = "(1,2,3,4)"; break;}
				case "rotor": {$nums = "(6,7)"; break;}
				case "pl": {$nums = "(14)"; break;}
				case "glider": {$nums = "(5)"; break;}
				case "lta": {$nums = "(12,13)"; break;}
			}
			
			$sql = "SELECT SUM(total) as 'total', SUM(sim_instrument) + SUM(act_instrument) as 'inst', SUM(dual_recieved) as 'dual', SUM(solo) as 'solo', SUM(pic) as 'pic',
				SUM(sic) as 'sic', SUM(night_landings) as 'night_land'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
			$result = mysql_query($sql);
			$times[$category] = mysql_fetch_assoc($result);
			
			/////////////////////////////////////
			
			$sql = "SELECT sum(xc) as 'xc_dual'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.xc = flights.dual_recieved AND flights.dual_recieved != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
			$result = mysql_query($sql);
			
			$temp = mysql_fetch_array($result);
		
			$times[$category]['xc_dual'] = $temp[0];
			
			/////////////////////////////////////
			
			$sql = "SELECT sum(xc) as 'xc_solo'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.xc = flights.solo AND flights.solo != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
			$result = mysql_query($sql);
			
			$temp = mysql_fetch_array($result);
		
			$times[$category]['xc_solo'] = $temp[0];
			
			/////////////////////////////////////
			
			$sql = "SELECT sum(xc) as 'xc_pic'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.xc = flights.pic AND flights.pic != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
			$result = mysql_query($sql);
			
			$temp = mysql_fetch_array($result);
		
			$times[$category]['xc_pic'] = $temp[0];
			
			/////////////////////////////////////
			
			if($category != "glider" && $category != "lta")
			{
				$sql = "SELECT sum(xc) as 'xc_sic'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.xc = flights.sic AND flights.sic != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";

				$result = mysql_query($sql);
				$temp = mysql_fetch_array($result);
				$times[$category]['xc_sic'] = $temp[0];
			}
			
			/////////////////////////////////////

			if($category != "glider")
			{	
				$sql = "SELECT sum(night) as 'night_dual'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.dual_recieved != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
					
				$result = mysql_query($sql);
				$temp = mysql_fetch_array($result);
				$times[$category]['night_dual'] = $temp[0];
			}
			
			/////////////////////////////////////
			
			if($category != "glider" && $category != "lta")
			{
				$sql = "SELECT sum(night) as 'night_pic'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.pic != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
				$result = mysql_query($sql);
				$temp = mysql_fetch_array($result);
				$times[$category]['night_pic'] = $temp[0];
			}
			
			/////////////////////////////////////
			
			if($category != "glider" && $category != "lta")
			{
				$sql = "SELECT sum(night) as 'night_sic'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.sic != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
				$result = mysql_query($sql);
				$temp = mysql_fetch_array($result);
				$times[$category]['night_sic'] = $temp[0];
			}
			
			/////////////////////////////////////
			
			if($category != "glider" && $category != "lta")
			{
				$sql = "SELECT sum(night_landings) as 'night_land_pic'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.night = flights.pic AND flights.night != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
				$result = mysql_query($sql);
				$temp = mysql_fetch_array($result);
				$times[$category]['night_land_pic'] = $temp[0];
			}
			
			/////////////////////////////////////
			
			if($category != "glider" && $category != "lta")
			{
				$sql = "SELECT sum(night_landings) as 'night_land_sic'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND flights.sic != 0
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
				$result = mysql_query($sql);
				$temp = mysql_fetch_array($result);
				$times[$category]['night_land_sic'] = $temp[0];
			}
		}
		
		foreach(array("sim", "ftd") as $category)
		{
			switch($category)
			{
				case "ftd": {$nums = "(16,18)"; break;}
				case "sim": {$nums = "(15,17)"; break;}
			}
			$sql = "SELECT sum(dual_recieved) as 'dual', sum(sim_instrument) as 'inst'
					FROM flights, planes
					WHERE planes.plane_id = flights.plane_id
					AND planes.category_class IN $nums
					AND flights.pilot_id = {$this->pilot_id}";
				
				$result = mysql_query($sql);
				$times[$category] = mysql_fetch_assoc($result);
		}
	
		return <<<EOF
	
				<table summary="8710 Data" class="eighty710_table">
					<thead>
						<tr>
							<td >&nbsp;</td>
							<td >Total</td>
							<td >Instruction Received</td>
							<td >Solo</td>
							<td >Pilot in Command (PIC)</td>
							<td >Cross Country Instruction Recieved</td>
							<td >Cross Country Solo</td>
							<td >Cross Country PIC</td>
							<td >Instrument</td>
							<td >Night Instruction Received</td>
							<td >Night Take-off/<br />Landings</td>
							<td >Night PIC</td>
							<td >Night Take-off/<br />Landings PIC</td>
							<td >Number of Flights</td>
							<td >Number of Aero-Tows</td>
							<td >Number of Ground Launches</td>
							<td >Number of Powered Launches</td>
						</tr>
					</thead>
	
					<tbody>
						<tr>
							<td rowspan="2">Airplanes</td>
							<td rowspan="2">&nbsp;{$times['airplane']['total']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['airplane']['dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['airplane']['solo']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['airplane']['pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['airplane']['xc_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['airplane']['xc_solo']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['airplane']['xc_pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['airplane']['inst']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['airplane']['night_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['airplane']['night_land']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['airplane']['night_pic']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['airplane']['night_land_pic']}&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
						</tr>
			
						<tr>
							<td>&nbsp;{$times['airplane']['sic']}&nbsp;</td>
							<td>&nbsp;{$times['airplane']['xc_sic']}&nbsp;</td>
							<td>&nbsp;{$times['airplane']['night_sic']}&nbsp;</td>
							<td>&nbsp;{$times['airplane']['night_land_sic']}&nbsp;</td>
			
						</tr>
			
						<tr>
							<td rowspan="2">Rotor-<br />craft</td>
							<td rowspan="2">&nbsp;{$times['rotor']['total']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['rotor']['dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['rotor']['solo']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['rotor']['pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['rotor']['xc_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['rotor']['xc_solo']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['rotor']['xc_pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['rotor']['inst']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['rotor']['night_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['rotor']['night_land']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['rotor']['night_pic']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['rotor']['night_land_pic']}&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
						</tr>
			
						<tr>
							<td>&nbsp;{$times['rotor']['sic']}&nbsp;</td>
							<td>&nbsp;{$times['rotor']['xc_sic']}&nbsp;</td>
							<td>&nbsp;{$times['rotor']['night_sic']}&nbsp;</td>
							<td>&nbsp;{$times['rotor']['night_land_sic']}&nbsp;</td>
			
						</tr>
						<tr>
							<td rowspan="2">Powered Lift</td>
							<td rowspan="2">&nbsp;{$times['pl']['total']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['pl']['dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['pl']['solo']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['pl']['pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['pl']['xc_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['pl']['xc_solo']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['pl']['xc_pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['pl']['inst']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['pl']['night_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['pl']['night_land']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['pl']['night_pic']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['pl']['night_land_pic']}&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
						</tr>
			
						<tr>
							<td>&nbsp;{$times['pl']['sic']}&nbsp;</td>
							<td>&nbsp;{$times['pl']['xc_sic']}&nbsp;</td>
							<td>&nbsp;{$times['pl']['night_sic']}&nbsp;</td>
							<td>&nbsp;{$times['pl']['night_land_sic']}&nbsp;</td>
			
						</tr>
			
						<tr>
							<td rowspan="2">Gliders</td>
							<td rowspan="2">&nbsp;{$times['glider']['total']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['solo']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['xc_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['xc_solo']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['xc_pic']}&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['num_flights']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['aero_tows']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['ground_launches']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['glider']['powered_launches']}&nbsp;</td>
						</tr>
			
						<tr><td style="display:none"></td></tr>
			
						<tr>
							<td rowspan="2">Lighter Than Air</td>
							<td rowspan="2">&nbsp;{$times['lta']['total']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['solo']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['xc_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['xc_solo']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['xc_pic']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['inst']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['night_dual']}&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['night_land']}&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2">&nbsp;{$times['lta']['num_flights']}&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="2" class="eighty710_blanked">&nbsp;</td>
						</tr>
			
						<tr><td style="display:none"></td></tr>
			
						<tr>
							<td rowspan="1">Simulator</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['sim']['dual']}&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['sim']['inst']}&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
							<td rowspan="3" class="eighty710_blanked">&nbsp;</td>
						</tr>
			
						<tr>
							<td rowspan="1">FTD</td>
							<td rowspan="1">&nbsp;{$times['ftd']['dual']}&nbsp;</td>
							<td rowspan="1">&nbsp;{$times['ftd']['inst']}&nbsp;</td>
						</tr>
			
						<tr>
							<td rowspan="1">PCATD</td>
							<td rowspan="1">&nbsp;</td>
							<td rowspan="1">&nbsp;</td>
				
						</tr>
					</tbody>
		
				</table>
EOF;
	}
	
}
?>
