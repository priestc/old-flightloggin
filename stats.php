<?php

include "classes/stats_class.php";

$user = new stats(true);
$webpage = new page("Stats", 1, $user->auth);

################################################################
	
$years_array = $user->get_years();

if($_GET['career'] == "yes")
{
	$number = empty($_GET["number"]) ? 90 : mres($_GET["number"]);
	$duration = empty($_GET["duration"]) ? 'day' : mres($_GET["duration"]);
	$category = empty($_GET["category"]) ? '(1,2,3,4)' : mres($_GET["category"]);
	$tag = empty($_GET["tag"]) ? '' : mres($_GET["tag"]);
	
	
	$tag_clause = empty($_GET["tag"]) ? '' : "AND planes.plane_id in (SELECT plane_id FROM tags WHERE UPPER(tag) = '{$_GET["tag"]}')";
	
	####################################################################
	
	$sql = "SELECT SUM(total), SUM(pic), SUM(sic), SUM(sim_instrument), SUM(act_instrument), SUM(solo), SUM(day_landings),
					SUM(night_landings), SUM(xc), SUM(night), SUM(dual_given), SUM(dual_recieved), SUM(simulator), SUM(approaches)
		FROM flights, planes
		WHERE flights.plane_id = planes.plane_id
		AND flights.date >= date_add( now( ) , INTERVAL -$number $duration )
		AND planes.category_class in $category
		AND flights.pilot_id = {$user->pilot_id}
		$tag_clause";
		
		//print $sql;

	$result = mysql_query($sql);
	
	$the_rest = mysql_fetch_array($result);
	
	$total = number_format($the_rest[0],1);
	$pic = number_format($the_rest[1],1);
	$sic = number_format($the_rest[2],1);
	$sim_instrument = number_format($the_rest[3],1);
	$act_instrument = number_format($the_rest[4],1);
	$solo = number_format($the_rest[5],1);
	$day_landings = number_format($the_rest[6],0);
	$night_landings = number_format($the_rest[7],0);
	$xc = number_format($the_rest[8],1);
	$night = number_format($the_rest[9],1);
	$dual_given = number_format($the_rest[10],1);
	$dual_recieved = number_format($the_rest[11],1);
	$sim_ftd = number_format($the_rest[12],1);
	$approaches = number_format($the_rest[13],0);


	###################################################
	
	$tags = $user->get_tags();
	
	if($duration == "year") $year_sel = "selected = \"selected\"";
	if($duration == "month") $month_sel = "selected = \"selected\"";
	if($duration == "day") $day_sel = "selected = \"selected\"";
	
	switch($category)
	{
		case "(1,2,3,4,5,6,7,8,9,10,11,12,13,14,1,16,17,18,19,20)": {$any_sel="selected"; break;}
		case "(1,2,3,4)": {$airplane_sel="selected = \"selected\""; break;}
		case "(1,3)": {$single_sel="selected = \"selected\""; break;}
		case "(2,4)": {$multi_sel="selected = \"selected\""; break;}
		case "(3,4)": {$seaplane_sel="selected = \"selected\""; break;}
		case "(1,2)": {$landplane_sel="selected = \"selected\""; break;}
		case "(1,3)": {$single_sel="selected = \"selected\""; break;}
		case "(1)": {$sel_sel="selected = \"selected\""; break;}
		case "(2)": {$mel_sel="selected = \"selected\""; break;}
		case "(3)": {$ses_sel="selected = \"selected\""; break;}
		case "(3)": {$mes_sel="selected = \"selected\""; break;}
		case "(4)": {$glider_sel="selected = \"selected\""; break;}
		case "(5)": {$heli_sel="selected = \"selected\""; break;}
		case "(6)": {$gyro_sel="selected = \"selected\""; break;}
	}
	
	####################################
	
	if($user->auth == "sec")
		$sec_field = "<input type=\"hidden\" class=\"short_textbox\" name=\"sec\" value=\"{$user->pilot_id}\" />";
		
	elseif($user->auth == "share")
	{
		$token_field = "<input type=\"hidden\" class=\"short_textbox\" name=\"token\" value=\"{$user->token}\" />";
		$share_field = "<input type=\"hidden\" class=\"short_textbox\" name=\"share\" value=\"{$user->pilot_id}\" />";
	}
	
	###################################
		
	$career_total_box = <<<EOF

<div>
	
	<table class="career_totals" summary="Day Summary">
		<thead>
			<tr>
				<td colspan="2">
					<form method="get" action="stats.php?career=yes">
						<div>
							<input type="hidden" class="short_textbox" name="career" value="yes" />
							
							$token_field
							$sec_field
							$share_field
														
							<input type="text" autocomplete="off" class="short_textbox" name="number" value="$number" />
								<select name="duration">
									<option value="month" $month_sel>Months</option>
									<option value="day" $day_sel>Days</option>
									<option value="year"$year_sel>Years</option>
								</select><br />
							
								<select name="category">
									<option value="(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20)" $any_sel>Any</option>
									<option value="(1,2,3,4)" $airplane_sel>Airplane</option>
									<option value="(1,3)" $single_sel>Single Engine</option>
									<option value="(2,4)" $multi_sel>Multi Engine</option>
									<option value="(2,4)" $seaplane_sel>Seaplane</option>
									<option value="(1,2)" $landplane_sel>Landplane</option>
									<option value="(1)" $sel_sel>SEL</option>
									<option value="(2)" $mel_sel>MEL</option>
									<option value="(3)" $ses_sel>SES</option>
									<option value="(4)" $mes_sel>MES</option>
									<option value="(5)" $glider_sel>Glider</option>
									<option value="(6)" $heli_sel>Helicopter</option>
									<option value="(7)" $gyro_sel>Gyroplane</option>
									<option value="(7)" $gyro_sel>Gyroplane</option>
								</select>
							
								<select name="tag">
									<option value="">----</option>
EOF;
									foreach($tags as $this_tag)
									{
										$selected = "";
										if($this_tag == $tag)
										$selected = "selected = \"selected\"";
										$career_total_box .= "<option value=\"$this_tag\" $selected>$this_tag</option>\n";
									}


	$career_total_box .= <<<EOF
								</select>
							
							<input type="submit" class="short_textbox" value="Go" />
						</div>
					</form>
				</td>
			</tr>
		</thead>
	
		<tbody>
			<tr>
				<td colspan="2"><hr />&nbsp;</td>
			</tr>
					
			<tr>
				<td style="text-align:right">Total</td>
				<td style="text-align:left;padding-left:25px">$total</td>
			</tr>
			
			<tr>
				<td style="text-align:right">PIC</td>
				<td style="text-align:left;padding-left:25px">$pic</td>
			</tr>
			
			<tr>
				<td style="text-align:right">SIC</td>
				<td style="text-align:left;padding-left:25px">$sic</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Solo</td>
				<td style="text-align:left;padding-left:25px">$solo</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Simulated Instrument</td>
				<td style="text-align:left;padding-left:25px">$sim_instrument</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Actual Instrument</td>
				<td style="text-align:left;padding-left:25px">$act_instrument</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Approaches</td>
				<td style="text-align:left;padding-left:25px">$approaches</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Night</td>
				<td style="text-align:left;padding-left:25px">$night</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Cross Country</td>
				<td style="text-align:left;padding-left:25px">$xc</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Dual Given</td>
				<td style="text-align:left;padding-left:25px">$dual_given</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Dual Recieved</td>
				<td style="text-align:left;padding-left:25px">$dual_recieved</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Simulator/FTD</td>
				<td style="text-align:left;padding-left:25px">$sim_ftd</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Day Landings</td>
				<td style="text-align:left;padding-left:25px">$day_landings</td>
			</tr>
			
			<tr>
				<td style="text-align:right">Night Landings</td>
				<td style="text-align:left;padding-left:25px">$night_landings</td>
			</tr>
			
		</tbody>
	</table>
			
			
</div>
EOF;


}
if($_GET['eighty710'] == "yes")
{
	$eighty710 = $user->make_8710();


}

