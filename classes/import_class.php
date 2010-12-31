<?php
include "logbook_class.php";

class import_ extends logbook
{

	function handle_upload($upload)
	{
		$this->new_file_path = $GLOBALS['save_location'] . date('c') . " -- " . $this->pilot_id . ".txt";
		
		move_uploaded_file($upload, $this->new_file_path);
		
		$this->file = file($this->new_file_path);
	}
	
	function get_col_numbers($header_row, $total_columns)
	{
		for($i=0;$i < $total_columns;$i++)			//determine which column is each logbook item
		{
			$str = str_replace('"','', $header_row[$i]);
			
			switch(trim(strtoupper($str)))
			{
				case "DATE":
					$cols['date'] = $i;break;
					
				case "TIME IN FLIGHT":
					$cols['total'] = $i;break;
					
				case "TOTAL":
					$cols['total'] = $i;break;
					
				case "DURATION":
					$cols['total'] = $i;break;
				
				case "DURATION OF FLIGHT":
					$cols['total'] = $i;break;
					
				case "TOTAL DURATION OF FLIGHT":
					$cols['total'] = $i;break;
					
				#############################################	
					
				case "AIRCRAFT TYPE":
					$cols['type'] = $i;break;
					
				case "AIRCRAFT MAKE & MODEL":
					$cols['type'] = $i;break;
					
				case "AIRCRAFT MAKE &MODEL":
					$cols['type'] = $i;break;
				
				case "TYPE":
					$cols['type'] = $i;break;

				#############################################	
				
				case "ROUTE OF FLIGHT":
					$cols['route'] = $i;break;
					
				case "ROUTE":
					$cols['route'] = $i;break;
					
				case "FROM":
					$cols['from'] = $i;break;
				
				case "TO":
					$cols['to'] = $i;break;
					
				case "VIA":
					$cols['via'] = $i;break;
					
				case "ROUTE OF FLIGHT FROM":
					$cols['from'] = $i;break;
					
				case "ROUTE OF FLIGHT TO":
					$cols['to'] = $i;break;
					
				case "ROUTE OF FLIGHT VIA":
					$cols['via'] = $i;break;
					
				#############################################
				
				case "AIRCRAFT IDENT":
					$cols['tail_number'] = $i;break;
					
				case "TAIL NUMBER":
					$cols['tail_number'] = $i;break;
					
				case "PLANE":
					$cols['tail_number'] = $i;break;
					
				case "REGISTRATION":
					$cols['tail_number'] = $i;break;
					
				case "AIRCRAFT REGISTRATION":
					$cols['tail_number'] = $i;break;
					
				#############################################
					
				case "DAY LANDINGS":
					$cols['day_landings'] = $i;break;
					
				case "LANDINGS DAY":
					$cols['day_landings'] = $i;break;
					
				case "NIGHT LANDINGS":
					$cols['night_landings'] = $i;break;
					
				case "LANDINGS NIGHT":
					$cols['night_landings'] = $i;break;
					
			    case "NIGHT LAND.":
					$cols['night_landings'] = $i;break;
					
				case "DAY LAND.":
					$cols['night_landings'] = $i;break;
					
				#############################################
					
				case "SIMULATOR":
					$cols['simulator'] = $i;break;
					
				case "FLIGHT SIMULATOR":
					$cols['simulator'] = $i;break;
					
				#############################################
					
				case "APPROACHES":
					$cols['approaches'] = $i;break;
					
				case "APP.":
					$cols['approaches'] = $i;break;
					
				case "APPROACHES & TYPE":
					$cols['approaches'] = $i;break;
				#############################################
					
				case "PILOT IN COMMAND":
					$cols['pic'] = $i;break;
					
				case "PIC":
					$cols['pic'] = $i;break;
				
				case "SECOND IN COMMAND":
					$cols['sic'] = $i;break;
					
				case "SIC":
					$cols['sic'] = $i;break;
					
				case "SOLO":
					$cols['solo'] = $i;break;
					
				#############################################
				
				case "DUAL":
					$cols['dual_recieved'] = $i;break;
					
				case "DUAL RECIEVED":
					$cols['dual_recieved'] = $i;break;
					
				case "DUAL RECEIVED":
					$cols['dual_recieved'] = $i;break;
					
				case "AS INSTRUCTOR":
					$cols['dual_given'] = $i;break;
					
				case "AS FLIGHT INSTRUCTOR":
					$cols['dual_given'] = $i;break;
					
				case "DUAL GIVEN":
					$cols['dual_given'] = $i;break;
					
				#############################################
					
				case "CROSS COUNTRY":
					$cols['xc'] = $i;break;
					
				case "XC":
					$cols['xc'] = $i;break;
					
				case "NIGHT":
					$cols['night'] = $i;break;
					
				case "INSTRUMENT":
					$cols['act_instrument'] = $i;break;
					
				case "ACTUAL INSTRUMENT":
					$cols['act_instrument'] = $i;break;
					
				case "ACTUAL":
					$cols['act_instrument'] = $i;break;
				
				case "HOOD":
					$cols['sim_instrument'] = $i;break;
					
				case "SIMULATED INSTRUMENT":
					$cols['sim_instrument'] = $i;break;
					
											//"official" custom columns
				case "STUDENT":
					$cols['student'] = $i;break;
			
				case "INSTRUCTOR":
					$cols['instructor'] = $i;break;
			
				case "FIRST OFFICER":
					$cols['fo'] = $i;break;
			
				case "CAPTAIN":
					$cols['captain'] = $i;break;
			
				case "FLIGHT NUMBER":
					$cols['flight_number'] = $i;break;
					
				case "REMARKS":
					$cols['remarks'] = $i;break;
					
				case "NON-FLYING":
					$cols['non_flying'] = $i;break;
					
				case "FLYING":
					$cols['flying'] = $i;break;
			}
		}
		
		return $cols;
	}
	
