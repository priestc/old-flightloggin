<?php
include "currency_class.php";

class logbook extends currency
{
	var $raw_planes;
	
	var $text_box_fields = array('plane_id', 'date', 'route', 'flight_number', 'student', 'instructor', 'captain', 'fo', 'approaches', 'total', 'pic','sic',
			'dual_recieved', 'dual_given', 'act_instrument', 'sim_instrument', 'night', 'xc', 'solo', 'day_landings', 'night_landings', 'remarks');
	///////
			
	var $non_numeric_box_fields = array('date', 'route', 'flight_number', 'student', 'instructor', 'captain', 'fo', 'remarks');
	
	var $numeric_box_fields = array('plane_id', 'approaches', 'total', 'pic','sic', 'dual_recieved', 'dual_given', 'act_instrument', 'sim_instrument', 'night', 'xc',
			'solo', 'day_landings', 'night_landings');
			
	///////
					
	var $checkbox_fields = array('pilot_checkride','bfr','ipc','non_flying','cfi_checkride','tracking', 'holding', 'medical_class', 'signoff', 'cfi_refresher');
	
	
			
	var $current_page;
	var $total_number_of_pages;
	var $total_rows;
		
	var $page_totals;		//created when make_logbook() is called. associated array index with the field name

	function get_logbook_info()
	{
		$result_count = mysql_query("SELECT count(*) FROM flights WHERE pilot_id = '{$this->pilot_id}'");
		$total_rows = mysql_fetch_array($result_count);
		
		$this->total_rows = $total_rows[0];
		
		##############
		
		$this->total_number_of_pages = ceil( ($this->total_rows / $this->logbook_per_page) );
		
		if($this->total_number_of_pages < 1)					//insure there is always at least one page
			$this->total_number_of_pages = 1;
		
		##############
		
		$this->current_page = $_GET['p'] or $this->current_page = $this->total_number_of_pages;
		
		if($this->current_page > $this->total_number_of_pages)
			$this->current_page = $this->total_number_of_pages;		//don't let current page be bigger than total number of pages
		
		##############
	}
	
	function recent_items($target)
	{
		//$target is a string that is either "instructor", "student", "fo", or "captain"

		$sql = sprintf("SELECT $target, max( date ) AS last_date
				FROM flights
				WHERE pilot_id = '%s' AND $target != \"\"
				GROUP BY $target
				ORDER BY last_date DESC
				LIMIT 12" ,
				mysql_real_escape_string($this->pilot_id)
				);

		$result = mysql_query($sql);

		$i = 0;

		$output = "<table class=\"saved_items_table\" summary=\"saved items\">";

		while($item = mysql_fetch_array($result))
		{

		$i++;

			if($i % 2 == 1)
			{//first column
				$output .= "\n\t\t\t<tr>\n
				 <td width=50% align=\"center\">
				 	<span class=\"fake_anchor\" onclick=\"javascript:new_entry_form.$target.value = this.innerHTML;\">" . $item[$target] . "</span>
				 </td>\n";
			}
			
			if($i % 2 == 0)
			{//second column
				$output .= "<td width=50% align=\"center\">
					<span class=\"fake_anchor\" onclick=\"javascript:new_entry_form.$target.value = this.innerHTML;\">" . $item[$target] . "</span>
				</td>\n</tr>";
			}

		}
	
		//print $sql;
	
		$output .= "\t\t\t\t</tr>\n\t\t\t\t</table>";
		
		return $output;
	}
	
	function make_dropdown($dropdown_type)
	{
		$this->dropdown_type = 1;
	
		if($dropdown_type == "new_entry")
		{
			if($this->dropdown_type == 1)
				$dropdown .= "<select id=\"plane_dropdown\" onchange=\"switch_sim();\" class=\"plane_dropdown\" name=\"plane_id\">" . $this->make_plane_options() . "</select>";
				
			if($this->dropdown_type == 2)
				$dropdown .= "	<select id=\"type_dropdown\" class=\"plane_dropdown\">" . $this->make_type_options() . "</select> <br>" .
				
						"<select id=\"plane_dropdown\" class=\"plane_dropdown\" style=\"margin-top:4px\">" . $this->make_plane_options("n/a", false, true) . "</select>";
		}
		
		if($dropdown_type == "mass_edit")
		{
			$dropdown .= "<select name=\"plane_id_{$flight_id}\" style=\"width:20em\">$dropdown</select>";
		}
		
		return $dropdown;
	}
	