$hash = substr(sha256($user->pilot_id . "dongs"),0,10);

$stats .= <<<EOF
	<div class="central_div">

		{$webpage->ads}
	
		<script type="text/javascript">
			var hash = "$hash";
			var pilot_id = "{$user->pilot_id}";
		</script>
	
		<script type="text/javascript" src="javascripts/stats.js"></script>
	
		<div class="big_time_box">
			<a href="map.php{$user->proper_q}">Route Map</a>, <a href="map.php?type=markers{$user->proper_a}">Airport Map</a>
		</div>
		
		<div class="big_time_box">
			<a href="stats.php?career=yes{$user->proper_a}">Career Totals</a>, <a href="stats.php?eighty710=yes{$user->proper_a}">8710 data</a>
		</div>
		
		<form id="sig_form">
			<div class="big_time_box">Signature Image: 
			
				<select name="color">
					<option value="1">Blue</option>
					<option value="2">Red</option>
					<option value="3">Green</option>
				</select>


				<select name="time">
					<option value="total">Total</option>
					<option value="act_instrument">Actual Instrument</option>
					<option value="multi">Multi-Engine</option>
					<option value="turbine">Turbine</option>
					<option value="turbine_pic">Turbine PIC</option>
					<option value="dual_given">Dual Given</option>
				</select>
				<input type="button" onclick="make_sig()" value="Generate Code" /></td>
			
			</div>
 		</form>
 		
 		<form id="the_form" action="stats.php">
 		
 			<input type="hidden" name="sec" value="{$user->pilot_id}" />
 		
	 		<div class="big_time_box">
				Bar Graph: <select name="bar_time">
					<option value="total">Total</option>
					<option value="landings">Landings</option>
					<option value="solo">Solo</option>
					<option value="pic_sic">PIC/SIC</option>
					<option value="dual_recieved">Dual Recieved</option>
					<option value="dual_given">Dual Given</option>
					<option value="instrument">Instrument</option>
					<option value="xc">Cross Country</option>
					<option value="night">Night</option>
				</select>
			
				<select name="bar_item">
					<option value="tail_number">by Tailnumbers</option>
					<option value="year">by Year</option>
					<option value="month">by Months</option>
					<option value="student">by Student</option>
					<option value="instructor">by Instructor</option>
					<option value="fo">by First Officer</option>
					<option value="captain">by Captain</option>
					<option value="category_class">by Category/Classes</option>
					<option value="type">by Types</option>
					<option value="model">by Model</option>
					<option value="manufacturer">by Manufacturers</option>
				</select>
			
				<input type="button" name="Go" value="Go" onclick="javascript:make_graph();" />
			
			</div>
			
			<div class="big_time_box">
				Accumulative Line Plot:
			
				<select name="line_item">
					<option value="total">Total</option>
					<option value="day_landings">Day Landings</option>
					<option value="night_landings">Night Landings</option>
					<option value="pic">PIC</option>
					<option value="sic">SIC</option>
					<option value="solo">Solo</option>
					<option value="dual_recieved">Dual Recieved</option>
					<option value="dual_given">Dual Given</option>
					<option value="act_instrument">Actual Instrument</option>
					<option value="sim_instrument">Simulated Instrument</option>
					<option value="xc">Cross Country</option>
					<option value="night">Night</option>
				</select>
			
				<select name="line_year">
					<option value="all">All</option>";
EOF;
					
					foreach($years_array as $year)
					{
						$stats .= "<option value=\"$year\">$year</option>\n";
					}
					
					
$stats .= "		</select>
			
				<input type=\"button\" name=\"Go\" value=\"Go\" onclick=\"javascript:make_line();\" />
			</div>
	
		</form>
	
			<div id=\"graph_div\" style=\"width:100%\">
	
	
				$career_total_box
				$eighty710
				
			</div>";

$stats .= "</div>";	//closes central_div

###############################################################

$webpage->add_content($stats);

###############################################################

$html = $webpage->output();

echo $html;
?>
