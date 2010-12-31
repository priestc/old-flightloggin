<?php
include "main_classes.php";

class currency extends auth
{
	function make_medical_box()
	{
		$this->get_medical_info();
		
		//$this->medical_class = 2;
		
		switch($this->medical_class)
		{
			case 1:		{$class = "1st Class Medical";break;}
			case 2:		{$class = "2nd Class Medical";break;}
			case 3:		{$class = "3rd Class Medical";break;}
			default:	 $class = "Medical";
		}
		$alert = "";
		
		if($class != "Medical")
		
			return "<div class=\"current {$alert}\"><big>$class</big><br />
					<small>Next Downgrade: <strong>" . date('F jS, Y', $this->medical_downgrade) . "</strong></small></div>";
					
		elseif($this->medical_downgrade != 0)
			return "<div class=\"current not_current\"><big>$class</big><br />
					<small>Last Medical Expired: <strong>" . date('F jS, Y', $this->medical_downgrade) . "</strong></small></div>";
		else
			return "<div class=\"current not_current\"><big>$class</big><br />
					<small>There are no medical exams found in your logbook</small></div>";
			
	}
	
	function make_currency_boxes()
	{
		$ninety_days_ago = strtotime('-90 days');								//plain 90 days ago
		
		$this->six_months_ago = $this->calendar_month(strtotime('now'), -6);
		$this->twelve_months_ago = $this->calendar_month(strtotime('now'), -12);
		$this->twentyfour_months_ago = $this->calendar_month(strtotime('now'), -24);
		$this->thirtysix_months_ago = $this->calendar_month(strtotime('now'), -36);
		$this->sixty_months_ago = $this->calendar_month(strtotime('now'), -60);
				
		$boxes .= "<div class=\"landings_currency_container\">";
				
		for($cat_class_i=1;$cat_class_i<23;$cat_class_i++)						//only go to 15 becausae FTD and Simulator currency is not needed
		{
			$category_class = switchout_category($cat_class_i);
			
			$letter = switchout_category_letter($cat_class_i);
			
			if(strstr($this->currency_string, $letter[0]))								//first letter in the array is for day
			{
				$any_landings_date = strtotime($this->date_of_last_three_any_landings($cat_class_i));
				$any_landings_date = $any_landings_date == "" ? 0 : $any_landings_date;
				
				if($any_landings_date >= $ninety_days_ago)
					$boxes .= $this->make_landing_box("Day", "yes", $category_class, $any_landings_date);
				
				elseif($any_landings_date > 0)
					$boxes .= $this->make_landing_box("Day", "no", $category_class, $any_landings_date);
				
				else
					$boxes .= $this->make_landing_box("Day", "never", $category_class);
			}
			
			if(strstr($this->currency_string, $letter[1]))								//second letter for night
			{
				$night_landings_date = strtotime($this->date_of_last_three_night_landings($cat_class_i)); 	//get the date of the third to last landing date
				$night_landings_date = $night_landings_date == "" ? 0 : $night_landings_date;			//make zero if there are not three landing
				
				if($night_landings_date >= $ninety_days_ago)
					$boxes .= $this->make_landing_box("Night", "yes", $category_class, $night_landings_date);
				
				elseif($night_landings_date > 0)
					$boxes .= $this->make_landing_box("Night", "no", $category_class, $night_landings_date);
				
				else
					$boxes .= $this->make_landing_box("Night", "never", $category_class);
			}
		}
		
			$boxes .= $this->make_type_rating_boxes();
		
		if(strstr($this->currency_string, 'G'))										//airplane instrument current
			$boxes .= $this->instrument_currency_box("Airplane");
		
		if(strstr($this->currency_string, 'H'))										//helicopter instrument current
			$boxes .= $this->instrument_currency_box("Helicopter");
			
		if(strstr($this->currency_string, 'L'))		
			$boxes .= $this->make_bfr_box();
			
		if(strstr($this->currency_string, 'K'))		
			$boxes .= $this->make_cfi_box();
			
		if(strstr($this->currency_string, 'J'))		
			$boxes .= $this->make_medical_box();
		
		$boxes .= "</div>";
		
		$boxes .= "<div class=\"times_currency_container\">";
		
	
			if(strstr($this->currency_string, 'M'))		
				$boxes .= $this->make_135_box();
		
			if(strstr($this->currency_string, 'N'))		
				$boxes .= $this->make_ATP_box();
			
			
		
		$boxes .= "</div>";
				
		return $boxes;
	}
	