	function make_plane_options($selected_id = "n/a", $empty = false, $just_tails = false)
	{	
		if(!isset($this->raw_planes))
			$this->get_planes();
			
		$plane_rows = mysql_fetch_array($this->raw_planes);
	
		if(empty($plane_rows))
		{
			$dropdown .= "<option value=\"0\">No Planes found in database</option>";
		}
		else
		{				
			if($empty)
				$dropdown .= "<option value=\"0\" selected=\"selected\">-----</option>\n";
				
			$dropdown .= "\n";
	
			do
			{
				//plane_rows[] is an array with all the items for each plane the user owns

				$plane_id = $plane_rows['plane_id'];
				$tailnumber = $plane_rows['tail_number'];
				$type = $plane_rows['type'];
				$category = $plane_rows['category_class'];
				$tags = $plane_rows['tags'];
	
				//tag it if it's a simulator or PCATD
				if($category == 15 || $category == 16)
					$sim_mark = '*';
				else $sim_mark = '';
			
				$sel = $selected_id == $plane_id ? "selected=\"selected\"" : "";
			
				if($just_tails)
					$dropdown .= "<option value=\"$plane_id{$sim_mark}\" $sel>$tailnumber</option>\n";
				else				
					$dropdown .= "<option value=\"$plane_id{$sim_mark}\" $sel>$tailnumber &#8226; $type</option>\n";

			}while($plane_rows = mysql_fetch_array($this->raw_planes));

			mysql_data_seek($this->raw_planes, 0);
		}
		
		return $dropdown;
	}
	
	function make_type_options()
	{
		if(!isset($this->raw_types))
			$this->get_types();
			
		$type_rows = mysql_fetch_array($this->raw_types);
	
		if(empty($type_rows))
		{
			$dropdown .= "<option value=\"0\">No Planes found in database</option>";
		}
		else
		{				
			if($empty)
				$dropdown .= "<option value=\"0\" selected=\"selected\">-----</option>\n";
				
			$dropdown .= "\n";
	
			do
			{
				$type = $plane_rows['type'];
				$sel = $selected_id == $plane_id ? " selected=\"selected\"" : "";

				$dropdown .= "<option value=\"$type\"$sel>$type</option>\n";

			}while($plane_rows = mysql_fetch_array($this->raw_types));

			mysql_data_seek($this->raw_types, 0);
		}
		
		return $dropdown;
	}
	
	function make_pagination($page, $special_values="")
	{
		$checked = $special_values["flights_only"] ? "checked='checked'" : "";
		$end_sel = $special_values["begining_end"] == "end" ? "selected='selected'" : "";
		$begin_sel = $special_values["begining_end"] == "begining" ? "selected='selected'" : "";
		
		$spec_vals = !empty($special_values["begin_date"]) || !empty($special_values["end_date"]) || !empty($special_values["how_many"]) || $special_values["flights_only"] == "on";
		
		if($spec_vals) {
			$spec_disp = "block";
			$link_disp = "none";}
		else{
			$spec_disp = "none";
			$link_disp = "block";}

	    if(!empty($special_values))
		$pagination = '<div style="display:' . $spec_disp . '; width:100%; padding: 5px" id="custom_box">
					<form method="post" action="logbook.php?p=' . $this->current_page . $this->proper_a . '">
					<table style="width: 100%">
						<tr>
							<td><input type="checkbox" name="flights_only" ' . $checked . ' />Display only flights</td>
							<td><input type="text" name="how_many" style="width:30px" value="' . $special_values["how_many"] . '"/> entries
								<select name="begining_end">
									<option value="end" ' . $end_sel . '>From the end</option>
									<option value="begining" ' . $begin_sel . '>From the begining</option>
								</select></td>
							<td>Between dates: <input type="text" name="begin_date" style="width:100px" value="' . $special_values["begin_date"] . '" /> and
								<input type="text" name="end_date" style="width:100px" value="' . $special_values["end_date"] . '" /> (yyyy-mm-dd)</td>
							<td><input type="submit" value="Create Custom View" /></td>
						</tr>
					</table>
						
					</form>
				</div><span style="display: ' . $link_disp . '" class="fake_anchor" onclick="document.getElementById(\'custom_box\').style.display=\'block\';this.style.display = \'none\'">Custom view</span>';

		
		//print $page_link;
	
		if($this->total_number_of_pages < 2)
			return;
		
		$pagination .= "<div style=\"background-color:transparent\">
					<table style=\"width:100%;\" summary=\"pagination\">
						<tr>
							<td style=\"width:20%;text-align:left;border:0;\">
								<span class=\"first_page_index\"><a href=\"$page.php?p=1{$this->proper_a}\">First Page</a></span>
							</td>
							<td style=\"width:60%;border:0;\"><div class=\"center_page_index\">";
		
		for($i=$this->current_page-5<1?1:$this->current_page-5;$i<$this->current_page;$i++)
			$pagination .= "<a href=\"$page.php?p=$i{$this->proper_a}\">$i</a> ";
		
		if($spec_vals)
			$pagination .= "<a href=\"$page.php?p={$this->current_page}{$this->proper_a}\">{$this->current_page}</a> ";
		else	
			$pagination .= "{$this->current_page} ";
		
		for($i=$this->current_page+1;$i<($this->current_page+6>$this->total_number_of_pages?$this->total_number_of_pages+1:$this->current_page+6);$i++)
			$pagination .= "<a href=\"$page.php?p=$i{$this->proper_a}\">$i</a> ";

		$pagination .= "</div></td>";
	
	
		$pagination .= "<td style=\"width:20%;text-align:right;border:0;\">
				<span class=\"last_page_index\"><a href=\"$page.php?p={$this->total_number_of_pages}{$this->proper_a}\">Last Page</a></span></td>";
			
		$pagination .= "</tr></table></div>";
		
		return $pagination;
	
	}