	function get_import_values()
	{
		$header_row = explode("\t", trim($this->file[0]));		//ann array with each row title
		$total_columns = sizeof($header_row);				//number of columns there are
	
		$cols = $this->get_col_numbers($header_row, $total_columns);
		
		###########################################################################################################
		
		//get the planes the user owns from the database
		$this->get_planes();

		//now make an array containing each plane the user already has entered into their database
		while($plane_rows = mysql_fetch_array($this->raw_planes))
		{
			$plane_id = $plane_rows['plane_id'];
			$tailnumber = $plane_rows['tail_number'];
			$type = $plane_rows['type'];

			$planes["$tailnumber"] = $plane_id;
		}
		
		###########################################################################################################
		
		for($file_line=1; $file_line<sizeof($this->file); $file_line++)
		{
			$line = trim($this->file[$file_line]);					//a tab seperated string with the all the values
			$seperated_line = explode("\t", $line);					//an array just like the header row, except with the values of each line
				
			if($seperated_line[0] == "#####RECORDS")
				$records_line = true;
			
			if($seperated_line[0] == "#####PLANES")
				$planes_line = true;
				
				
			if(!$records_line && !$planes_line)		//if records and planes havent been seen yet...
			{
				$date = $seperated_line[$cols['date']];
			
				$type = $seperated_line[$cols['type']];
				$tail_number = $seperated_line[$cols['tail_number']];
				
				##############################################################
				
				if(empty($seperated_line[$cols['route']]))							//if there is no "route"...
				{
					if(!empty($seperated_line[$cols['to']]) && empty($seperated_line[$cols['from']]))	//TO but no FROM
						$route = $seperated_line[$cols['to']] . "to";
						
					if(!empty($seperated_line[$cols['to']]) && !empty($seperated_line[$cols['via']]))	//TO, FROM and VIA
						$route = $seperated_line[$cols['to']] . "-" . $seperated_line[$cols['via']] . "-" . $seperated_line[$cols['from']];
				
				
					if(!empty($seperated_line[$cols['to']]) && empty($seperated_line[$cols['via']]))	//TO, FROM and no VIA
						$route = $seperated_line[$cols['to']] . "-" . $seperated_line[$cols['from']];
				
				
				}
				else												//there is a ROUTE
					$route = $seperated_line[$cols['route']];
					
				##############################################################
					
			//		$route = $seperated_line[$cols['to']] . "-" . $seperated_line[$cols['from']];
			//		
			//	elseif(!empty($seperated_line[$cols['via']]))							//if there is no route , but there is a via, use TO-VIA-FROM
			//		$route = $seperated_line[$cols['to']] . "-" . $seperated_line[$cols['via']] . "-" . $seperated_line[$cols['from']];
					
				//elseif(empty($seperated_line[$cols['to']]) && empty($seperated_line[$cols['from']]))
					
				
				##############################################################
			
				$student = $seperated_line[$cols['student']];
				$instructor = $seperated_line[$cols['instructor']];
				$captain = $seperated_line[$cols['captain']];
				$fo = $seperated_line[$cols['fo']];
				$flight_number = $seperated_line[$cols['flight_number']];
				$remarks = $seperated_line[$cols['remarks']];
			
				$flying = $seperated_line[$cols['flying']];
				$non_flying = $seperated_line[$cols['non_flying']];
			
				//create each variable, if it's 0.0 or (sometimes just 0), make it an empty string
			
				$total = $seperated_line[$cols['total']] ==			"0.0" ?		"" : $seperated_line[$cols['total']];
				$day_landings = $seperated_line[$cols['day_landings']] ==	"0" ?		"" : $seperated_line[$cols['day_landings']];
				$night_landings = $seperated_line[$cols['night_landings']] ==	"0" ?		"" : $seperated_line[$cols['night_landings']];
				$act_instrument = $seperated_line[$cols['act_instrument']] ==	"0.0" ?		"" : $seperated_line[$cols['act_instrument']];
				$sim_instrument = $seperated_line[$cols['sim_instrument']] ==	"0.0" ?		"" : $seperated_line[$cols['sim_instrument']];
				$approaches = $seperated_line[$cols['approaches']] ==		"0" ?		"" : $seperated_line[$cols['approaches']];
				$simulator = $seperated_line[$cols['simulator']] ==		"0.0" ?		"" : $seperated_line[$cols['simulator']];
				$night = $seperated_line[$cols['night']] ==			"0.0" ?		"" : $seperated_line[$cols['night']];
				$xc = $seperated_line[$cols['xc']] ==				"0.0" ?		"" : $seperated_line[$cols['xc']];
				$solo = $seperated_line[$cols['solo']] ==			"0.0" ?		"" : $seperated_line[$cols['solo']];
				$pic = $seperated_line[$cols['pic']] ==				"0.0" ?		"" : $seperated_line[$cols['pic']];
				$sic = $seperated_line[$cols['sic']] ==				"0.0" ?		"" : $seperated_line[$cols['sic']];
				$dual_recieved = $seperated_line[$cols['dual_recieved']] ==	"0.0" ?		"" : $seperated_line[$cols['dual_recieved']];
				$dual_given = $seperated_line[$cols['dual_given']] ==		"0.0" ?		"" : $seperated_line[$cols['dual_given']];
				$simulator = $seperated_line[$cols['simulator']] ==		"0.0" ?		"" : $seperated_line[$cols['simulator']];
			
				##########################################################################################################################################
			
				$unquoted_date = str_replace("\"", '', $date);						//remove any quotation marks
				$date_timestamp = strtotime($unquoted_date);						//convert to timestamp
			
				$route = str_replace("\"", '', $route);		//remove any quotation marks
				$route = str_replace(" to ", '-', $route);	//try to format route a little better
				$route = str_replace(":", '-', $route);
										
				$type = str_replace("\"", '', $type);
				$tail_number = str_replace("\"", '', $tail_number);

				if(substr($remarks, 0, 1) == "\"" && substr($remarks, strlen($remarks) - 1, 1) == "\"")	//remove quotation marks, only if they are around the whole remarks string
					$remarks = substr($remarks, 1, strlen($remarks) - 2);
				
				if(substr($student, 0, 1) == "\"" && substr($student, strlen($student) - 1, 1) == "\"")
					$student = substr($student, 1, strlen($student) - 2);
				
				if(substr($instructor, 0, 1) == "\"" && substr($instructor, strlen($instructor) - 1, 1) == "\"")
					$instructor = substr($instructor, 1, strlen($instructor) - 2);
				
				if(substr($fo, 0, 1) == "\"" && substr($fo, strlen($fo) - 1, 1) == "\"")
					$fo = substr($fo, 1, strlen($fo) - 2);
				
				if(substr($captain, 0, 1) == "\"" && substr($captain, strlen($captain) - 1, 1) == "\"")
					$captain = substr($captain, 1, strlen($captain) - 2);
				
				if(substr($flight_number, 0, 1) == "\"" && substr($flight_number, strlen($flight_number) - 1, 1) == "\"")
					$flight_number = substr($flight_number, 1, strlen($flight_number) - 2);
				
				##########################################################################################################################################
			
				if(strstr($flying, "H"))		//holding
					$holding = 1;
				else	$holding = 0;
			
				if(strstr($flying, "T"))		//tracking
					$tracking = 1;
				else	$tracking = 0;
			
				if(strstr($flying, "C"))		//cfi checkride
					$c_checkride = 1;
				else	$c_checkride = 0;
			
				if(strstr($flying, "P"))		//pilot checkride
					$p_checkride = 1;
				else	$p_checkride = 0;
			
				if(strstr($flying, "I"))		//ipc
					$ipc = 1;
				else	$ipc = 0;
			
				if(strstr($flying, "B"))		//bfr
					$bfr = 1;
				else	$bfr = 0;
			
				if(strstr($non_flying, "S"))		//signoff
					$signoff = 1;
				else	$signoff = 0;
			
				if(strstr($non_flying, "R"))		//cfi refresher
					$refresher = 1;
				else	$refresher = 0;
			
				if(strstr($non_flying, "1") || strstr($non_flying, "2") || strstr($non_flying, "3"))
					$medical = $non_flying;
				else	$medical = 0;
			
				##########################################################################################################################################
				
				$is_sim = 0;		//plane is not a simulator by default
			
				if(empty($total))	//if total is zero, then it must be a simulator flight, use simulator column instead and put parenthesis around it when displaying
				{
					$is_sim = 1;
					$display_total = "($simulator)";
				}
				else	$display_total = $total;
			
				##########################################################################################################################################
		
				$plane_id = $planes[$tail_number];					//get the plane ID if it's in the user's plane database
				
				if(empty($plane_id) && !empty($tail_number))
					$this->missing_planes[] = "$tail_number\t$type\t$is_sim";	//uh-oh, plane is not in the database, add to the missing planes array
				
				##########################################################################################################################################
			
				$this->fixed_file[] = array('date' => $date_timestamp, 'tail_number' => $tail_number, 'type' => $type, 'route' => $route, 'display_total' => $display_total,
							'total' => $total, 'pic' => $pic, 'solo' => $solo, 'sic' => $sic, 'dual_recieved' => $dual_recieved, 'dual_given' => $dual_given,
							'act_instrument' => $act_instrument, 'sim_instrument' => $sim_instrument, 'approaches' => $approaches, 'night' => $night,
							'xc' => $xc, 'day_landings' => $day_landings, 'night_landings' => $night_landings, 'student' => $student, 'instructor' => $instructor,
							'fo' => $fo, 'captain' => $captain, 'flight_number' => $flight_number, 'remarks' => $remarks, 'simulator' => $simulator,
							'holding'=> $holding, 'tracking'=>$tracking, 'c_checkride'=>$c_checkride, 'p_checkride'=>$p_checkride,
							'bfr'=>$bfr, 'ipc'=>$ipc, 'medical'=>$medical, 'signoff'=>$signoff, 'refresher' => $refresher);
							 
				##########################################################################################################################################		
			
				$this->page_totals['total'] += $total;
				$this->page_totals['pic'] += $pic;
				$this->page_totals['sic'] += $sic;
				$this->page_totals['solo'] += $solo;
				$this->page_totals['dual_recieved'] += $dual_recieved;
				$this->page_totals['dual_given'] += $dual_given;
				$this->page_totals['act_instrument'] += $act_instrument;
				$this->page_totals['sim_instrument'] += $sim_instrument;
				$this->page_totals['approaches'] += $approaches;
				$this->page_totals['night'] += $night;
				$this->page_totals['xc'] += $xc;
				$this->page_totals['day_landings'] += $day_landings;
				$this->page_totals['night_landings'] += $night_landings;
			}
			
			elseif(!$planes_line && $records_line)				//planes line has not yet been seen, but records line has.
				$this->records = $seperated_line[0];
			
			elseif($planes_line && $records_line && $seperated_line[0] != "#####PLANES")				//planes and records line has been seen
				$this->planes_from_file[] = array("tailnumber" => $seperated_line[0], "manufacturer" => $seperated_line[1], "model" => $seperated_line[2],
							"type" => $seperated_line[3],"category_class" => $seperated_line[4], "tail_type" => $seperated_line[5], "tags" => $seperated_line[6]);		
			
		}
		
		//print "lol";
		$_SESSION['fixed_file'] = $this->fixed_file;
		$_SESSION['planes_from_file'] = $this->planes_from_file;
		$_SESSION['records'] = $this->records;
		//print "dongz";
		
		//var_dump($_SESSION);
		
		if(!empty($this->missing_planes))
			$_SESSION['missing_planes'] = array_values(array_unique($this->missing_planes));		//remove duplicates and renumber the array
	}
	