	function instrument_currency_box($category)
	{
		$approaches_timestamp = strtotime($this->date_of_last_six_approaches($category));
		$holding_timestamp = strtotime($this->date_of_last_one("holding", $category));
		$tracking_timestamp = strtotime($this->date_of_last_one("tracking", $category));
		
		$this->get_ipc_timestamp();
		
		$oldest_timestamp = min($approaches_timestamp, $tracking_timestamp, $holding_timestamp);//currency based on approaches, holds, etc calculate from the oldest of the three
		$currency_from_timestamp = max($oldest_timestamp, $this->ipc_timestamp);		//currency based on either IPC or recent experience is based on the most recent
		$currency_from_timestamp = strtotime(date('F 1, Y', $currency_from_timestamp));		//reset to the first day of that month
		
		$inst_until_date = date('F 1, Y', $currency_from_timestamp + (7 * 31 * 24 * 60 * 60) );	//add 7 months, then go back to the first day of that month
		
		$display_until_ipc_date = str_replace(" 1, ", " 1st, ", $until_ipc_date);
		$display_inst_until_date = str_replace(" 1, ", " 1st, ", $inst_until_date);
			
		if(
			($tracking_timestamp >= $this->six_months_ago && $holding_timestamp >= $this->six_months_ago && $approaches_timestamp >= $this->six_months_ago)  ||
			($this->ipc_timestamp >= $this->six_months_ago)
		
		)
		{
			if( ((strtotime($inst_until_date) - strtotime("now")) / 3600 / 24) < 10	)		//alert if less than 10 days away from expiration
				$alert = "almost_expired";
			
			$boxes .= "<div class=\"current {$alert}\" ><big>Instrument-$category</big><br />
								<small>Expires: <strong>" . str_replace(" 1, ", " 1st, ", $inst_until_date) . "</strong></small></div>";
		}
			
		elseif(	
			($tracking_timestamp >= $this->twelve_months_ago && $holding_timestamp >= $this->twelve_months_ago && $approaches_timestamp >= $this->twelve_months_ago)  ||
			($this->ipc_timestamp >= $this->twelve_months_ago)
		)											//not instrument current, but not quite old enough to require an IPC
		{	
		
			$until_ipc_date = date('M 1, Y', $currency_from_timestamp + (13 * 31 * 24 * 60 * 60) );	//add 13 months, then go back to the first day of that month
			$display_until_ipc_date = str_replace(" 1 ", " 1st ", $until_ipc_date);
				
			$instrument_array = $this->instrument_current_numbers($category);
			$approaches_needed = 6 - $instrument_array['approaches'];
			
			$boxes .= "<div class=\"current not_current\"><big>Instrument-$category</big><br />
						<small>You need:<br /> ";
			
				//$boxes .= "Either an <strong>IPC</strong>, or ";
				
				if ($instrument_array['holds'] == 0 && $instrument_array['tracking'] >= 1)
					$boxes .= "<strong>Holding</strong>, ";
				
				elseif ($instrument_array['holds'] >=1 && $instrument_array['tracking'] == 0)
					$boxes .= "<strong>Tracking</strong>, ";

				elseif($instrument_array['holds'] == 0 && $instrument_array['tracking'] == 0)
					$boxes .= "<strong>Holding and Tracking</strong>, ";
				
				if($instrument_array['approaches'] < 6)
					$boxes .= "<strong>" . $approaches_needed . " Apps.</strong>";
					
				$boxes .= "<br />IPC required after: <strong>$display_until_ipc_date</strong></small></div>";
		}
		elseif(
			($tracking_timestamp != 0 && $holding_timestamp != 0 && $approaches_timestamp != 0) || $this->ipc_timestamp != 0
		)
		{
			$months_ago = round(  (((strtotime("now") - strtotime($inst_until_date)) / 3600) / 24) / 30 * 10) / 10;
			
			$boxes .= "<div class=\"current not_current\"><big>Instrument-$category</big><br />
					<small>Currency Lost: <strong>" . $display_inst_until_date . "</strong><br />
					which was <strong>$months_ago</strong> months ago.<br />
					<strong>You need an IPC</strong></small></div>";
			
			//$boxes .= "<div class=\"not_current\">" . $inst_until_date . "----$months_ago </div>";
		}
		else
		{
			$boxes .= "<div class=\"current not_current\"><big>Instrument-$category</big><br /><small>You have never been Instrument-$category current</small></div>";
		
		
		}
		
		return $boxes;
	}
	