	function make_total_rows($type = "logbook")
	{
		if($type == "import")
		{	$page_label = "Import Totals:";
			$overall_label= "Totals before import:";
		}
		else
		{	$page_label = "Page Totals:";
			$overall_label= "Overall Totals:";
			$show_all = "   <span style=\"display:inline\" class=\"fake_anchor\" id=\"unhide_columns_text_link\" onclick=\"toggle_hide_columns();show_all_columns()\">Show All Columns</span>
					<span style=\"display:none\" class=\"fake_anchor\" id=\"rehide_columns_text_link\" onclick=\"toggle_hide_columns();hide_the_columns('{$this->mode}')\">Hide Columns</span>";
			$mass_edit = "<a href=\"mass_entry.php?p={$this->current_page}\">Mass Edit</a>";
		}
		
		if($this->auth == "share")				//hide the mass edit link for guests
			$mass_edit = "";
	

		$sql = sprintf(
			"SELECT SUM(total), SUM(pic), SUM(solo), SUM(sic), SUM(dual_recieved), SUM(dual_given), SUM(act_instrument),
				SUM(sim_instrument), SUM(night), SUM(xc), SUM(day_landings), SUM(night_landings), SUM(approaches)
			FROM flights
			WHERE pilot_id='%s' LIMIT 1",
			mysql_real_escape_string($this->pilot_id)
			);

		$result = mysql_query($sql);

		$row = mysql_fetch_row($result);

		//replace blank strings with a space character so the table cell stil renders
		$this->total_total = $overall['total'] = $row[0] == "" ? "0.0" : $row[0];
		$this->total_pic = $overall['pic'] = $row[1] == "" ? "0.0" : $row[1];
		$overall['solo'] = $row[2] == "" ? "0.0" : $row[2];
		$overall['sic'] = $row[3] == "" ? "0.0" : $row[3];
		$overall['dual_recieved'] = $row[4] == "" ? "0.0" : $row[4];
		$overall['dual_given'] = $row[5] == "" ? "0.0" : $row[5];
		$this->total_actual = $overall['act_instrument'] = $row[6] == "" ? "0.0" : $row[6];
		$overall['sim_instrument'] = $row[7] == "" ? "0.0" : $row[7];
		$this->total_night = $overall['night'] = $row[8] == "" ? "0.0" : $row[8];
		$this->total_xc = $overall['xc'] = $row[9] == "" ? "0.0" : $row[9];
		$overall['day_landings'] = $row[10] == "" ? "0" : $row[10];
		$this->total_night_landings = $overall['night_landings'] = $row[11] == "" ? "0" : $row[11];
		$overall['approaches'] = $row[12] == "" ? "0" : $row[12];
		
		if($type == "import")
		$after_import_row = "
			<tr class=\"page_row\">
				<td colspan=\"3\" style=\"text-align:right;background-color:transparent;border:none\">Totals after import:</td>
				<td >" . number_format($this->page_totals['total'] + $overall['total'],1) . "</td>
				<td class=\"pic_col\">" . number_format($this->page_totals['pic'] + $overall['pic'],1) . "</td>
				<td class=\"solo_col\">" . number_format($this->page_totals['solo'] + $overall['solo'],1) . "</td>
				<td class=\"sic_col\">" . number_format($this->page_totals['sic'] + $overall['sic'],1) . "</td>
				<td class=\"dual_recieved_col\">" . number_format($this->page_totals['dual_recieved'] + $overall['dual_recieved'],1) . "</td>
				<td class=\"dual_given_col\">" . number_format($this->page_totals['dual_given'] + $overall['dual_given'],1) . "</td>
				<td class=\"act_col\">" . number_format($this->page_totals['act_instrument'] + $overall['act_instrument'],1) . "</td>
				<td class=\"sim_col\">" . number_format($this->page_totals['sim_instrument'] + $overall['sim_instrument'],1) . "</td>
				<td class=\"approaches_col\">" . number_format($this->page_totals['approaches'] + $overall['approaches'],0) . "</td>
				<td class=\"night_col\">" . number_format($this->page_totals['night'] + $overall['night'],1) . "</td>
				<td class=\"xc_col\">" . number_format($this->page_totals['xc'] + $overall['xc'],1) . "</td>
				<td class=\"day_land_col\">" . number_format($this->page_totals['day_landings'] + $overall['day_landings'],0) . "</td>
				<td class=\"night_land_col\">" . number_format($this->page_totals['night_landings'] + $overall['night_landings'],0) . "</td>
				<td colspan=\"6\" style=\"background-color:transparent;border:0px\">&nbsp</td>
			</tr>\n\n";
		else
			$after_import_row = "";
		
		
		return "
			<tr class=\"page_row\">
				<td colspan=\"3\" style=\"text-align:right;background-color:transparent;border:none\">$page_label</td>
				<td >" . number_format($this->page_totals['total'],1) . "</td>
				<td class=\"pic_col\">" . number_format($this->page_totals['pic'],1) . "</td>
				<td class=\"solo_col\">" . number_format($this->page_totals['solo'],1) . "</td>
				<td class=\"sic_col\">" . number_format($this->page_totals['sic'],1) . "</td>
				<td class=\"dual_recieved_col\">" . number_format($this->page_totals['dual_recieved'],1) . "</td>
				<td class=\"dual_given_col\">" . number_format($this->page_totals['dual_given'],1) . "</td>
				<td class=\"act_col\">" . number_format($this->page_totals['act_instrument'],1) . "</td>
				<td class=\"sim_col\">" . number_format($this->page_totals['sim_instrument'],1) . "</td>
				<td class=\"approaches_col\">" . number_format($this->page_totals['approaches'],0) . "</td>
				<td class=\"night_col\">" . number_format($this->page_totals['night'],1) . "</td>
				<td class=\"xc_col\">" . number_format($this->page_totals['xc'],1) . "</td>
				<td class=\"day_land_col\">" . number_format($this->page_totals['day_landings'],0) . "</td>
				<td class=\"night_land_col\">" . number_format($this->page_totals['night_landings'],0) . "</td>
				<td colspan=\"6\" style=\"background-color:transparent;border:0px;text-align:right;\"></td>
			</tr>\n\n

			<tr class=\"overall_row\">
				<td colspan=\"3\" style=\"text-align:right;background-color:transparent;border-width:0px\">$overall_label</td>
				<td class=\"total_col\">" . number_format($overall['total'],1) . "</td>
				<td class=\"pic_col\">" . number_format($overall['pic'],1) . "</td>
				<td class=\"solo_col\">" . number_format($overall['solo'],1) . "</td>
				<td class=\"sic_col\">" . number_format($overall['sic'],1) . "</td>
				<td class=\"dual_recieved_col\">" . number_format($overall['dual_recieved'],1) . "</td>
				<td class=\"dual_given_col\">" . number_format($overall['dual_given'],1) . "</td>
				<td class=\"act_col\">" . number_format($overall['act_instrument'],1) . "</td>
				<td class=\"sim_col\">" . number_format($overall['sim_instrument'],1) . "</td>
				<td class=\"approaches_col\">" . number_format($overall['approaches'],0) . "</td>
				<td class=\"night_col\">" . number_format($overall['night'],1) . "</td>
				<td class=\"xc_col\">" . number_format($overall['xc'],1) . "</td>
				<td class=\"day_land_col\">" . number_format($overall['day_landings'],0) . "</td>
				<td class=\"night_land_col\">" . number_format($overall['night_landings'],0) . "</td>
				<td class=\"remarks_col\" colspan=\"6\" style=\"text-align:right;background-color:transparent;border:0px\">$mass_edit &nbsp; &nbsp; $show_all</td>
			</tr>
			
			$after_import_row
			
			\n\n";
			
	}
	