	function make_import_logbook()
	{	
		$output .= "<div class=\"import-announce\">";

		if(empty($this->missing_planes))
			$output .= "All planes found in your LogShare logbook already appear in your logbook database. None will be added or deleted.";
			
		elseif(empty($this->planes_from_file))		//don't print the "we will add these planes" message if there were planes added from the backup file.
		{	
			$this->missing_planes = array_values(array_unique($this->missing_planes));
	
			$output .= "<span class=\"alert\">The following planes are not in your database. When you click submit, they will be automatically entered for you:<br><br>";
		
			for($j=0;$j<sizeof($this->missing_planes);$j++)
			{
				$array = explode("\t", $this->missing_planes[$j]);
				$output .= "{$array[0]} - {$array[1]}<br>";
			}
			
			$output .= "</span>";
		}
		
		$output .= "<br><br>Please look through the following lines to make sure the information is correct. Once you have varified that everything looks good,
				insert the lines into your logbook by pressing the Submit button at the bottom.";
				
		$output .= "</div>";
		
		#############################################################################################################
		
		$output .= "<div class=\"logbook_inner\">";
		$output .= "<table class=\"logbook_table\">";
		$output .= "<thead>" . $this->make_header_row() . "</thead>";
		for($i=0; $i<sizeof($this->fixed_file); $i++)
		{
	
			//for coloring of each row
			if($i % 2)
				$color = "odd";
			else    $color = "even";

			$this->page_totals["$field"] += $row["$field"];
			
			$flying= "";
		
			if($this->fixed_file[$i]['p_checkride'])
				$flying .= "[Pilot Checkride] ";
				
			if($this->fixed_file[$i]['c_checkride'])
				$flying .= "[CFI Checkride] ";
				
			if($this->fixed_file[$i]['bfr'])
				$flying .= "[Flight Review] ";
				
			if($this->fixed_file[$i]['ipc'])
				$flying .= "[IPC] ";
			
			###############################################################
			
			if($this->fixed_file[$i]['holding'] && $this->fixed_file[$i]['tracking'])
					if($this->fixed_file[$i]['approaches'] == "") $disp_approaches = "HT"; else $disp_approaches = $this->fixed_file[$i]['approaches'] . " HT";
				
				elseif($row['holding'])
					if($this->fixed_file[$i]['approaches'] == "") $disp_approaches = "H"; else $disp_approaches = $this->fixed_file[$i]['approaches'] . " H";
				
				elseif($row['tracking'])
					if($this->fixed_file[$i]['approaches'] == "") $disp_approaches = "T"; else $disp_approaches = $this->fixed_file[$i]['approaches'] . " T";
					
				else
					$disp_approaches = $this->fixed_file[$i]['approaches'];
			
			###############################################################
			
			switch($this->fixed_file[$i]['medical'])
			{
				case 1:		{$ordinal = "1st"; break;}
				case 2:		{$ordinal = "2nd"; break;}
				case 3:		{$ordinal = "3rd"; break;}
				default:	 $ordinal = "";
			}
			
			$non_flying = "";
			
			if($ordinal)
				$non_flying = "$ordinal Class Medical";
			
			elseif($this->fixed_file[$i]['signoff'])
				$non_flying = "Student Signoff";

			elseif($this->fixed_file[$i]['refresher'])
				$non_flying = "CFI Refresher";
				
			###############################################################
			
			
				
			$output .= "<tr class=\"$color\">";
			
			if($this->fixed_file[$i]['medical'] || $this->fixed_file[$i]['signoff'])				//non-flying event
				$output .= "<td title=\"Date\" >" . date("m-d-y", $this->fixed_file[$i]['date']) . "</td>
						<td class=\"non_flying_event\" colspan=21>$non_flying - {$this->fixed_file[$i]['remarks']}</td>";
			else 													//regular, flying event
				$output .= "<td title=\"Date\" >" . date("m-d-y", $this->fixed_file[$i]['date']) . "</td>	
					<td title=\"Plane\" >" . $this->fixed_file[$i]['tail_number'] . "</td>
					<td title=\"Route\" >" . $this->fixed_file[$i]['route'] . "</td>
					<td title=\"Total\" >" . $this->fixed_file[$i]['display_total'] . "</td>
					<td title=\"PIC\" >" . $this->fixed_file[$i]['pic'] . "</td>
					<td title=\"Solo\" >" . $this->fixed_file[$i]['solo'] . "</td>
					<td title=\"SIC\" >" . $this->fixed_file[$i]['sic'] . "</td>
					<td title=\"Dual Recieved\" >" . $this->fixed_file[$i]['dual_recieved'] . "</td>
					<td title=\"Dual Given\" >" . $this->fixed_file[$i]['dual_given'] . "</td>
					<td title=\"Actual Instrument\" >" . $this->fixed_file[$i]['act_instrument'] . "</td>
					<td title=\"Simulated Instrument\" >" . $this->fixed_file[$i]['sim_instrument'] . "</td>
					<td title=\"Approaches\" >$disp_approaches</td>
					<td title=\"Night\" >" . $this->fixed_file[$i]['night'] . "</td>
					<td title=\"Cross Country\" >" . $this->fixed_file[$i]['xc'] . "</td>
					<td title=\"Day Landings\" >" . $this->fixed_file[$i]['day_landings'] . "</td>
					<td title=\"Night Landings\" >" . $this->fixed_file[$i]['night_landings'] . "</td>
					<td title=\"Student\" >" . $this->fixed_file[$i]['student'] . "</td>
					<td title=\"Instructor\" >" . $this->fixed_file[$i]['instructor'] . "</td>
					<td title=\"First Officer\" >" . $this->fixed_file[$i]['fo'] . "</td>
					<td title=\"Captain\" >" . $this->fixed_file[$i]['captain'] . "</td>
					<td title=\"Flight Number\" >" . $this->fixed_file[$i]['flight_number'] . "</td>
					<td title=\"Remarks\" style=\"text-align:left\"><span class=\"flying_event\">" . $flying . "</span>" . $this->fixed_file[$i]['remarks'] . "</td>";

			$output .= "</tr>\n\n";

		}
		
		$output .= $this->make_header_row();
		$output .= $this->make_total_rows("import");
		
		$output .= "</table>";
		
		if(!empty($this->records))
			$output .= "<div class=\"import_records\" ><h1>Records</h1>" . str_replace('\r', "<br />", $this->records) . "</div>";
			
		if(!empty($this->planes_from_file))						//print the planes table if there are planes in the backup file.
		{
			$output .= "<div class=\"import_records\"><h1>Planes</h1>
					<table class=\"import_planes_table\">
						<tr>
							<td><strong>Tail Number</strong></td>
							<td><strong>Manufacturer</strong></td>
							<td><strong>Type</strong></td>
							<td><strong>Model</strong></td>
							<td><strong>Category/Class</strong></td>
							<td><strong>Tags</strong></td>
						</tr>\n";
			
			foreach($this->planes_from_file as $plane_array)
			{
				$type = $plane_array['tail_type'] == "R" || $plane_array['tail_type'] == "TR" ? " <i>(type rating)</i>" : "";
				$tail = $plane_array['tail_type'] == "T" || $plane_array['tail_type'] == "TR" ? " <i>(tailwheel)</i>" : "";
				
				$output .= "<tr>\n\t<td>" . $plane_array['tailnumber'] . "</td>\n\t<td>"
							 . $plane_array['manufacturer'] . "</td>\n\t<td>"
							 . $plane_array['type'] . "</td>\n\t<td>"
							 . $plane_array['model'] . "</td>\n\t<td>"
							 . switchout_category($plane_array['category_class']) . "</td>\n\t<td>"
							 . $plane_array['tags'] . $type . $tail . "</td>\n</tr>\n\n";
			}
			
			$output .= "</table></div>";
		}
		
		$output .= "</div>";		//close logbook_inner
		
		$output .= " <div style=\"margin-right:auto;margin-left:auto;padding-bottom:10px\">
				<form method=\"post\" action=\"submit-import.php{$this->proper_q}\">
					<input type=\"submit\" name=\"submit\" value=\"Add\">
					<input type=\"submit\" name=\"submit\" value=\"Replace\" onclick=\"return confirm('Are you sure? This will clear your logbook and replace all your flights with the flights you are importing.');\">
				</form>
			     </div>";
		
		//var_dump($this->fixed_file);		
		return $output;
	
	}
	
