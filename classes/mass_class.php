<?php
include "logbook_class.php";

class mass extends logbook
{

	var $abbreviated = array("date" => "Date (mm/dd/yyyy)", "route" => "Route", "total" => "Total", "pic" => "PIC", "sic" => "SIC", "solo" => "Solo", "dual_recieved" => "Rec",
				 "dual_given" => "Giv", "sim_instrument" => "Hd.", "act_instrument" => "Act", "night" => "Ngt", "xc" => "XC", "approaches" => "Ap.",
				 "day_landings" => "D.", "night_landings" => "N.","student" => "Student", "instructor" => "Instructor", "flight_number" => "Flight Number",
				 "remarks" => "Remarks", "fo" => "First Officer", "captain" => "Captain");
				 
	function make_mass_entry_rows()		//these are actually all not blank, mass EDIT only
	{
		
	
		$this->get_logbook_info();
		$sql = sprintf(
				"SELECT flights.*, planes.tail_number
				FROM flights LEFT JOIN planes
				ON planes.plane_id = flights.plane_id
				WHERE flights.pilot_id='%s'
				ORDER BY date, flight_id LIMIT " . ($this->current_page-1) * $this->logbook_per_page . ",{$this->logbook_per_page}",
				mysql_real_escape_string($this->pilot_id)
				);
			
		$result = mysql_query($sql);
		
		#####################################################
		
		
		$output .= $this->make_pagination("mass_entry");
		
		
		
		$output .= "	
				<table id=\"mass_entry_table\" summary=\"header row\">
					<tr>
						<td></td>
						<td>Plane</td>
						<td>Total</td>
						<td>PIC</td>
						<td>Solo</td>
						<td>SIC</td>
						<td>Dual<br />Rec.</td>
						<td>Dual<br />Given</td>
						<td>Actual</td>
						<td>Hood</td>
						<td>App.</td>		
						<td>Night</td>
						<td>XC</td>
						<td>Day<br />Land.</td>
						<td>Night<br />Land.</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>";
			
		$color == "mass_color_1";
		
		
		$i = 1;
		while($row = mysql_fetch_assoc($result))
		{
			$dropdown = $this->make_plane_options($row['plane_id'], false, true);
			$formatted_date = date('m/d/Y', strtotime($row['date']));
			$flight_id = $row['flight_id'];
			$color = $color == "mass_color_1" ? "mass_color_2" : "mass_color_1";
			
			foreach(array_merge($this->text_box_fields, array("simulator")) as $field)
			{
				if($row[$field] == "")
				{
					$grayed[$field] = "mass_grayed";
					$row[$field] = $this->abbreviated[$field];
				}
				
				else	$grayed[$field] = "";
			}
			
			//var_dump($row);
			
			if($row['medical_class'] >= 1 || $row['signoff'] >= 1 || $row['cfi_refresher'] >= 1)				//non flying event
			{
				$first_sel = $row['medical_class'] == "1" ? "checked  = \"checked\"" : "";
				$second_sel = $row['medical_class'] == "2" ? "checked = \"checked\"" : "";
				$third_sel = $row['medical_class'] == "3" ? "checked = \"checked\"" : "";
				$signoff_sel = $row['signoff'] == "1" ? "checked = \"checked\"" : "";
				$refresher_sel = $row['cfi_refresher'] == "1" ? "checked = \"checked\"" : "";
				
				$output .= <<<EOF
							<tr class="$color" style="font-size:small">
								<td>
									<input type="hidden" name="entry_{$i}" value="$flight_id" />
									<input type="text" name="date_{$flight_id}" class="mass_names" value="$formatted_date" />
								</td>
								
								<td colspan="14" align="right">
									<input type="radio" name="medical_class_{$flight_id}" value="1" $first_sel />1st Class
									&nbsp;&nbsp;<input type="radio" name="medical_class_{$flight_id}" value="2" $second_sel />2nd Class
									&nbsp;&nbsp;<input type="radio" name="medical_class_{$flight_id}" value="3" $third_sel />3rd Class
									&nbsp;&nbsp;<input type="radio" name="signoff_{$flight_id}" value="1" $signoff_sel />Signoff
									&nbsp;&nbsp;<input type="radio" name="cfi_refresher_{$flight_id}" value="1" $refresher_sel />CFI Refresher
								</td>
								<td colspan="3">
									<input type="text" name="remarks_{$flight_id}" class="non_remarks" value="{$row['remarks']}"
									onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Remarks" />
								</td>
							</tr>
EOF;
			}
			
			else														//flying event
			{
			
			
			
			if($row['total'] == "Total")
			{
				$display_total = $row['simulator'];
				$grayed["total"] = "";
			}
			else	$display_total = $row['total'];
			
			$holding_sel = $row['holding'] == "1" ? "checked = \"checked\"" : "";
			$tracking_sel = $row['tracking'] == "1" ? "checked = \"checked\"" : "";
			$pilot_sel = $row['pilot_checkride'] == "1" ? "checked = \"checked\"" : "";
			$inst_sel = $row['cfi_checkride'] == 1 ? "checked = \"checked\"" : "";
			$bfr_sel = $row['bfr'] == 1 ? "checked = \"checked\"" : "";
			$ipc_sel = $row['ipc'] == 1 ? "checked = \"checked\"" : "";
			
			$output .= <<<EOF
			<tr class="$color" style="font-size:xx-small">
			
				<td>	<input type="hidden" name="entry_{$i}" value="$flight_id" />
				
					<input autocomplete="off" type="text" name="date_{$flight_id}" class="mass_names" value="$formatted_date"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" /><br />
					
					<input type="text" name="route_{$flight_id}" class="mass_names" value="{$row['route']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
				</td>
				
				<td>
					<select name="plane_id_{$flight_id}" style="width:20em">$dropdown</select>
				</td>
				
				<td>
					<input autocomplete="off" type="text" name="total_{$flight_id}" class="mass_numbers {$grayed['total']}" value="{$display_total}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Total" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="pic_{$flight_id}" class="mass_numbers {$grayed['pic']}" value="{$row['pic']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="PIC" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="solo_{$flight_id}" class="mass_numbers {$grayed['solo']}" value="{$row['solo']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Solo" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="sic_{$flight_id}" class="mass_numbers {$grayed['sic']}" value="{$row['sic']}" 
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="dual_recieved_{$flight_id}" class="mass_numbers  {$grayed['dual_recieved']}" value="{$row['dual_recieved']}" 
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Dual Recieved" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="dual_given_{$flight_id}" class="mass_numbers  {$grayed['dual_given']}" value="{$row['dual_given']}" 
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Dual Given" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="act_instrument_{$flight_id}" class="mass_numbers  {$grayed['act_instrument']}" value="{$row['act_instrument']}" 
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Actual Instrument" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="sim_instrument_{$flight_id}" class="mass_numbers  {$grayed['sim_instrument']}" value="{$row['sim_instrument']}" 
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Simulated Instrument" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="approaches_{$flight_id}" class="mass_small_numbers {$grayed['approaches']}" value="{$row['approaches']}" 
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Approaches" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="night_{$flight_id}" class="mass_numbers {$grayed['night']}" value="{$row['night']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Night" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="xc_{$flight_id}" class="mass_numbers {$grayed['xc']}" value="{$row['xc']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Cross Country" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="day_landings_{$flight_id}" class="mass_small_numbers {$grayed['day_landings']}" value="{$row['day_landings']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Day Landings" />
				</td>
				<td>
					<input autocomplete="off" type="text" name="night_landings_{$flight_id}" class="mass_small_numbers {$grayed['night_landings']}" value="{$row['night_landings']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Night Landings" />
				</td>
				<td>
					<input type="text" name="student_{$flight_id}" class="mass_names {$grayed['student']}" value="{$row['student']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Student" /><br />
					
					<input type="text" name="instructor_{$flight_id}" class="mass_names {$grayed['instructor']}" value="{$row['instructor']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Instructor" /><br />
				</td>
				<td>
					<input type="text" name="fo_{$flight_id}" class="mass_names {$grayed['fo']}" value="{$row['fo']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="First Officer" /><br />
					
					<input type="text" name="captain_{$flight_id}" class="mass_names {$grayed['captain']}" value="{$row['captain']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Captain" /><br />
				</td>
				<td>
					<input type="text" name="flight_number_{$flight_id}" class="mass_names {$grayed['flight_number']}" value="{$row['flight_number']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Flight Number" /><br />
					
					<input type="text" name="remarks_{$flight_id}" class="mass_names {$grayed['remarks']}" value="{$row['remarks']}"
					onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" title="Remarks" />
				</td>
		</tr>
				
		<tr class="$color">
				<td>
					<input type="checkbox" name="delete_{$flight_id}" value="1" />Delete
				</td>	
						
				<td colspan="17" align="right">
					<table class="mass_checkmark_boxes" summary="flying event checkboxes">
					<tr>
						<td style="width:16%"><input type="checkbox" name="holding_{$flight_id}" value="1" $holding_sel />Holding</td>
						<td style="width:16%"><input type="checkbox" name="tracking_{$flight_id}" value="1" $tracking_sel />I&amp;T</td>
						<td style="width:16%"><input type="checkbox" name="ipc_{$flight_id}" value="1" $ipc_sel />IPC</td>
						<td style="width:16%"><input type="checkbox" name="bfr_{$flight_id}" value="1" $bfr_sel />Flight Review</td>
						<td style="width:16%"><input type="checkbox" name="pilot_checkride_{$flight_id}" value="1" $pilot_sel />Pilot Checkride</td>
						<td style="width:16%"><input type="checkbox" name="cfi_checkride_{$flight_id}" value="1" $inst_sel />CFI Checkride</td>
					</tr>
					</table>
				</td>
		</tr>
EOF;
			}
			
			$i++;
		}
	
	
	$output .= "</table>
				<input type=\"submit\" value=\"Save\" style=\"margin:10px\" />
				<input type=\"button\" value=\"New\" style=\"margin:10px\" onclick=\"window.location.href='mass_entry.php?new=1'\" />";
	
	return $output;
	
	}
	
	function make_blank_mass_entry_rows()
	{
		$dropdown = $this->make_plane_options("n/a", true, true);
	
		$output .= "	<table id=\"mass_entry_table\" summary=\"header row\">
					<tr>
						<td></td>
						<td>Plane</td>
						<td>Total</td>
						<td>PIC</td>
						<td>Solo</td>
						<td>SIC</td>
						<td>Dual<br />Rec.</td>
						<td>Dual<br />Given</td>
						<td>Actual</td>
						<td>Hood</td>
						<td>App.</td>		
						<td>Night</td>
						<td>XC</td>
						<td>Day<br />Land.</td>
						<td>Night<br />Land.</td>
						<td>
							
						</td>
						<td>
							
						</td>
				
						<td>
							
						</td>
					</tr>";
			
		$color == "mass_color_1";
	
		for($i=1;$i<21;$i++)
		{
			$color = $color == "mass_color_1" ? "mass_color_2" : "mass_color_1";
			
			$output .= <<<EOF
			
					
					<tr class="$color" style="font-size:xx-small">
						<td>
							<input type="hidden" name="empty_$i" value="$i" />
							
							<input autocomplete="off" type="text" name="date_$i" class="mass_names mass_grayed" value="Date (mm/dd/yyyy)"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" /><br />
							
							<input type="text" name="route_$i" class="mass_names mass_grayed" value="Route"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<select name="plane_id_$i" style="width:20em">$dropdown</select>
						</td>
						<td>
							<input autocomplete="off" type="text" name="total_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['total']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="pic_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['pic']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="solo_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['solo']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="sic_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['sic']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="dual_recieved_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['dual_recieved']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="dual_given_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['dual_given']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="act_instrument_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['act_instrument']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="sim_instrument_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['sim_instrument']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="approaches_$i" class="mass_small_numbers mass_grayed" value="{$this->abbreviated['approaches']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="night_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['night']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="xc_$i" class="mass_numbers mass_grayed" value="{$this->abbreviated['xc']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="day_landings_$i" class="mass_small_numbers mass_grayed" value="{$this->abbreviated['day_landings']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input autocomplete="off" type="text" name="night_landings_$i" class="mass_small_numbers mass_grayed" value="{$this->abbreviated['night_landings']}"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
						<td>
							<input type="text" name="student_$i" class="mass_names mass_grayed" value="Student"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" /><br />
							
							<input type="text" name="instructor_$i" class="mass_names mass_grayed" value="Instructor"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" /><br />
						</td>
						<td>
							<input type="text" name="fo_$i" class="mass_names mass_grayed" value="First Officer"
							 onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" /><br />
							 
							<input type="text" name="captain_$i" class="mass_names mass_grayed" value="Captain"
							 onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" /><br />
						</td>
						<td>
							<input type="text" name="flight_number_$i" class="mass_names mass_grayed" value="Flight Number"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" /><br />
							
							<input type="text" name="remarks_$i" class="mass_names mass_grayed" value="Remarks"
							onfocus="javascript:on_focus(this)" onblur="javascript:off_focus(this)" />
						</td>
				</tr>
				
				<tr class="$color">

					<td colspan="18" align="right">
						<table class="mass_checkmark_boxes" summary="flying event checklists">
						<tr>
						
						<td style="width:16%"><input type="checkbox" name="holding_{$i}" value="1" />Holding</td>
						<td style="width:16%"><input type="checkbox" name="tracking_{$i}" value="1" />I&amp;T</td>
						<td style="width:16%"><input type="checkbox" name="ipc_{$i}" value="1" />IPC</td>
						<td style="width:16%"><input type="checkbox" name="bfr_{$i}" value="1" />Flight Review</td>
						<td style="width:16%"><input type="checkbox" name="pilot_checkride_{$i}" value="1" />Pilot Checkride</td>
						<td style="width:16%"><input type="checkbox" name="cfi_checkride_{$i}" value="1" />CFI Checkride</td>
						
						</tr>
						</table>
					</td>
			</tr>
EOF;
		}
		
		$output .= "</table>
				<input type=\"submit\" value=\"Submit\" style=\"margin-top:10px\" />";
		
		return $output;
	}
	
	function submit_mass_entry($POST)
	{
		//var_dump($POST);
				
		if(isset($POST["entry_1"]))						//if the data is coming from a mass edit.
		{
			for($i=1;$i<$this->logbook_per_page+1;$i++)			//loop as many times as there are entries on the page
			{
				$flight_id = $POST["entry_$i"];
				
				foreach($this->text_box_fields as $field)
				{
					$$field = $POST[$field . "_" . $flight_id] == $this->abbreviated["$field"] || empty($POST[$field . "_" . $flight_id])
						? "NULL" : "\"" . $POST[$field . "_" . $flight_id] . "\"";
				}
		
				foreach($this->checkbox_fields as $field)		//if it's empty, amke it null, if its still the field title, make it null
				{
					$$field = empty($POST[$field . "_" . $flight_id]) ? '0' : $POST[$field . "_" . $flight_id];
					
					//print $field . "<br />\n";
				}
			
				$date_stamp = strtotime(str_replace("\"","",$date));
			
				//get date into the correct format, escaped, with quotation marks, and in sql-friendly Y-M-D
				$date = "'" . date("Y-m-d",$date_stamp) . "'";
			
				$star = substr($plane_id, strlen($plane_id)-2, 1);
			
				//determine if it was in a sim, and prepare the appropriate variables
				if($star == "*")
				{
					$simulator = $total;
					$total = 'NULL';
					$plane_id = "'" . substr($plane_id, 1, strlen($plane_id)-3) . "'";		//plane_id = without the star
				}
				else $simulator = 'NULL';
				
				if($POST["delete_$flight_id"] == 1)							//if the delete checkbox was checked
				{
					$sql = "DELETE FROM flights WHERE flight_id='$flight_id' AND pilot_id = {$this->pilot_id} LIMIT 1";
					mysql_query($sql);
				}
				else											//if the delete checkbox wasn't checked
				{
				
					$sql = 	"UPDATE flights SET
						`plane_id` = $plane_id,
						`date` = $date,
						`route` = $route,
						`flight_number` = $flight_number,
						`student` = $student,
						`instructor` = $instructor,
						`captain` = $captain,
						`fo` = $fo,
						`total` = $total,
						`pic` = $pic,
						`sic` = $sic,
						`dual_recieved` = $dual_recieved,
						`dual_given` = $dual_given,
						`act_instrument` = $act_instrument,
						`sim_instrument` = $sim_instrument,
						`night` = $night,
						`xc` = $xc,
						`solo` = $solo,
						`day_landings` = $day_landings,
						`night_landings` = $night_landings,
						`remarks` = $remarks,
						`approaches` = $approaches,
						`holding` = $holding,
						`tracking` = $tracking,
						`ipc` = $ipc,
						`bfr` = $bfr,
						`medical_class` = $medical_class,
						`pilot_checkride` = $pilot_checkride,
						`cfi_checkride` = $cfi_checkride,
						`cfi_refresher` = $cfi_refresher,
						`signoff` = $signoff,
			
						`simulator` = $simulator
			
						WHERE `flight_id` = $flight_id AND `pilot_id` = '{$this->pilot_id}'";
				
					if($date_stamp > 0)		//this loop goes around "logbook_per_page" times, but sometimes there arent that many entries on the page	
						mysql_query($sql);
						//print "$sql<br /><br />";
				}
			}
		}
					
		elseif(isset($POST["empty_1"]))					//if the data is coming from a mass entry
		{
			//var_dump($POST);
			
			for($i=1;$i<21;$i++)
			{
				$flight_id = $POST["empty_$i"];
				
				foreach($this->text_box_fields as $field)
				{
					$$field = $POST[$field . "_" . $flight_id] == $this->abbreviated["$field"] || empty($POST[$field . "_" . $flight_id])
						? "NULL" : "\"" . $POST[$field . "_" . $flight_id] . "\"";
				}
		
				foreach($this->checkbox_fields as $field)		//if it's empty, amke it null, if its still the field title, make it null
				{
					$$field = empty($POST[$field . "_" . $flight_id]) ? '0' : $POST[$field . "_" . $flight_id];
				}
			
				$date_stamp = strtotime(str_replace("\"","",$date));
			
				//get date into the correct format, escaped, with quotation marks, and in sql-friendly Y-M-D
				$date = "'" . date("Y-m-d",$date_stamp) . "'";
			
				$star = substr($plane_id, strlen($plane_id)-2, 1);
			
				//determine if it was in a sim, and prepare the appropriate variables
				if($star == "*")
				{
					$simulator = $total;
					$total = 'NULL';
					$plane_id = "'" . substr($plane_id, 1, strlen($plane_id)-3) . "'";		//plane_id = without the star
				}
				else $simulator = 'NULL';
						
				$sql = "INSERT INTO flights (
					`plane_id`,
					`pilot_id`,
					`date`,
					`route`,
					`flight_number`,
					`student`,
					`instructor`,
					`captain`,
					`fo`,
					`total`,
					`pic`,
					`sic`,
					`dual_recieved`,
					`dual_given`,
					`act_instrument`,
					`sim_instrument`,
					`night`,
					`xc`,
					`solo`,
					`day_landings`,
					`night_landings`,
					`remarks`,
					`approaches`,
					`simulator`,
					`holding`,
					`tracking`,
					`ipc`,
					`bfr`,
					`medical_class`,
					`pilot_checkride`,
					`cfi_checkride`,
					`cfi_refresher`,
					`signoff`)
				
					VALUES ($plane_id, '{$this->pilot_id}', $date, $route, $flight_number, $student,
						$instructor, $captain, $fo, $total, $pic, $sic, $dual_recieved, $dual_given, 
						$act_instrument, $sim_instrument, $night, $xc, $solo, $day_landings, $night_landings, $remarks, $approaches, $simulator,
						$holding, $tracking, $ipc, $bfr, $medical_class, $pilot_checkride, $cfi_checkride,
						$cfi_refresher, $signoff);";
				
				if($date_stamp > 0)	
				{	
					mysql_query($sql);
					//print "$sql<br /><br />\n";
				}
			
			}
		}
			
	}
}