	function make_type_rating_boxes()						//uses make_landing_box()
	{
		$type_currency_array = explode("*", $this->type_currency_string);
		$ninety_days_ago = strtotime('-90 days');
		
		foreach($type_currency_array as $coded_type)
		{
			$type = substr($coded_type, 0, strlen($loded_type)-2);
		
			if(strstr($coded_type, "^n"))
			{
				$night_landings_date = strtotime($this->type_rating_date_of_last_three_night_landings($type));
				$night_landings_date = $night_landings_date == "" ? 0 : $night_landings_date;
				
				if($night_landings_date >= $ninety_days_ago)
					$boxes .= $this->make_landing_box("Night", "yes", $type, $night_landings_date);
					
				elseif($night_landings_date == 0)
					$boxes .= $this->make_landing_box("Night", "never", $type);
					
				else	$boxes .= $this->make_landing_box("Night", "no", $type, $night_landings_date);
			}
			elseif(strstr($coded_type, "^g"))
			{
				$any_landings_date = strtotime($this->type_rating_date_of_last_three_any_landings($type));
				$any_landings_date = $any_landings_date == "" ? 0 : $any_landings_date;
				
				if($any_landings_date >= $ninety_days_ago)
					$boxes .= $this->make_landing_box("Day", "yes", $type, $any_landings_date);
					
				elseif($any_landings_date == 0)
					$boxes .= $this->make_landing_box("Day", "never", $type);
					
				else
					$boxes .= $this->make_landing_box("Day", "no", $type, $any_landings_date);
			}
		}
		
		return $boxes;
		
	}
	
	function make_bfr_box()
	{
		$this->get_bfr_dates();
		
		if($this->bfr_timestamp >= $this->twentyfour_months_ago)
		{
			if(($this->bfr_expire - strtotime("now")) / (3600 *24) < 30)		//alert if expiration is less than 30 days away
				$alert = "almost_expired";
			else	$alert = "";
				
			return "<div class=\"current {$alert}\"><big>Flight Review</big><br />
					<small>Expires: <strong>" . date('F jS, Y', $this->bfr_expire) . "</strong></small></div>";
		}
	
		elseif($this->bfr_timestamp != 0)
			return "<div class=\"current not_current\"><big>Flight Review</big><br />
					<small>Last Flight Review: <strong>" . date('F jS, Y', $this->bfr_timestamp) . "</strong><br />
					Which expired: <strong>" . date('F jS, Y', $this->bfr_expire) . "</strong></small></div>";
			
		else	return "<div class=\"current not_current\"><big>Flight Review</big><br /><small>There are no Flight Reviews or Pilot Checkrides found in your logbook.</small></div>";
	
	}
	
	function make_cfi_box()
	{
		$this->get_cfi_dates();
		
		if($this->cfi_timestamp >= $this->twentyfour_months_ago)					//is current
		{
			if(($this->cfi_expire - strtotime("now")) / (3600 *24) < 30)		//alert if expiration is less than 30 days away
				$alert = "almost_expired";

			return "<div class=\"current {$alert}\"><big>Flight Instructor</big><br />
					<small>Expires: <strong>" . date('F jS, Y', $this->cfi_expire) . "</strong></small></div>";
		}
	
		elseif($this->cfi_timestamp != 0)									//not current
			return "<div class=\"current not_current\"><big>Flight Instructor</big><br />
					<small>Last CFI renewal: <strong>" . date('F jS, Y', $this->cfi_timestamp) . "</strong><br />
					Which expired on: <strong>" . date('F jS, Y', $this->cfi_expire) . "</strong></small></div>";
			
		else	return "<div class=\"current not_current\"><big>Flight Instructor</big><br /><small>There are no CFI checkrides or Refreshers found in your logbook.</small></div>";
	
	}
	
	function make_landing_box($day_night, $yes_no, $category_class, $last_until = 0, $needs = false)
	{
		if($yes_no == "yes")			//is current
		{
			$expiration_timestamp = $last_until + (90 * 24 * 60 * 60);
			$expiration_date = date('F jS, Y', $expiration_timestamp);
			
			if(($expiration_timestamp - strtotime("now")) / (3600 *24) < 10)		//alert if expiration is less than 10 days away
				$alert = "almost_expired";
				
			return "<div class=\"current {$alert}\"><big>$category_class $day_night</big><br />
					<small>Expires: <strong>$expiration_date</strong></small></div>";
		}
		elseif($yes_no == "no")			//not current
		{
			$days_ago = round((strtotime('now') - $last_until) / (3600 * 24));
			$date_of_last = date('F jS, Y',$last_until);
		
			return "<div class=\"current not_current\"><big>$category_class $day_night</big><br />
						<small>Date of third-to-last landing:<br /><strong>$date_of_last <br />($days_ago days Ago)</strong></small></div>";
		}
		elseif($yes_no == "never")		//does not have three landings
		{
			$second_day_night = $day_night == "Day" ? "" : $day_night;		//don't print "Day" if
			
			return "<div class=\"current not_current\"><big>$category_class $day_night</big><br />
						<small>You have not yet logged 3 $category_class $second_day_night landings</small></div>\n\n";
		}
	
	}
	