	function put_into_database()
	{
		var_dump($_SESSION);
	
		$this->fixed_file = $_SESSION['fixed_file'];
		$this->missing_planes = $_SESSION['missing_planes'];
		$this->planes_from_file = $_SESSION['planes_from_file'];
		$this->records =  $_SESSION['records'];
		
		$this->create_empty_planes();
		
		########################################################################################################### exact same code as above, no modifications
		
		//get the planes the user owns from the database
		$this->get_planes();

		//now make an array containing each plane the user already has entered into their database
		while($plane_rows = mysql_fetch_array($this->raw_planes))
		{
			$plane_id = $plane_rows['plane_id'];
			$tailnumber = $plane_rows['tail_number'];
			$type = $plane_rows['type'];

			$planes["$tailnumber"] = $plane_id;
		}
	
		########################################################################################################  now start making the queries
		//start transaction
		mysql_query("BEGIN");
		
		if(!empty($this->records))
		{
			$sql = "UPDATE pilots SET records = \"" . mres(str_replace('\r', "\r", $this->records)) . "\" WHERE pilot_id = {$this->pilot_id} LIMIT 1";
			mysql_query($sql);
		}
		
	
		//start at 0 because there is no header
		for($k=0; $k<sizeof($this->fixed_file); $k++)
		{

			//create a query to enter the data into the logbook table
			$sql = "INSERT INTO flights (`plane_id`,`pilot_id`,`date`,`route`,`flight_number`,`student`,`instructor`,`captain`,
				`fo`,`total`,`pic`,`sic`,`dual_recieved`,`dual_given`,`act_instrument`,`sim_instrument`,`approaches`,
				`night`,`xc`,`solo`,`day_landings`,`night_landings`,`remarks`,`simulator`, `holding`, `tracking`, `pilot_checkride`,
				`cfi_checkride`, `medical_class`, `signoff`, `cfi_refresher`, `ipc`, `bfr`)
				
				VALUES (";
				
			$sql .= empty($planes[$this->fixed_file[$k]['tail_number']]) ? 'NULL,' : $planes[$this->fixed_file[$k]['tail_number']] . ",";
			$sql .= '"' . $this->pilot_id . '",';
			$sql .= empty($this->fixed_file[$k]['date']) ? 'NULL,' : '"' . date('Y-m-d',$this->fixed_file[$k]['date']) . '",';
			$sql .= empty($this->fixed_file[$k]['route']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['route']) . '",';
			$sql .= empty($this->fixed_file[$k]['flight_number']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['flight_number']) . '",';
			$sql .= empty($this->fixed_file[$k]['student']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['student']) . '",';
			$sql .= empty($this->fixed_file[$k]['instructor']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['instructor']) . '",';
			$sql .= empty($this->fixed_file[$k]['captain']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['captain']) . '",';
			$sql .= empty($this->fixed_file[$k]['fo']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['fo']) . '",';
			$sql .= empty($this->fixed_file[$k]['total']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['total']) . '",';
			$sql .= empty($this->fixed_file[$k]['pic']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['pic']) . '",';	
			$sql .= empty($this->fixed_file[$k]['sic']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['sic']) . '",';	
			$sql .= empty($this->fixed_file[$k]['dual_recieved']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['dual_recieved']) . '",';
			$sql .= empty($this->fixed_file[$k]['dual_given']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['dual_given']) . '",';
			$sql .= empty($this->fixed_file[$k]['act_instrument']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['act_instrument']) . '",';
			$sql .= empty($this->fixed_file[$k]['sim_instrument']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['sim_instrument']) . '",';
			$sql .= empty($this->fixed_file[$k]['approaches']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['approaches']) . '",';
			$sql .= empty($this->fixed_file[$k]['night']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['night']) . '",';
			$sql .= empty($this->fixed_file[$k]['xc']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['xc']) . '",';
			$sql .= empty($this->fixed_file[$k]['solo']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['solo']) . '",';
			$sql .= empty($this->fixed_file[$k]['day_landings']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['day_landings']) . '",';
			$sql .= empty($this->fixed_file[$k]['night_landings']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['night_landings']) . '",';
			$sql .= empty($this->fixed_file[$k]['remarks']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['remarks']) . '",';
			$sql .= empty($this->fixed_file[$k]['simulator']) ? 'NULL,' : '"' . mres($this->fixed_file[$k]['simulator']) . '",';
			
			$sql .= empty($this->fixed_file[$k]['holding']) ? '0,' : '"' . mres($this->fixed_file[$k]['holding']) . '",';
			$sql .= empty($this->fixed_file[$k]['tracking']) ? '0,' : '"' . mres($this->fixed_file[$k]['tracking']) . '",';
			$sql .= empty($this->fixed_file[$k]['p_checkride']) ? '0,' : '"' . mres($this->fixed_file[$k]['p_checkride']) . '",';
			$sql .= empty($this->fixed_file[$k]['c_checkride']) ? '0,' : '"' . mres($this->fixed_file[$k]['c_checkride']) . '",';
			$sql .= empty($this->fixed_file[$k]['medical']) ? '0,' : '"' . mres($this->fixed_file[$k]['medical']) . '",';
			$sql .= empty($this->fixed_file[$k]['signoff']) ? '0,' : '"' . mres($this->fixed_file[$k]['signoff']) . '",';
			$sql .= empty($this->fixed_file[$k]['refresher']) ? '0,' : '"' . mres($this->fixed_file[$k]['refresher']) . '",';
			$sql .= empty($this->fixed_file[$k]['ipc']) ? '0,' : '"' . mres($this->fixed_file[$k]['ipc']) . '",';
			$sql .= empty($this->fixed_file[$k]['bfr']) ? '0)' : '"' . mres($this->fixed_file[$k]['bfr']) . '")';
				
			//print $sql . "<br>\n";
				
			if(!(mysql_query($sql)))
			{
				mysql_query("ROLLBACK");
				return $k + 1;					//returns the line number where an error was found.
			}
		}
		
		mysql_query("COMMIT");			//if it has made it this far, no errors have occured.
		
		return false;				//meaning no errors

	}
	
