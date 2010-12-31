<?php

include "logbook_class.php";

class logbook_print extends logbook
{
	function make_print_rows($columns)
	{
		$sql = sprintf(
				"SELECT flights.*, planes.tail_number, planes.type
				FROM flights LEFT JOIN planes
				ON planes.plane_id = flights.plane_id
				WHERE flights.pilot_id='%s'
				ORDER BY date, flight_id",
				$this->pilot_id
				);
				
				
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
	
		if(empty($row))
			return "<tr><td class=\"odd\" colspan=\"22\"><br /><big>No Items to Show</big><br />&nbsp;</td></tr>";
				
		do
		{//each iteration is a row in the logbook table

			$flight_id = $row['flight_id'];
			$plane_id = $row['plane_id'];
			$sql_date = $row['date'];
			$simulator = $row['simulator'];
			$type = $row['type'];

			//for coloring of each row
			if($color == "even")
				$color = "odd";
			else    $color = "even";

			//fill the data variables, and make the page total data
			foreach($this->text_box_fields as $field)
			{
				$$field = $row["$field"];
			
				$this->page_totals["$field"] += $row["$field"];
			}
			
			foreach($this->checkbox_fields as $field)
			{
				$$field = $row["$field"] > 0 ? "checked" : "";
			}
			
			
			if($total == '' && $simulator != '' && !$columns['sim'])		//if total is empty, sim is not empty and the sim column is not seperated...
				$display_total = "($simulator)";
				
			else $display_total = $total;
				
			//reformat the date
			$date = date($this->date_format,strtotime($sql_date));

			//select the proper plane to display
			$display_plane = $row['tail_number'];
			
			$flying = "";
			
			if($row['cfi_checkride'])
				$flying .= "<span class=\"flying_event\">[CFI Checkride]</span> ";
				
			if($row['pilot_checkride'])
				$flying .= "<span class=\"flying_event\">[Pilot Checkride]</span> ";

			if($row['bfr'])
				$flying .= "<span class=\"flying_event\">[Flight Review]</span> ";

			if($row['ipc'])
				$flying .= "<span class=\"flying_event\">[IPC]</span> ";
			
			///////
			
			switch($row['medical_class'])
			{
				case 1:		{$ordinal = "1st"; $non_flying_number = 1; break;}
				case 2:		{$ordinal = "2nd"; $non_flying_number = 2; break;}
				case 3:		{$ordinal = "3rd"; $non_flying_number = 3; break;}
				default:	 $ordinal = "";
			}
			
			$non_flying = "";
			
			if($ordinal)
				$non_flying = "$ordinal Class Medical";
			
			elseif($row['signoff'])
			{
				$non_flying = "Student Signoff";
				$non_flying_number = 5;
			}
			
			elseif($row['cfi_refresher'])
			{
				$non_flying = "CFI refresher";
				$non_flying_number = 4;
			}
			
			if($non_flying)
			{
				if(!($remarks == "")) $display_remarks = " - " . $remarks;
				
				$items .=  "<tr class=\"$color\">
						<td class=\"date_col\" title=\"Date\">$date</td>
											
						<td class=\"non_flying_event\" colspan=\"21\">
							{$non_flying}{$display_remarks}
						</td>
				 	    </tr>";
				
				$display_remarks = "";
			}
			else
			{
			
				#####################################################
			
				if($row['holding'] && $row['tracking'])
					if($approaches == "") $disp_approaches = "HT"; else $disp_approaches = $approaches . " HT";
				
				elseif($row['holding'])
					if($approaches == "") $disp_approaches = "H"; else $disp_approaches = $approaches . " H";
				
				elseif($row['tracking'])
					if($approaches == "") $disp_approaches = "T"; else $disp_approaches = $approaches . " T";
					
				else
					$disp_approaches = $approaches;

				#####################################################

				$items .=  "<tr class=\"$color\">
						<td title=\"Date\" class=\"date_col\">$date</td>
						
						<td title=\"Plane\">$display_plane</td>";
						
						if($columns['type'])
							$items .=  "<td title=\"Type\">$type</td>";
							
						$items .=  "<td title=\"Route\">$route</td>
								<td title=\"Total\">$display_total</td>
								<td title=\"PIC\" class=\"pic_col\">$pic</td>";
								
						if($columns['sim'])
							$items .=  "<td title=\"Simulator\" class=\"sim_col\">$simulator</td>";
						
						if($columns['solo'])
							$items .=  "<td title=\"Solo\" class=\"solo_col\">$solo</td>";
							
						if($columns['sic'])
							$items .=  "<td title=\"SIC\" class=\"sic_col\">$sic</td>";
							
						if($columns['dual_recieved'])
							$items .=  "<td title=\"Dual Recieved\" class=\"dual_recieved_col\">$dual_recieved</td>";
							
						if($columns['dual_given'])
							$items .=  "<td title=\"Dual Given\" class=\"dual_given_col\">$dual_given</td>";
						
						if($columns['act_instrument'])
							$items .=  "<td title=\"Actual Instrument\" class=\"act_col\">$act_instrument</td>";
							
						if($columns['sim_instrument'])
							$items .=  "<td title=\"Simulated Instrument\" class=\"sim_col\">$sim_instrument</td>";
							
						if($columns['approaches'])
							$items .=  "<td title=\"Approaches\" class=\"approaches_col\">$disp_approaches</td>";
							
						if($columns['night'])
							$items .=  "<td title=\"Night\" class=\"night_col\">$night</td>";
							
						$items .=  "<td title=\"Cross Country\" class=\"xc_col\">$xc</td>
								<td title=\"Day Landings\" class=\"day_land_col\">$day_landings</td>
								<td title=\"Night Landings\" class=\"night_land_col\">$night_landings</td>";
								
						if($columns['student'])
							$items .=  "<td title=\"Student\" class=\"student_col\">$student</td>";
							
						if($columns['instructor'])
							$items .=  "<td title=\"Instructor\" class=\"instructor_col\">$instructor</td>";
							
						if($columns['fo'])
							$items .=  "<td title=\"First Officer\" class=\"fo_col\">$fo</td>";
							
						if($columns['captain'])
							$items .=  "<td title=\"Captain\" class=\"captain_col\">$captain</td>";
							
						if($columns['flight_number'])
							$items .=  "<td title=\"Flight Number\" class=\"flight_number_col\">$flight_number</td>";
							
						if($columns['remarks'])
							$items .=  "<td title=\"Remarks\" style=\"text-align:left\">{$flying}$remarks</td>";
							

				$items .=  "</tr>\n\n";
			}
			
		}while($row = mysql_fetch_array($result));
		
		return $items;
	}
	