	function make_ATP_box()
	{
		$this->get_ATP_times();
		
		if($is_total = $this->total_total + $this->approved_sim >= 1500)		//1500 total time
			$total_gray = "class=\"qualified\"";
		else	$total_gray = "class=\"not_qualified\"";
			
		if($is_night = $this->total_night + $this->extra_night >= 100)			//100 night
			$night_gray = "class=\"qualified\"";
		else	$night_gray = "class=\"not_qualified\"";
			
		if($is_xc = $this->total_xc >= 500)						//500 xc
			$xc_gray = "class=\"qualified\"";
		else	$xc_gray = "class=\"not_qualified\"";
			
		if($is_instrument = $this->total_instrument >= 75)				//75 instrument
			$instrument_gray = "class=\"qualified\"";
		else	$instrument_gray = "class=\"not_qualified\"";
		
		if($is_pic = $this->total_pic >= 250)						//250 PIC
			$pic_gray = "class=\"qualified\"";
		else	$pic_gray = "class=\"not_qualified\"";
		
		if($is_pic_xc = $this->total_pic_xc >= 100)					//100 XC PIC
			$pic_xc_gray = "class=\"qualified\"";
		else	$pic_xc_gray = "class=\"not_qualified\"";
		
		if($is_pic_night = $this->total_pic_night >= 25)				//25 night PIC
			$pic_night_gray = "class=\"qualified\"";
		else	$pic_night_gray = "class=\"not_qualified\"";
		
		$disp_x_night = $this->extra_night == 0 ? "" : " (+" . $this->extra_night . ")";
		$disp_x_total = $this->approved_sim == 0 ? "" : " (+" . $this->approved_sim . ")";
		$disp_x_inst = $this->adjusted_simulator_instrument == 0 ? "" : " (+" . $this->adjusted_simulator_instrument . ")";
		
		#########################################################################
		
		$title = "You <strong>do not</strong> have the flight times to qualify for the ATP checkride as described in 14 CFR Part 61.159";
		
		if($is_total && $is_night && $is_xc && $is_instrument && $is_pic && $is_pic_xc && $is_pic_night)			//VFR qualified
		{
			$atp_title = "<span class=\"qualified\">ATP &#10003;</span>";
			$title = "You have the flight times to qualify for the ATP checkride as described in 14 CFR Part 61.159";
		}
		else
			$atp_title = "<span class=\"not_qualified\">ATP &#10005;</span>";

	
			return "<div class=\"div_ATP\">
			
					<div style=\"width:100%\">
						$title
					</div>
				
					<div style=\"float:left;width:100%\">
						<table class=\"table_ATP\" summary=\"ATP minimums\">
							<tbody>
								<tr>
									<td colspan=\"3\" class=\"top_135\">$atp_title</td>
								</tr>
					
								<tr $total_gray>
									<td>Total:</td>
									<td>" . number_format($this->total_total,1) . $disp_x_total . "</td>
									<td style=\"text-align:left\">/ 1,500</td>
								</tr>
						
								<tr $xc_gray>
									<td>XC:</td>
									<td>" . number_format($this->total_xc,1) . "</td>
									<td style=\"text-align:left\">/ 500</td>
								</tr>
					
								<tr $night_gray>
									<td>Night:</td>
									<td>" . number_format($this->total_night,1) . $disp_x_night . "</td>
									<td style=\"text-align:left\">/ 100</td>
								</tr>
					
								<tr $instrument_gray>
									<td>Instrument:</td>
									<td>" . number_format($this->plane_instrument,1) . $disp_x_inst . "</td>
									<td style=\"text-align:left\">/ 75</td>
								</tr>
							
								<tr $pic_gray>
									<td>PIC:</td>
									<td>" . number_format($this->total_pic,1) . "</td>
									<td style=\"text-align:left\">/ 250</td>
								</tr>
							
								<tr $pic_xc_gray>
									<td>PIC XC:</td>
									<td>" . number_format($this->total_pic_xc,1) . "</td>
									<td style=\"text-align:left\">/ 100</td>
								</tr>
							
								<tr $pic_night_gray>
									<td>PIC Night:</td>
									<td>" . number_format($this->total_pic_night,1) . "</td>
									<td style=\"text-align:left\">/ 25</td>
								</tr>
							
							</tbody>
					
						</table>
					</div>
					
				</div>";
	}
	