	function make_header_row()
	{
		return "<tr class=\"header_row\">
				<td class=\"date_col\">Date</td>
				<td class=\"plane_col\">Plane</td>
				<td class=\"route_col\">Route</td>
				<td class=\"total_col\">Total</td>
				<td class=\"pic_col\">PIC</td>
				<td class=\"solo_col\">Solo</td>
				<td class=\"sic_col\">SIC</td>
				<td class=\"dual_recieved_col\">Dual Rec.</td>
				<td class=\"dual_given_col\">Dual Given</td>
				<td class=\"act_col\">Actual</td>
				<td class=\"sim_col\">Hood</td>
				<td class=\"approaches_col\">App.</td>		
				<td class=\"night_col\">Night</td>
				<td class=\"xc_col\">XC</td>
				<td class=\"day_land_col\">Day Land.</td>
				<td class=\"night_land_col\">Night Land.</td>
				<td class=\"student_col\">Student</td>
				<td class=\"instructor_col\">Instructor</td>
				<td class=\"fo_col\">First Officer</td>
				<td class=\"captain_col\">Captain</td>
				<td class=\"flight_number_col\">Flt Num</td>
				<td class=\"remarks_col\">Remarks</td>
			</tr>";

	}
	
	function submit_logbook($POST, $flight_id = false)
	{
	
		foreach($this->non_numeric_box_fields as $field)			//escapes all fields, and adds quotation marks
			$$field = empty($POST["$field"]) ? "NULL" : "\"" . $POST["$field"] . "\"";
		
		foreach($this->numeric_box_fields as $field)				//escapes all fields, and adds quotation marks
		{
			$$field = empty($POST["$field"]) ? "NULL" : "\"" . "{$POST["$field"]}" . "\"";
		}
		
		foreach($this->checkbox_fields as $field)				//escapes all fields, and adds quotation marks
			$$field = empty($POST["$field"]) ? 0 : $POST["$field"] ;
		
		$medical_class = 0;
		$signoff = 0;
		$cfi_refresher = 0;

		switch($non_flying)
		{
			case "0":	break;
			case "1":	{$medical_class = 1;	break;}
			case "2":	{$medical_class = 2;	break;}
			case "3":	{$medical_class = 3;	break;}
			case "5":	{$signoff = 1;		break;}
			case "4":	{$cfi_refresher = 1;	break;}
			default:	$signoff = 0;
		}
	
		//get date into the correct format, escaped, with quotation marks, and in sql-friendly Y-M-D
		$date = "'" . date("Y-m-d",strtotime(str_replace("\"","",$date))) . "'";
		
		$star = substr($plane_id, strlen($plane_id)-2, 1);
	
		//determine if it was in a sim, and prepare the appropriate variables
		if($star == "*")
		{
			$simulator = $total;
			$total = 'NULL';
			$plane_id = "'" . substr($plane_id, 1, strlen($plane_id)-3) . "'";		//plane_id = without the star
		}
		else $simulator = 'NULL';
		
		$route = str_replace("'", "", $route);
		
		if($flight_id)
		
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
				
				WHERE `flight_id` = $flight_id AND `pilot_id` = '{$this->pilot_id}'
			
			";
		
		else if($this->pilot_id >= 1)
	
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
				
		mysql_query($sql);
		//print $sql;
	}
	
	
	