	function create_empty_planes()
	{
		if(!empty($this->planes_from_file))
		{
			foreach($this->planes_from_file as $plane_array)
			{
				$type = $plane_array['tail_type'] == "R" || $plane_array['tail_type'] == "TR" ? "1" : "0";
				$tail = $plane_array['tail_type'] == "T" || $plane_array['tail_type'] == "TR" ? "1" : "0";
				
				$sql = sprintf("INSERT INTO planes (`pilot_id`, `tail_number`, `type`, `manufacturer`, `model`, `category_class`, `tailwheel`, `type_rating`)
					VALUES('%s','%s','%s','%s','%s','%s','%s','%s')",
					$this->pilot_id,
					mres($plane_array['tailnumber']),
					mres($plane_array['type']),
					mres($plane_array['manufacturer']),
					mres($plane_array['model']),
					mres($plane_array['category_class']),
					$tail,
					$type
					);
				
				//print $sql . "<br>\n";
					
				mysql_query($sql);
				
				if(!empty($plane_array['tags']))
				{
					$tags = $plane_array['tags'];
					$plane_id = mysql_insert_id();		//get the plane_id of the newly created plane
					
					$this->make_tags($tags, $plane_id);
				}

			}
		
			return;
		
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////
	
		for($i=0;$i<sizeof($this->missing_planes);$i++)
		{
			$plane_string = $this->missing_planes[$i];

			//split the string into an array with two items, one the tailnumber, the other is the type
			$array = explode("\t", $plane_string);
		
			$tail_number = $array[0];
			$type = $array[1];
			$is_sim = $array[2];
			
			#######################
		
			$first_one = strtoupper(substr($type, 0, 1));
			$first_two = strtoupper(substr($type, 0, 2));
			$first_three = str_replace("-", "", strtoupper(substr($type, 0, 3)));
		
			$upper_type = strtoupper($type);
			
			######################
		
			$manufacturer = 'NULL';
			$model = 'NULL';
			$category_class = 1;
			$type_rating = 0;
			$tailwheel = 0;
			$tags = "";
			
			####################
		
			if($first_two == "PA")
			{	
				$manufacturer = "Piper";
			
				if(substr($upper_type,2,2) == "14")
				{
					$model = "Family Cruiser";
					$tailwheel = 1;
				}
			
				elseif(substr($upper_type,2,2) == "15" || substr($upper_type,2,2) == "17")
				{
					$model = "Vagabond";
					$tailwheel = 1;
				}
			
				elseif(substr($upper_type,2,2) == "16")
				{
					$model = "Clipper";
					$tailwheel = 1;
				}
			
				elseif(substr($upper_type,2,2) == "18")
				{
					$model = "Super Cub";
					$tailwheel = 1;
				}
			
				elseif(substr($upper_type,2,2) == "20")
				{
					$model = "Pacer";
					$tailwheel = 1;
				}
			
				elseif(substr($upper_type,2,2) == "22")
				{
					$model = "Tri-Pacer";
					$railwheel = 1;
				}
			
				elseif(substr($upper_type,2,2) == "23")
				{
					$model = "Aztech";
					$category_class = 2;
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "24")
				{
					$model = "Comanche";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "25")
				{
					$model = "Pawnee";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "28")
				{
					$model = "Cherokee";
				}
			
				elseif(substr($upper_type,2,2) == "30")
				{
					$model = "Twin Comanche";
					$category_class = 2;
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "31")
				{
					$model = "Navajo";
					$category_class = 2;
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "32")
				{
					$model = "Cherokee Six";
				}
			
				elseif(substr($upper_type,2,3) == "32R")
				{
					$model = "Lance";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "34")
				{
					$model = "Seneca";
					$category_class = 2;
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
					$engine = 2;
				}
			
				elseif(substr($upper_type,2,2) == "36")
				{
					$model = "Pawnee Brave";
				}
			
				elseif(substr($upper_type,2,2) == "38")
				{
					$model = "Tomahawk";
				}
			
				elseif(substr($upper_type,2,2) == "39")
				{
					$model = "Twin Comanche";
					$category_class = 2;
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "42")
				{
					$model = "Cheyenne";
					$category_class = 2;
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
					$engine = 3;
				}
			
				elseif(substr($upper_type,2,2) == "44")
				{
					$model = "Seminole";
					$category_class = 2;
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,2,2) == "46")
				{
					$model = "Malibu";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			}
		
			if($first_two == "C1" || $first_two == "C2" || $first_two == "C3" || $first_two == "C4" || $first_two == "CE")
			{
				$manufacturer = "Cessna";
				$type_rating = 0;
				$tailwheel = 0;
				
			
				if(substr($upper_type,0,4) == "C140" || substr($upper_type,0,4) == "C120" || substr($upper_type,0,4) == "C170"
						|| substr($upper_type,0,4) == "C180"|| substr($upper_type,0,4) == "C190" || substr($upper_type,0,4) == "C185")
				{
					$tailwheel = 1;
				}
			
				elseif(substr($upper_type,0,6) == "C172RG")
				{
					$model = "Retractable Skyhawk";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C162")
				{
					$model = "SkyCatcher";
				}
			
				elseif(substr($upper_type,0,4) == "C172")
				{
					$model = "Skyhawk";
				}
			
				elseif(substr($upper_type,0,4) == "C182")
				{
					$model = "Skylane";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C210")
				{
					$model = "Centurion";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C206")
				{
					$model = "Stationair";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C208")
				{
					$model = "Caravan";
					$tags .= "High Performance, ";
					$engine = 3;
				}
			
				elseif(substr($upper_type,0,4) == "C310")
				{
					$model = "Sky Knight";
					$category_class = 2;
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C337")
				{
					$model = "Super Skymaster";
					$class = "2";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C336")
				{
					$model = "Skymaster";
					$class = "2";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C177")
				{
					$model = "Cardinal";
				}
			
				elseif(substr($upper_type,0,6) == "C177RG")
				{
					$model = "Retractable Cardinal";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C421")
				{
					$model = "Golden Eagle";
					$class = "2";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) =="C425")
				{
					$model = "Conquest I";
					$class = "2";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif(substr($upper_type,0,4) == "C441")
				{
					$model = "Conquest II";
					$class = "2";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
			}
		
			if($first_two == "BE")
			{	
				$manufacturer = "Beechcraft";
				$type_rating = 0;
				$tailwheel = 0;
				
			
				if($upper_type == "BE76")
				{
					$model = "Duchess";
					$category_class = 2;
					$tags .= "Complex, ";
				}
			
				elseif($upper_type == "BE55" || $upper_type == "BE58")
				{
					$model = "Baron";
					$category_class = 2;
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
					
				elseif($upper_type == "BE60")
				{
					$model = "Duke";
					$category_class = 2;
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif($upper_type == "BE95")
				{
					$model = "TravelAir";
					$category_class = 2;
					$tags .= "Complex, ";
				}
			
				elseif($upper_type == "BE17")
				{
					$model = "StaggerWing";
					$tailwheel = 1;
				}
			
				elseif($upper_type == "BE18")
				{
					$model = "Twin Beech";
					$category_class = 2;
				}
			
				elseif($upper_type == "BE23")
				{
					$model = "Musketeer";
				}
			
				elseif($upper_type == "BE77")
				{
					$model = "Skipper";
				}
			
				elseif($upper_type == "BE35" || $upper_type == "BE36")
				{
					$model = "Bonanza";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif($upper_type == "BE33")
				{
					$model = "Debonair";
					$tags .= "High Performance, ";
					$tags .= "Complex, ";
				}
			
				elseif($upper_type == "BE99")
				{
					$model = "Airliner";
					$category_class = 2;
					$tags .= "Complex, ";
					$tags .= "High Performance, ";
					$engine = 3;
				}
			
				elseif($upper_type == "BE95")
				{
					$model = "TravelAir";
					$category_class = 2;
					$tags .= "Complex, ";
				}
			
				elseif($upper_type == "BE90")
				{
					$model = "King Air";
					$category_class = 2;
					$tags .= "Complex, ";
					$tags .= "High Performance, ";
					$engine = 3;
				}
		
			}
		
			if($type == "CH2T" || $type == "CH2000")
			{
				$manufacturer = "Zenair";
				$model = "Alarus";
				$complex = 0;
				$type_rating = 0;
				$tailwheel = 0;
						
			}
		
			if($type == "7ECA")
			{
				$manufacturer = "Bellanca";
				$model = "Citabria";
				$tailwheel = 1;
						
			}
		
			if($upper_type == "F33A")
			{
				$manufacturer = "Beechcraft";
				$model = "Bonanza";
				$tags .= "Complex, ";
				$tags .= "High Performance, ";
			}
			
			####################
			
			if($is_sim == 1)
				$category_class = 16;
		
			$tags = trim($tags);
		
			$tags = substr($tags, 0, strlen($tags)-1);
		
			if($manufacturer != 'NULL')
				$manufacturer = "'$manufacturer'";
			
			if($model != 'NULL')
				$model = "'$model'";
		
			#####################

			$sql = sprintf("INSERT INTO planes (`pilot_id`, `tail_number`, `type`, `manufacturer`, `model`, `category_class`)
					VALUES('%s','%s','%s',%s,%s,'%s')",
					$this->pilot_id,
					mres($tail_number),
					mres($type),
					$manufacturer,
					$model,
					$category_class
					);

			mysql_query($sql);
			
			//$result = mysql_query("SELECT plane_id FROM planes WHERE `tail_number` = '" . mres($tail_number) . "' AND pilot_id = {$this->pilot_id}");
			//$plane_id = mysql_fetch_array($result);
			//$plane_id = $plane_id[0];
			
			$plane_id = mysql_insert_id();
			
			$tags_array = explode(",", $tags);
			
			//print "$tags<br>";

			if(!empty($tags))			
				foreach($tags_array as $tag)
				{
					mysql_query($sql = "INSERT INTO tags (`plane_id`, `tag`) VALUES ('$plane_id','" . mres($tag) . "')");
				}
			$tags = "";
		}
	}
}