	function make_135_box()
	{
		$this->get_135_times();	
					
		if($is_ifr_total = $this->total_total >= 1200)
			$ifr_total_gray = "class=\"qualified\"";
		else	$ifr_total_gray = "class=\"not_qualified\"";
			
		if($is_vfr_total = $this->total_total >= 500)
			$vfr_total_gray = "class=\"qualified\"";
		else	$vfr_total_gray = "class=\"not_qualified\"";
			
		if($is_night = $this->total_night >= 100)
			$night_gray = "class=\"qualified\"";
		else	$night_gray = "class=\"not_qualified\"";
			
		if($is_ifr_xc = $this->p2p_xc >= 500)
			$ifr_xc_gray = "class=\"qualified\"";
		else	$ifr_xc_gray = "class=\"not_qualified\"";
			
		if($is_vfr_xc = $this->p2p_xc >= 100)
			$vfr_xc_gray = "class=\"qualified\"";
		else	$vfr_xc_gray = "class=\"not_qualified\"";
			
		if($is_instrument = $this->total_instrument >= 75)
			$instrument_gray = "class=\"qualified\"";
		else	$instrument_gray = "class=\"not_qualified\"";
		
		if($is_night_xc = $this->p2p_night_xc >= 25)
			$night_xc_gray = "class=\"qualified\"";
		else	$night_xc_gray = "class=\"not_qualified\"";
		
		
		#########################################################################
		
		$title = "You <strong>do not</strong> have the flight times to qualify for VFR or IFR operations as described in 14 CFR Part 135.243";
		
		if($is_vfr_total && $is_night_xc && $is_vfr_xc)			//VFR qualified
		{
			$vfr_title = "<span class=\"qualified\">VFR &#10003;</span>";
			$title = "You have the flight times to qualify for VFR-only operations as described in 14 CFR Part 135.243";
		}
		else
			$vfr_title = "<span class=\"not_qualified\">VFR &#10005;</span>";
				
		
		if($is_ifr_total && $is_night && $is_ifr_xc && $is_instrument)	//if IFR qualified
		{
			$ifr_title = "<span class=\"qualified\">IFR &#10003;</span>";
			$title = "You have the flight times to qualify for IFR and VFR operations as described in 14 CFR Part 135.243";
		}
		else
			$ifr_title = "<span class=\"not_qualified\">IFR &#10005;</span>";
		
		return "<div class=\"div_135\">
		
				<div style=\"width:100%;border:0px solid black\">
					$title
				</div>
			
				<div style=\"float:left;width:50%\">
					<table summary=\"Part 135 IFR Minimums\">
						<tbody>
							<tr>
								<td colspan=\"3\" class=\"top_135\">$ifr_title</td>
							</tr>
				
							<tr $ifr_total_gray>
								<td>Total:</td>
								<td>" . number_format($this->total_total,1) . "</td>
								<td style=\"text-align:left\">/ 1,200</td>
							</tr>
					
							<tr $ifr_xc_gray>
								<td>P2P XC:</td>
								<td>" . number_format($this->p2p_xc,1) . "</td>
								<td style=\"text-align:left\">/ 500</td>
							</tr>
				
							<tr $night_gray>
								<td>Night:</td>
								<td>" . number_format($this->total_night,1) . "</td>
								<td style=\"text-align:left\">/ 100</td>
							</tr>
				
								<tr $instrument_gray>
								<td>Instrument:</td>
								<td>" . number_format($this->total_instrument,1) . "</td>
								<td style=\"text-align:left\">/ 75</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div style=\"float:right;width:50%\">
				
					<table summary=\"Part 135 VFR Minimums\">
						<tr>
							<td colspan=\"3\" class=\"top_135\">$vfr_title</td>
						</tr>
				
						<tr $vfr_total_gray>
							<td>Total:</td>
							<td>" . number_format($this->total_total,1) . "</td>
							<td style=\"text-align:left\">/ 500</td>
						</tr>
					
						<tr $vfr_xc_gray>
							<td>P2P XC:</td>
							<td>" . number_format($this->p2p_xc,1) . "</td>
							<td style=\"text-align:left\">/ 100</td>
						</tr>
				
						<tr $night_xc_gray>
							<td>Night P2P XC:</td>
							<td>" . number_format($this->p2p_night_xc,1) . "</td>
							<td style=\"text-align:left\">/ 25</td>
						</tr>
				
					</table>
				</div>
				
			</div>";
	}
	
	#########################################################################################################################
	#########################################################################################################################
	#########################################################################################################################
	#########################################################################################################################
	#########################################################################################################################
	
	function get_ATP_times()
	{
		$this->get_adjusted_total_instrument();
		$this->get_total_pic_night();
		$this->get_total_pic_xc();
		$this->get_approved_sim_time();
		$this->get_extra_night();
	}
	