	function make_logbook_rows($special_options = array())
	{
		$start = ($this->current_page-1) * $this->logbook_per_page;
		$how_many = $this->logbook_per_page;
		
		//$special_options = array("start_date" => "2008-07-01", "flights_only" => true);
		
		if($special_options)
		{
			if($special_options['flights_only'])
				$flights_only = "AND flights.medical_class = 0 AND flights.cfi_refresher = 0 AND flights.signoff = 0";
			#############################
			
			if(!empty($special_options['begin_date']) || !empty($special_options['end_date'])){
			
				if(empty($special_options['begin_date']))
					$special_options['begin_date'] = "1900-01-01";
					
				if(empty($special_options['end_date']))
					$special_options['end_date'] = date("Y-n-j");
			
				$start_date = "AND UNIX_TIMESTAMP(date) >= "
							. strtotime($special_options['begin_date'])
							. " AND UNIX_TIMESTAMP(date) <= "
							. strtotime($special_options['end_date']);
				$start = 0;
				$how_many = 99999999;}
				
			if($special_options['begining_end'] == "begining" && $special_options['how_many'] > 0) {
				$how_many = $special_options['how_many'];
				$start = 0;}
				
			if($special_options['begining_end'] == "end" && $special_options['how_many'] > 0){
				$how_many = $special_options['how_many'];
				$start = 0;
				$sort = "DESC";
				$wrapper_bottom = ") as dd ORDER BY date ASC";
				$wrapper_top = "SELECT * FROM (";}
			
		}
		
		//print $special_options['begin_date'] . "--" . $special_options['end_date'] . "/n";
						
		$sql = sprintf(
				"$wrapper_top
				SELECT flights.*, planes.tail_number
				FROM flights LEFT JOIN planes
				ON planes.plane_id = flights.plane_id
				WHERE flights.pilot_id='%s'
				$flights_only
				$start_date
				ORDER BY date $sort, flight_id $sort
				LIMIT $start, $how_many
				$wrapper_bottom",
				$this->pilot_id
				);
				
		//print $sql;
				
				
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
			
			if($total == '' && $simulator != '')
			{
				$display_total = "($simulator)";
				$is_sim = "*";
			}
			else
			{
				$display_total = $total;
				$is_sim = "";
			}

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
				
				if($this->auth != "share")
					$items .=  "<tr class=\"$color\">
							<td class=\"date_col\" title=\"Date (Click to Edit)\"><span class=\"fake_anchor\"
							onclick=\"javascript:prepare_and_fire_entry_form(
						
									'edit_non_flight',
									'$flight_id',
									'" . date('m/d/Y', strtotime($sql_date)) ."',
									'" . mres($remarks) . "',
									'',
									'$non_flying_number'
									);\">$date</span></td>
											
							<td class=\"non_flying_event\" colspan=\"21\">
								{$non_flying}{$display_remarks}
							</td>
					 	    </tr>";
				else
					$items .=  "<tr class=\"$color\">
							<td class=\"date_col\">$date</td>
											
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
				
				$js_total = empty($total) ? $simulator : $total;
				
				#####################################################
				
				if($this->auth != "share")
					$proper_date_column = "<td title=\"Date (Click to Edit)\" class=\"date_col\"><span class=\"fake_anchor\"
						