	function make_print_logbook($columns)
	{
		return "<table class=\"logbook_print\" summary=\"logbook table\">" .
			
				"<thead>" . $this->make_print_header($columns) . "</thead>" . 
			
					"<tbody>" . 
			
						$this->make_print_rows($columns) .
			
						$this->make_print_total_rows($columns) .
			
					"</tbody>
			
			</table>";
	}
	
	function make_print_header($columns)
	{
		$display .= "<tr class=\"header_row\">
						<td class=\"date_col\">Date</td>
						<td class=\"plane_col\">Plane</td>";
				
				if($columns['type'])
					$display .= "<td class=\"plane_col\">Type</td>";
				
				$display .= "<td class=\"route_col\">Route</td>
						<td class=\"total_col\">Total</td>
						<td class=\"pic_col\">PIC</td>";
						
				if($columns['sim'])
					$display .= "<td class=\"sim_col\">Sim</td>";
				
				if($columns['solo'])
					$display .= "<td class=\"solo_col\">Solo</td>";
				
				
				if($columns['sic'])
					$display .= "<td class=\"sic_col\">SIC</td>";
					
				if($columns['dual_recieved'])
					$display .= "<td class=\"dual_recieved_col\">Dual Rec.</td>";
					
					
				if($columns['dual_given'])
					$display .= "<td class=\"dual_given_col\">Dual Given</td>";
					
				if($columns['act_instrument'])
					$display .= "<td class=\"act_col\">Actual</td>";
					
				if($columns['sim_instrument'])
					$display .= "<td class=\"sim_col\">Hood</td>";
					
				if($columns['approaches'])
					$display .= "<td class=\"approaches_col\">App.</td>";
					
				if($columns['night'])
					$display .= "<td class=\"night_col\">Night</td>";
					
				$display .= "<td class=\"xc_col\">XC</td>
					<td class=\"day_land_col\">Day Land.</td>
					<td class=\"night_land_col\">Night Land.</td>";
					
				if($columns['student'])
					$display .= "<td class=\"student_col\">Student</td>";
					
				if($columns['instructor'])
					$display .= "<td class=\"instructor_col\">Instructor</td>";
					
				if($columns['fo'])
					$display .= "<td class=\"fo_col\">First Officer</td>";
					
				if($columns['captain'])
					$display .= "<td class=\"captain_col\">Captain</td>";
					
				if($columns['flight_number'])
					$display .= "<td class=\"flight_number_col\">Flt Num</td>";
					
				if($columns['remarks'])
					$display .= "<td class=\"remarks_col\">Remarks</td>";
					
			$display .= "</tr>";
			
			return $display;
	}