	function get_135_times()
	{
		$this->get_p2p_xc();
		$this->get_p2p_night_xc();	
		$this->get_adjusted_total_instrument();
	}
	
	function get_p2p_xc()
	{
		if(!isset($this->p2p_xc))
		{
			$sql = "SELECT SUM(total)
				FROM flights
				WHERE
					substring(fix_route(route), 1, 3) != substring(fix_route(route), 5, 3)
				
					AND substring(fix_route(route), 1, 4) != substring(fix_route(route), 6, 4)
				
					AND LENGTH(fix_route(route)) > 4 AND flights.pilot_id = {$this->pilot_id}";
			
			$result = mysql_query($sql);
			$first_array = mysql_fetch_array($result);
		
			$this->p2p_xc = $first_array[0];
		}
	}
	
	function get_plane_instrument()
	{	
		if(!isset($this->plane_instrument))
		{
			$sql = "SELECT SUM(flights.sim_instrument)
				FROM flights
				WHERE simulator IS NULL
					AND flights.pilot_id = {$this->pilot_id}";
			
			$result = mysql_query($sql);
			$second_array = mysql_fetch_array($result);
		
			$this->plane_instrument = $second_array[0] + $this->total_actual;
		}
	}
	
	function get_adjusted_simulator_instrument()						//"adjusted" because it cuts off at 25.
	{
		if(!isset($this->adjusted_simulator_instrument))
		{
			$sql = "SELECT SUM(flights.sim_instrument)
				FROM flights
				WHERE simulator > 0
					AND flights.pilot_id = {$this->pilot_id}";
			
			$result = mysql_query($sql);
			$third_array = mysql_fetch_array($result);
		
			$this->adjusted_simulator_instrument = $third_array[0];
			
			if($this->adjusted_simulator_instrument > 25)
				$this->adjusted_simulator_instrument = 25.0;
		}
	}
	
	function get_p2p_night_xc()
	{
		if(!isset($this->p2p_night_xc))
		{	
			$sql = "SELECT SUM(night)
				FROM flights
				WHERE 
					night > 0
				
					AND substring(route, 1, 3) != substring(route, 5, 3)
				
					AND substring(route, 1, 4) != substring(route, 6, 4)
				
					AND LENGTH(route) >= 4 AND flights.pilot_id = {$this->pilot_id}";
			
			$result = mysql_query($sql);
			$fourth_array = mysql_fetch_array($result);
		
			$this->p2p_night_xc = $fourth_array[0];
		}
	}
	
	function get_total_pic_night()
	{
		if(!isset($this->total_pic_night))
		{
			$sql = "SELECT SUM(`night`)
				FROM flights
				WHERE night > 0 AND `pic` = `total` AND flights.pilot_id = {$this->pilot_id}";
			
			$result = mysql_query($sql);
			$third_array = mysql_fetch_array($result);
		
			$this->total_pic_night = $third_array[0];
		}
	}
	
	function get_total_pic_xc()
	{
		if(!isset($this->total_pic_xc))
		{
			$sql = "SELECT SUM(`pic`)
				FROM flights
				WHERE `xc` = `total` AND flights.pilot_id = {$this->pilot_id}";
			
			$result = mysql_query($sql);
			$third_array = mysql_fetch_array($result);
		
			$this->total_pic_xc = $third_array[0];
		}
	}
	
	function get_approved_sim_time()
	{
		if(!isset($this->approved_sim_time))
		{
			$sql = "SELECT SUM(flights.simulator)
				FROM flights, planes
				WHERE flights.plane_id = planes.plane_id AND category_class = 15 AND flights.pilot_id = {$this->pilot_id}";
			
			$result = mysql_query($sql);
			$third_array = mysql_fetch_array($result);
		
			$this->approved_sim = $third_array[0];
			
			if($this->approved_sim > 100)
				$this->approved_sim = 100;
		}
	}
	
	function get_extra_night()
	{
		if($this->total_night_landings > 20)
		{
			$this->extra_night = $this->total_night_landings - 20;
			
			if($this->extra_night > 25)
				$this->extra_night = 25;
		}
	}
	
	function get_adjusted_total_instrument()
	{
		$this->get_plane_instrument();
		$this->get_adjusted_simulator_instrument();	
		$this->total_instrument = $this->plane_instrument + $this->adjusted_simulator_instrument;
	}
	
	function get_ipc_timestamp()
	{
		$sql = "SELECT `date` 
			FROM `flights`
			WHERE ipc = 1 AND pilot_id = {$this->pilot_id}
			ORDER BY `date` DESC
			LIMIT 1";
			
		$result = mysql_query($sql);
			$third_array = mysql_fetch_array($result);
		
			$this->ipc_timestamp = strtotime($third_array[0]);
	}
	