						onclick=\"javascript:prepare_and_fire_entry_form(
									'edit_flight',
									'$flight_id',
									'" . date('m/d/Y', strtotime($sql_date)) . "',
									'" . mres($remarks) . "',
									new Array('$plane_id{$is_sim}','$route','$js_total',
										'$pic','$solo','$sic','$dual_recieved',
										'$dual_given','$act_instrument','$sim_instrument',
										'$approaches','$night','$xc','$day_landings',
										'" . mres($night_landings) . "','" . mres($student) . "','" . mres($instructor) . "',
										'" . mres($fo) . "','" . mres($captain) . "','$flight_number',
										'$holding','$tracking','$bfr','$ipc',
										'$pilot_checkride','$cfi_checkride'),
										''
										);\">$date</span></td>";
				else
					$proper_date_column = "<td class=\"date_col\">$date</td>";
				
				
				
				#####################################################

				$items .=  "<tr class=\"$color\">
						
						$proper_date_column
						
						<td title=\"Plane\">$display_plane</td>
						<td title=\"Route\">$route</td>
						<td title=\"Total\">$display_total</td>
						<td title=\"PIC\" class=\"pic_col\">$pic</td>
						<td title=\"Solo\" class=\"solo_col\">$solo</td>
						<td title=\"SIC\" class=\"sic_col\">$sic</td>
						<td title=\"Dual Recieved\" class=\"dual_recieved_col\">$dual_recieved</td>
						<td title=\"Dual Given\" class=\"dual_given_col\">$dual_given</td>
						<td title=\"Actual Instrument\" class=\"act_col\">$act_instrument</td>
						<td title=\"Simulated Instrument\" class=\"sim_col\">$sim_instrument</td>
						<td title=\"Approaches\" class=\"approaches_col\">$disp_approaches</td>
						<td title=\"Night\" class=\"night_col\">$night</td>
						<td title=\"Cross Country\" class=\"xc_col\">$xc</td>
						<td title=\"Day Landings\" class=\"day_land_col\">$day_landings</td>
						<td title=\"Night Landings\" class=\"night_land_col\">$night_landings</td>
						<td title=\"Student\" class=\"student_col\">$student</td>
						<td title=\"Instructor\" class=\"instructor_col\">$instructor</td>
						<td title=\"First Officer\" class=\"fo_col\">$fo</td>
						<td title=\"Captain\" class=\"captain_col\">$captain</td>
						<td title=\"Flight Number\" class=\"flight_number_col\">$flight_number</td>
						<td title=\"Remarks\" style=\"text-align:left\">{$flying}$remarks</td>
						</tr>\n\n";
			}
			
		}while($row = mysql_fetch_array($result));
		
		return $items;
	}
	
	function make_logbook($special_options = array())
	{
		//if(!empty($special_options))
			
	
		if($this->auth != "share")
			$new_entry_button = "<input type=\"button\" value=\"New Entry\"
					onclick=\"javascript:prepare_and_fire_entry_form('new_flight','','','','','')\" />";
		else
			$new_entry_button = "";
	
	
		return "<script type=\"text/javascript\" src=\"./javascripts/logbook.js\"></script>
			<script type=\"text/javascript\">hide_the_columns(\"{$this->mode}\");</script>
		
				<div style=\"float:none;width:100%;text-align:center;padding-top:10px;padding-bottom:10px\">
					$new_entry_button
				</div>
				
				<div class=\"logbook_inner\">" .
		
				$this->make_pagination("logbook", $special_options) .
		
				"<table class=\"logbook_table\" summary=\"logbook table\">" .
				
				"<thead>" . $this->make_header_row() . "</thead>" . 
				
				"<tbody>" . $this->make_logbook_rows($special_options) .
				
				$this->make_total_rows() .
				
				"</tbody></table></div>";
	}
	
	function delete_entry($flight_id)
	{
		$sql = "UPDATE flights SET `pilot_id` = '-{$this->pilot_id}' WHERE flight_id='$flight_id' AND pilot_id = {$this->pilot_id} LIMIT 1";
		mysql_query($sql);
		
		//print $sql;
	}
	
	#########################################################################################################################
	#########################################################################################################################
	#########################################################################################################################
	#########################################################################################################################
	#########################################################################################################################
	
	function make_flying_form()
	{
		if($this->mode != "All")
			$show_all_entries = "<span style=\"display:inline\" class=\"fake_anchor\" id=\"unhide_entries_text_link\" onclick=\"toggle_hide_entries();unhide_all_entries()\">Show All Entry Fields</span>
					     <span style=\"display:none\" class=\"fake_anchor\" id=\"rehide_entries_text_link\" onclick=\"toggle_hide_entries();hide_entries()\">Hide Entry Fields</span>";
			
		else	$show_all_entries = "<span style=\"display:inline\" class=\"fake_anchor\" id=\"unhide_entries_text_link\">&nbsp;</span>
					     <span style=\"display:none\" class=\"fake_anchor\" id=\"rehide_entries_text_link\">&nbsp;</span>";
	
		$delete_button = "<input type=\"submit\" name=\"submit\" value=\"Delete Entry\"
						onclick=\"return confirm('Are you sure? This is unreversable.')\" />";
	
		$hide_script = "<script type=\"text/javascript\">var mode = \"{$this->mode}\";</script>";
		
		$dropdown = $this->make_dropdown("new_entry");
	
		if($this->dropdown_type == 2)							//if the user is using the dual dropdown typw, then make the plane section twice as high
			$plane_height = "style=\"height:50px\"";
		else
			$plane_height = "";
		
		$non_flying_link = "<span class=\"fake_anchor\" onclick=\"prepare_and_fire_entry_form('new_non_flight');\">New Non-Flying Event</span>";
		
		$new_entry_button = "<input type=\"submit\" name=\"submit\" value=\"Submit New Entry\" onclick=\"return validate_new_entry_form(new_entry_form);\" />";
		$edit_entry_button = "<input type=\"submit\" name=\"submit\" value=\"Submit Edit\" onclick=\"return validate_new_entry_form(new_entry_form);\" />";
		
		$new_entry = <<<EOF

		<div class="dragme">
		<div class="new_entry_popup_dragbar">
			<div id="entry_popup_title" class="inner_title">New Entry</div>
			<div class="fake_anchor close_button" onclick='javascript:close_popup("new_entry_popup");'>X</div>
		</div>
		</div>

		<script type="text/javascript" src="javascripts/new_entry.js"></script>
		<script type="text/javascript" src="javascripts/validate_new_entry.js"></script>
		
		<form id="new_entry_form" action="handle_entry_form.php" method="post">
		
		<div class="new_entry_left">
		
		<input type="hidden" name="page" value="{$_GET['p']}" />
		
			<div class="keep_vertical">
				<div class="entry_entry_left"><input type="text" class="long_textbox" name="date" value="$display_date" /></div>
				<div class="title_entry_left"><span style="font-size:small;color:gray">(mm/dd/yyyy) </span>Date</div>
			</div>
			
			<div class="keep_vertical">	
				<div class="entry_entry_left"><input type="text" class="long_textbox" name="route" value="{$result_array['route']}" /></div>
				<div class="title_entry_left">Route</div>
			</div>
			
			<div class="keep_vertical">
				<div class="entry_entry_left" $plane_height>$dropdown</div>
				<div class="title_entry_left">Plane</div>
			</div>
			
			<div id="instructor_div" class="keep_vertical">
				<div class="entry_entry_left"><input type="text" class="long_textbox" name="instructor" value="{$result_array['instructor']}" /></div>
				<div class="title_entry_left">Instructor</div>
			</div>
			
			<div id="student_div" class="keep_vertical">
				<div class="entry_entry_left"><input type="text" class="long_textbox" name="student" value="{$result_array['student']}" /></div>
				<div class="title_entry_left">Student</div>
			</div>
			
			<div id="fo_div" class="keep_vertical">
				<div class="entry_entry_left"><input type="text" class="long_textbox" name="fo" value="{$result_array['fo']}" /></div>
				<div class="title_entry_left">First Officer</div>
			</div>
			
			<div id="captain_div" class="keep_vertical">
				<div class="entry_entry_left"><input type="text" class="long_textbox" name="captain" value="{$result_array['captain']}" /></div>
				<div class="title_entry_left">Captain</div>
			</div>
			
			<div id="flight_number_div" class="keep_vertical">
				<div class="entry_entry_left"><input type="text" class="long_textbox" name="flight_number" value="{$result_array['flight_number']}" /></div>
				<div class="title_entry_left">Flight Number</div>
			</div>
			
			<div id="remarks_div" class="keep_vertical">
				<div class="entry_entry_left" style="height:110px"><textarea class="long_textbox" rows="5" cols="10" name="remarks">{$result_array['remarks']}</textarea></div>
				<div class="title_entry_left" style="height:110px">Remarks</div>
			</div>
		</div>
		
		
		
		<div class="new_entry_right">
				
			<div id="total_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="total" value="" />
					<input type="button" value="&nbsp;" onclick="total_button('new_entry_form')" title="Shortcut Button" />
				</div>
				
				<div id="total_label" class="title_entry_right">Total</div>
			</div>
			
			<div id="pic_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="pic" value="{$result_array['pic']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').pic.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').pic.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				<div class="title_entry_right">PIC</div>
			</div>
			
			<div id="sic_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="sic" value="{$result_array['sic']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').sic.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').sic.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">SIC</div>
			</div>
			
			<div id="solo_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="solo" value="{$result_array['solo']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').solo.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').solo.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">Solo</div>
			</div>
			
			<div id="dual_given_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="dual_given" value="{$result_array['dual_given']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').dual_given.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').dual_given.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">Dual Given</div>
			</div>
			
			<div id="dual_recieved_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="dual_recieved" value="{$result_array['dual_recieved']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').dual_recieved.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').dual_recieved.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">Dual Received</div>
			</div>
			
			<div id="sim_instrument_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="sim_instrument" value="{$result_array['sim_instrument']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').sim_instrument.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').sim_instrument.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">Hood</div>
			</div>
			
			<div id="act_instrument_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="act_instrument" value="{$result_array['act_instrument']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').act_instrument.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').act_instrument.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">Actual</div>
			</div>
			
			<div id="night_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="night" value="{$result_array['night']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').night.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').night.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">Night</div>
			</div>
						
			<div id="xc_div" class="keep_vertical">
				<div class="entry_entry_right">
					<input type="text" autocomplete="off" class="short_textbox" name="xc" value="{$result_array['xc']}" />
					<input type="button" title="Shortcut Button" value="&nbsp;" onclick="document.getElementById('new_entry_form').xc.value = document.getElementById('new_entry_form').total.value == document.getElementById('new_entry_form').xc.value ? '' : document.getElementById('new_entry_form').total.value" />
				</div>
				
				<div class="title_entry_right">Cross Country</div>
			</div>
		</div>
		
	<div class="landing_boxes">
		
			<div id="day_landings_div" style="float:left;width:33%;border:0px solid black;overflow:hidden;">
				Day Landings &nbsp; <input type="text" autocomplete="off" style="width:2em" name="day_landings" value="{$result_array['day_landings']}" />
			</div>
					
			<div id="night_landings_div" style="float:left;width:33%;border:0px solid black;overflow:hidden;">
				Night Landings &nbsp; <input type="text" autocomplete="off" style="width:2em" name="night_landings" value="{$result_array['night_landings']}" />
			</div>
				
			<div id="approaches_div" style="float:left;width:33%;border:0px solid black;overflow:hidden;">
				Approaches &nbsp; <input type="text" autocomplete="off" style="width:2em" name="approaches" value="{$result_array['approaches']}" />
			</div>
		
	</div>
		
		<div class="events_div">
				
			<table style="text-align:left;width:100%" summary="Non-flying event selection">
				<tbody>
					<tr>
						<td><input type="checkbox" name="pilot_checkride" value="1" $pilot_checkride_sel /></td>
						<td>Pilot Checkride</td>
						<td><input type="checkbox" name="bfr" value="1" $bfr_sel /></td>
						<td>Flight Review</td>
						<td><input type="checkbox" name="holding" value="1" $holding_sel /></td>
						<td>Holding</td>
					</tr>
					<tr>
						<td><input type="checkbox" name="cfi_checkride" value="1" $cfi_checkride_sel /></td>
						<td>Instructor Checkride</td>
						<td><input type="checkbox" name="ipc" value="1" $ipc_sel /></td>
						<td>Instrument Proficiency Check</td>
						<td><input type="checkbox" name="tracking" value="1" $tracking_sel /></td>
						<td>Intercepting &amp; Tracking</td>
					</tr>
				</tbody>

			</table>
		</div>		
				
				
			
		<div style="width:100%;float:left;text-align:center;border:0px solid black"><input type="hidden" name="flight_id" value="" />
				
			<div id="new_entry_buttons">
				$new_entry_button <br /><br /> $show_all_entries &nbsp; &nbsp; &nbsp; $non_flying_link
			</div>
		
			<div id="edit_entry_buttons">
				$edit_entry_button $delete_button
			</div>
		
		</div>
				
		
		</form>
		
		$hide_script
		
		
EOF;
		return $new_entry;
	}
	
	function make_non_flying_form()
	{
	
		$delete_button = "<input type=\"submit\" name=\"submit\" value=\"Delete Entry\" onclick=\"return confirm('Are you sure? This is unreversable.');\" />";
		$new_button = "<input type=\"submit\" name=\"submit\" value=\"Submit New Entry\" onclick=\"return validate_non_flying_form('non_flying_form');\" />";
		$edit_button = "<input type=\"submit\" name=\"submit\" value=\"Submit Edit\" onclick=\"return validate_non_flying_form('non_flying_form');\" />";
		
		$bottom_link = "<span class=\"fake_anchor\" onclick=\"prepare_and_fire_entry_form('new_flight');\">New Flying Event</span>";
	
	return <<<EOF
	
		<div class="dragme">
		<div class="new_entry_popup_dragbar">
			<div id="non_popup_title" class="inner_title">New Entry</div>
			<div class="fake_anchor close_button" onclick='javascript:close_popup("new_entry_popup");'>X</div>
		</div>
		</div>
	
		<form id="non_flying_form" action="handle_entry_form.php" method="post">
					
		<table summary="non-flying event selection">
			<tbody>
				<tr>
					<td align="right"><span style="font-size:small;color:gray">(mm/dd/yyyy) </span>Date</td>
					<td align="left"><input type="text" class="long_textbox" style="width:8em" name="date" /></td>
				</tr>
				<tr>	
					<td valign="top" align="right">Remarks</td>
					<td align="left"><textarea class="long_textbox" style="width:300px" rows="5" cols="10" name="remarks"></textarea></td>
				</tr>
			</tbody>
				
		</table>	
		
		
		<div class="events_div">
		
			<input type="hidden" name="page" value="{$_GET['p']}" />
				
			<table style="text-align:left;width:100%" summary="non-flying event selection">
				<tbody>
					<tr>
						<td><input type="radio" name="non_flying" value="1" /> 1st Class Medical</td>
						<td><input type="radio" name="non_flying" value="2" /> 2nd Class Medical</td>
						<td><input type="radio" name="non_flying" value="3" /> 3rd Class Medical</td>
					</tr>
		
					<tr>
						<td><input type="radio" name="non_flying" value="4" /> CFI Refresher</td>
						<td><input type="radio" name="non_flying" value="5" /> Student Signoff</td>
					</tr>
				</tbody>

			</table>
		</div>		
				
				
			
		<div style="width:100%;float:left;text-align:center;border:0px solid black"><input type="hidden" name="flight_id" value="" />
						
			<div id="new_non_buttons">
				$new_button  <br /><br /> $bottom_link
			</div>		
		
			<div id="edit_non_buttons">
				$edit_button $delete_button
			</div>

		</div>
				
		
		</form>
	
	
	
EOF;
	}
}
?>