	function make_print_total_rows($columns)
	{
		$sql = sprintf(
			"SELECT SUM(total), SUM(pic), SUM(solo), SUM(sic), SUM(dual_recieved), SUM(dual_given), SUM(act_instrument), SUM(simulator),
				SUM(sim_instrument), SUM(night), SUM(xc), SUM(day_landings), SUM(night_landings), SUM(approaches)
			FROM flights
			WHERE pilot_id='%s' LIMIT 1",
			mres($this->pilot_id)
			);

		$result = mysql_query($sql);

		$row = mysql_fetch_assoc($result);
		
		foreach($this->text_box_fields as $header)
		{
			$overall[$header] = $row["SUM($header)"] == "" ? "0.0" : $row["SUM($header)"];
		}
		
		$overall["sim"] = $row["SUM(simulator)"] == "" ? "0.0" : $row["SUM(simulator)"];
		
		$colspan = $columns['type'] ? 4 : 3;
		
		
		$display = "<tr class=\"overall_row\">
				<td colspan=\"$colspan\" style=\"text-align:right;background-color:transparent;border-width:0px\">Overall Totals: </td>
				<td class=\"total_col\">" . number_format($overall['total'],1) . "</td>
				<td class=\"pic_col\">" . number_format($overall['pic'],1) . "</td>";
				
				if($columns['sim'])
					$display .= "<td class=\"sim_col\">" . number_format($overall['sim'],1) . "</td>";
				
				if($columns['solo'])
					$display .= "<td class=\"solo_col\">" . number_format($overall['solo'],1) . "</td>";
					
				if($columns['sic'])
					$display .= "<td class=\"sic_col\">" . number_format($overall['sic'],1) . "</td>";
					
				if($columns['dual_recieved'])
					$display .= "<td class=\"dual_recieved_col\">" . number_format($overall['dual_recieved'],1) . "</td>";
					
				if($columns['dual_given'])
					$display .= "<td class=\"dual_given_col\">" . number_format($overall['dual_given'],1) . "</td>";
						
				if($columns['act_instrument'])
					$display .= "<td class=\"act_col\">" . number_format($overall['act_instrument'],1) . "</td>";
					
				if($columns['sim_instrument'])
					$display .= "<td class=\"sim_col\">" . number_format($overall['sim_instrument'],1) . "</td>";
					
				if($columns['approaches'])
					$display .= "<td class=\"approaches_col\">" . number_format($overall['approaches'],0) . "</td>";
					
				if($columns['night'])	
					$display .= "<td class=\"night_col\">" . number_format($overall['night'],1) . "</td>";
					
				
				$display .= "<td class=\"xc_col\">" . number_format($overall['xc'],1) . "</td>
						<td class=\"day_land_col\">" . number_format($overall['day_landings'],0) . "</td>
						<td class=\"night_land_col\">" . number_format($overall['night_landings'],0) . "</td>";
			
			
				if($columns['remarks'])	
					$display .= "<td colspan=\"30\" style=\"border:0\"></td>";
			
			$display .= "</tr>";
			
		return $display;
			
	}

}
?>