	function get_bfr_dates()
	{
		$sql = "SELECT `date` 
			FROM `flights`
			WHERE (bfr = 1 OR pilot_checkride = 1) AND pilot_id = {$this->pilot_id}
			ORDER BY `date` DESC
			LIMIT 1";
			
		$result = mysql_query($sql);
			$third_array = mysql_fetch_array($result);
		
		$this->bfr_timestamp = strtotime($third_array[0]);
		
		if($this->bfr_timestamp == 0)
			$this->bfr_expire = 0;
			
		else
			$this->bfr_expire = strtotime(date('M 1, Y', $this->calendar_month($this->bfr_timestamp, 24) ));
	}
	
	function get_cfi_dates()
	{
		$sql = "SELECT `date` 
			FROM `flights`
			WHERE (cfi_checkride = 1 OR cfi_refresher = 1) AND pilot_id = {$this->pilot_id}
			ORDER BY `date` DESC
			LIMIT 1";
			
		$result = mysql_query($sql);
			$third_array = mysql_fetch_array($result);
		
			$this->cfi_timestamp = strtotime($third_array[0]);
			
		if($this->cfi_timestamp == 0)
			$this->cfi_expire = 0;
		
		else
			$this->cfi_expire = strtotime(date('M 1, Y', $this->calendar_month($this->cfi_timestamp, 24) ));
	}
	
	function calendar_month($orig_timestamp, $number_of_months)
	{
		//print "function ran\n";
	
		if(!($number_of_months % 12))					//if the months come out to be a whole year, 36, 24, 12, etc.
		{
			//print "whole year\n";
			$years_to_incriment = $number_of_months / 12;
		
			$month = date("m", $orig_timestamp);
			$year = date("Y", $orig_timestamp);
			
			$new_month = $month + 1;
			
			if($new_month < 0)		//if the month end up in the last year, subtract a year and increase the months by 12
			{
				$new_month += 12;
				$years_to_incriment--;
			}
			
			if($new_month > 12)		//if the month is greater than 12, incriment the year one, and decrease the month by one
			{
				$new_month -= 12;
				$years_to_incriment++;
			}
			
			if($number_of_months > 1)
				$date = ($year + $years_to_incriment) . "-" . $new_month . "-01";
			else	$date = ($year + $years_to_incriment) . "-" . $month . "-01";
		}
		else								//if the months do not make a whole year
		{	
			//print "not whole year\n";				
			$month = date("m", $orig_timestamp);
			$year = date("Y", $orig_timestamp);
			
			if($number_of_months > 1)				//in case a negative number is given
				$new_month = $month + $number_of_months + 1;
			else	$new_month = $month + $number_of_months;
			
			if($new_month < 0)
			{
				$new_month += 12;
				$year--;
			}
			
			if($new_month > 12)			//if the month is greater than 12, incriment the year one, and decrease the month by one
			{
				$new_month -= 12;
				$year++;
			}
			
			$date = $year . "-$new_month-01";		
		}
		
		
		
		//print "original: " . date("Y-m-d",$orig_timestamp) . "\n" .
		//		"$number_of_months\n" .
		//		"new-date: $date\n" . 
		//		"stamp: " . strtotime($date) . "\n\n";
				
		return	strtotime($date);
	
	}
	
	function get_medical_info()						//all this function does is sets $this->medical_downgrade and $this->medical_class
	{
		$sql = "SELECT flights.date, flights.medical_class, pilots.dob
			FROM `flights`, `pilots`
			WHERE medical_class > 0 AND flights.pilot_id = {$this->pilot_id} AND pilots.pilot_id = {$this->pilot_id}
			ORDER BY date DESC
			LIMIT 1";
			
		$result = mysql_query($sql);
		$third_array = mysql_fetch_array($result);
		
		$this->medical_exam_timestamp = strtotime($third_array[0]);
		$this->medical_exam_class = $third_array[1];
		
		#########################
		
		//$this->dob = empty($third_array[2]) ? date("Y-m/d", strtotime("now")) : $third_array[2];
		
		$this->dob = $third_array[2];
		$new_year = date("Y", strtotime($this->dob)) + 40;
		$forty_timestamp = strtotime($new_year . date("-m-d", strtotime($this->dob)));
		
		//print date("Y-m-d", $forty_timestamp);
		
		##########################
		
		
		if($this->medical_exam_timestamp <= $forty_timestamp)			//under 40
		{
			//print "under_40";
		
			if($this->medical_exam_class == 3)
			{
				$third_class_ago    = $this->sixty_months_ago;					//set when the third class had to have been done for it to be still a 3rd
				$third_class_expire = $this->calendar_month($this->medical_exam_timestamp, 60);	//set when it expires
				
				//print "issued: 3\n\n";
			}
		
			elseif($this->medical_exam_class == 2)
			{
				$second_class_ago    = $this->twelve_months_ago;				//set when the second class had to have been done for it to be still a 2nd
				$second_class_expire = $this->calendar_month($this->medical_exam_timestamp, 12);//set when it expires
				
				$third_class_ago    = $this->sixty_months_ago;					//set when the third class had to have been done for it to be still a 1st
				$third_class_expire = $this->calendar_month($this->medical_exam_timestamp, 60);	//set when it expires
				
				//print "issued: 2\n\n";
			}
			
			elseif($this->medical_exam_class == 1)
			{
				$first_class_ago    = $this->twelve_months_ago;					//set when the first class had to have been done for it to be still good
				$first_class_expire = $this->calendar_month($this->medical_exam_timestamp, 12);	//set when it expires
				
				$second_class_ago    = $this->twelve_months_ago;				//set when the second class had to have been done for it to be still good
				$second_class_expire = $this->calendar_month($this->medical_exam_timestamp, 12);//set when it expires
				
				$third_class_ago    = $this->sixty_months_ago;					//set when the third class had to have been done for it to be still good
				$third_class_expire = $this->calendar_month($this->medical_exam_timestamp, 60);	//set when it expires
				
				//print "issued: 1\n\n";
				
				//$this->calendar_month($this->medical_exam_timestamp, 12);
			}
		
		}
		else									//over 40
		{
			if($this->medical_exam_class == 3)
			{
				$third_class_ago    = $this->thirtysix_months_ago;				//set when the third class had to have been done for it to be still good
				$third_class_expire = $this->calendar_month($this->medical_exam_timestamp, 36);	//set when it expires
			}
		
			if($this->medical_exam_class == 2)
			{
				$second_class_ago    = $this->twelve_months_ago;				//set when the second class had to have been done for it to be still good
				$second_class_expire = $this->calendar_month($this->medical_exam_timestamp, 12);//set when it expires
				
				$third_class_ago    = $this->thirtysix_months_ago;				//set when the third class had to have been done for it to be still good
				$third_class_expire = $this->calendar_month($this->medical_exam_timestamp, 36);	//set when it expires
			}
			
			if($this->medical_exam_class == 1)
			{
				$first_class_ago    = $this->six_months_ago;					//set when the first class had to have been done for it to be still good
				$first_class_expire = $this->calendar_month($this->medical_exam_timestamp, 6);	//set when it expires
				
				$second_class_ago    = $this->twelve_months_ago;				//set when the second class had to have been done for it to be still good
				$second_class_expire = $this->calendar_month($this->medical_exam_timestamp, 12);//set when it expires
				
				$third_class_ago    = $this->thirtysix_months_ago;				//set when the third class had to have been done for it to be still good
				$third_class_expire = $this->calendar_month($this->medical_exam_timestamp, 36);	//set when it expires
			}
		
		}
		
		if($this->medical_exam_timestamp > $first_class_ago && $first_class_ago > 0)
		{
		
			//print "first:" . date("M-d-Y", $this->medical_exam_timestamp). " needs to be after " . date("M-d-Y", $first_class_ago) . "\n";
		
			$this->medical_class = 1;
			$this->medical_downgrade = $first_class_expire;
		}
		
		elseif($this->medical_exam_timestamp > $second_class_ago && $second_class_ago > 0)
		{
		
			//print "second:" . date("M-d-Y", $this->medical_exam_timestamp). " needs to be after " . date("M-d-Y", $second_class_ago) . "\n";
		
			$this->medical_class = 2;
			$this->medical_downgrade = $second_class_expire;
		}
			
		elseif($this->medical_exam_timestamp > $third_class_ago && $third_class_ago > 0)
		{
		
			//print "third:" . date("M-d-Y", $this->medical_exam_timestamp). " needs to be after ". date("M-d-Y", $third_class_ago) . "\n";
		
			$this->medical_class = 3;
			$this->medical_downgrade = $third_class_expire;
		}
		
		else
		{
			//print "expired:" . date("M-d-Y", $this->medical_exam_timestamp). " on ". date("M-d-Y", $third_class_ago) . "\n";
		
			$this->medical_class = 0;
			$this->medical_downgrade = $third_class_expire;
		}
		
		
		//print "\nexipres: " . date("M-d-Y", $this->medical_downgrade);

	}

}
