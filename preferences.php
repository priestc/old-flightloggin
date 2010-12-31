<?php

include "classes/prefs_class.php";

$user = new prefs(true);

if(!empty($_POST))
{
	$user->submit_prefs($_POST);
	
	$user->create_session_variables();
	
	header('Location: logbook.php');
	exit;
}

	$webpage = new page("Preferences", 1, $user->auth);

	$user->get_prefs();

	###############################################################

	$year  = date("Y");
	$year1 = date("Y") + 1;
	$year2 = date("Y") + 2;

	$year_1 = date("Y") - 1;
	$year_2 = date("Y") - 2;
	$year_3 = date("Y") - 3;
	$year_4 = date("Y") - 4;

	$njy = date("n-j-y");
	$njY = date("n-j-Y");
	$mdY = date("m-d-Y");
	$Dnjy = date("D n-j-y");
	$jny = date("j-n-y");
	$jnY = date("j-n-Y");
	$dmY = date("d-m-Y");
	$Djny = date("D j-n-y");
	
	#################################################################
	
	$categories_array = $user->get_categories();
	
	$tailwheel_classes = $user->get_tailwheel_classes();
	
	$categories_array = array_merge($categories_array,$tailwheel_classes);
	
	//each letter represents a currency routine that the user selects.
	foreach(array(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N) as $letter)
		if(strstr($user->currency_string, $letter))
			$$letter = "checked = \"checked\"";
	
	###################################################################
	
	$type_currency_array = explode("*", $user->type_currency_string);
	
	foreach($type_currency_array as $item)
	{
		$type_checkboxes[$item] = "checked = \"checked\"";
	
	}
	
	###################################################################
	switch ($user->style)
	{
		case 1:
			$style_one_sel = "selected = \"selected\"";break;
		case 2:
			$style_two_sel = "selected = \"selected\"";break;
		case 3:
			$style_three_sel = "selected = \"selected\"";break;
		case 4:
			$style_four_sel = "selected = \"selected\"";break;
		case 5:
			$style_five_sel = "selected = \"selected\"";break;
		case 6:
			$style_six_sel = "selected = \"selected\"";break;
		case 7:
			$style_seven_sel = "selected = \"selected\"";break;
		case 8:
			$style_eight_sel = "selected = \"selected\"";break;
	}
		
	switch ($user->mode)
	{
		case 1:
			$default_sel = "selected = \"selected\"";break;
		case 2:
			$student_sel = "selected = \"selected\"";break;
		case 3:
			$private_sel = "selected = \"selected\"";break;
		case 4:
			$instructor_sel = "selected = \"selected\"";break;
		case 5:
			$fo_sel = "selected = \"selected\"";break;
		case 6:
			$captain_sel = "selected = \"selected\"";break;
		case 7:
			$all_sel = "selected = \"selected\"";break;
	}
		
	switch ($user->date_format)
	{
		case 1:
			$format_1_sel = "selected = \"selected\"";break;
		case 2:
			$format_2_sel = "selected = \"selected\"";break;
		case 3:
			$format_3_sel = "selected = \"selected\"";break;
		case 4:
			$format_4_sel = "selected = \"selected\"";break;
		case 5:
			$format_5_sel = "selected = \"selected\"";break;
		case 6:
			$format_6_sel = "selected = \"selected\"";break;
		case 7:
			$format_7_sel = "selected = \"selected\"";break;
		case 8:
			$format_8_sel = "selected = \"selected\"";break;
	}
	
	switch ($user->backup_frequency)
	{
		case 0:
			$backup_never_sel = "selected = \"selected\"";break;
		case 1:
			$weekly_sel = "selected = \"selected\"";break;
		case 2:
			$biweekly_sel = "selected = \"selected\"";break;
		case 3:
			$monthly_sel = "selected = \"selected\"";break;
		case 4:
			$bimonthly_sel = "selected = \"selected\"";break;
	}
	
	if($user->dob_timestamp != -1)						//if the user's dob timestamp is not -1 (which means he hasn't entered one yet), then convert to m/d/Y
		$dob = date("m/d/Y", $user->dob_timestamp);



	$content = <<<EOF

			<script type="text/javascript" src="javascripts/prefs.js"></script>
			<script type="text/javascript" src="javascripts/validate_prefs.js"></script>
	
			<div class="central_div central_prefs">
			{$webpage->ads}
			<h1>Preferences</h1>

			<form id="prefs_form" action="preferences.php" method="post" onsubmit="return validate_prefs_form(this);">

			<fieldset class="sections">
				<legend>General</legend>\n
				
						<div class="pref_title">Real Name &nbsp;</div>
						<div class="pref_form"><input autocomplete="off" type="text" name="real_name" value="{$user->real_name}" /></div>
					
						<div class="pref_title">Email &nbsp;</div>
						<div class="pref_form"><input autocomplete="off" type="text" name="email" value="{$user->email}" /></div>
						
						<div class="pref_title">Date of birth &nbsp;</div>
						<div class="pref_form"><input autocomplete="off" type="text" name="dob" value="$dob" />(mm/dd/yyyy)</div>
						
						<div class="pref_title">&nbsp;</div>
						<div class="pref_form">&nbsp;</div>
						
						<div class="pref_title">Old Password &nbsp;</div>
						<div class="pref_form"><input autocomplete="off" type="password" name="old_pass" value="" /></div>
						
						<div class="pref_title">New Password &nbsp;</div>
						<div class="pref_form"><input autocomplete="off" type="password" name="new_pass1" value="" /></div>
						
						<div class="pref_title">Confirm New Password &nbsp;</div>
						<div class="pref_form"><input autocomplete="off" type="password" name="new_pass2" value="" /></div>
						
						<div class="pref_title">&nbsp;</div>
						<div class="pref_form">&nbsp;</div>
					
						<div class="pref_title">Style &nbsp;</div>
						<div class="pref_form"> 
							<select name="style">\n
								<option value="1" $style_one_sel>Blue (Default)</option>
								<option value="2" $style_two_sel>Red</option>
								<option value="3" $style_three_sel>Stupid</option>
							</select>
						</div>
			</fieldset>


				<fieldset class="sections">
				<legend>Currency Reminders</legend>
				
					
						<div class="pref_title">Medical Renewal &nbsp;</div>
						<div class="currency_day"><input type="checkbox" value="J" name="J" $J /></div>
						<div class="currency_night"></div>
						<div class="currency_dummy"></div>
					
						<div class="pref_title">CFI Renewal &nbsp;</div>
						<div class="currency_day"><input type="checkbox" value="K" name="K" $K /></div>
						<div class="currency_night"></div>
						<div class="currency_dummy"></div>
						
						
						<div class="pref_title">Flight Review Renewal &nbsp;</div>
						<div class="currency_day"><input type="checkbox" value="L" name="L" $L /></div>
						<div class="currency_night"></div>
						<div class="currency_dummy"></div>
						
						<div class="pref_title">FAR Part 135 PIC minimums &nbsp;</div>
						<div class="currency_day"><input type="checkbox" value="M" name="M" $M /></div>
						<div class="currency_night"></div>
						<div class="currency_dummy"></div>
						
						<div class="pref_title">ATP minimums &nbsp;</div>
						<div class="currency_day"><input type="checkbox" value="N" name="N" $N /></div>
						<div class="currency_night"></div>
						<div class="currency_dummy"></div>


						<div class="pref_title">Instrument-Airplane &nbsp;</div>
						<div class="currency_day"><input type="checkbox" value="G" name="G" $G /></div>
						<div class="currency_night"></div>
						<div class="currency_dummy"></div>
EOF;
						if(array_search('6', $categories_array))
						{
							$content .= "<div class=\"pref_title\">Instrument-Helicopter &nbsp;</div>
									<div class=\"currency_day\"><input type=\"checkbox\" value=\"H\" name=\"H\" $H /></div>
									<div class=\"currency_night\"></div>
									<div class=\"currency_dummy\"></div>";
						}
					
						if(array_search('5', $categories_array))
						{
							$content .= "<div class=\"pref_title\">Instrument-Glider &nbsp;</div>
									<div class=\"currency_day\"><input type=\"checkbox\" value=\"I\" name=\"I\" $I /></div>
									<div class=\"currency_night\"></div>
									<div class=\"currency_dummy\"></div>";
						}

		$content .= <<<EOF
						<div class="pref_title">&nbsp;</div>
						<div class="currency_day"></div>
						<div class="currency_night"></div>
						<div class="currency_dummy"></div>
		
						<div class="pref_title">&nbsp;</div>
						<div class="currency_day">Day</div>
						<div class="currency_night">Night</div>
						<div class="currency_dummy"></div>
					
						<div class="pref_title">Airplane / SEL &nbsp;</div>
						<div class="currency_day"><input type="checkbox" value="a" name="a" $a /></div>
						<div class="currency_night"><input type="checkbox" value="b" name="b" $b /></div>
						<div class="currency_dummy"></div>
EOF;

	##########################################################################

					foreach($categories_array as $category_num)
					{
						$category_letter = switchout_category_letter($category_num);
						$category_name = switchout_category($category_num);
					
						$content .= "	<div class=\"pref_title\">$category_name &nbsp;</div>
									<div class=\"currency_day\">
										<input type=\"checkbox\" value=\"{$category_letter[0]}\" name=\"{$category_letter[0]}\" ${$category_letter[0]} />
									</div>
								
									<div class=\"currency_night\">
										<input type=\"checkbox\" value=\"{$category_letter[1]}\" name=\"{$category_letter[1]}\" ${$category_letter[1]} />
									</div>
								<div class=\"currency_dummy\"></div>";
					}

	##########################################################################
					
					$type_rating_array = $user->get_type_ratings();
					
					$content .= "<input type=\"hidden\" name=\"types\" value=\"" . implode("&&", $type_rating_array) . "\" />";
					
					foreach($type_rating_array as $type)
					{					
						$content .= "	<div class=\"pref_title\">$type &nbsp;</div>
									<div class=\"currency_day\">
										<input type=\"checkbox\" value=\"1\" name=\"$type-gen\" {$type_checkboxes["$type"."^g"]} />
									</div>
								
									<div class=\"currency_night\">
										<input type=\"checkbox\" value=\"1\" name=\"$type-night\" {$type_checkboxes["$type"."^n"]} />
									</div>
								<div class=\"currency_dummy\"></div>";
					}
					
	##########################################################################
									
	$content .= <<<EOF
			</fieldset>

			<fieldset class="sections">\n
				<legend>Logbook</legend>
				
						<div class="pref_title">Logbook Display Mode&nbsp;</div>
						<div class="pref_form">

							<select name="mode">
								<option value="1" $default_sel>Default</option>
								<option value="2" $student_sel>Student</option>
								<option value="3" $private_sel>Private</option>
								<option value="4" $instructor_sel>Instructor</option>
								<option value="5" $fo_sel>Airline First Officer</option>
								<option value="6" $captain_sel>Airline Captain</option>
								<option value="7" $all_sel>All</option>
							</select>
						</div>
					
						<div class="pref_title">Entries per page&nbsp;</div>
						<div class="pref_form">
					
							<input type="text" name="logbook_per_page" value={$user->logbook_per_page}>

						</div>
					
						<div class="pref_title">Date Format&nbsp;</div>
						<div class="pref_form">
					
							<select name="date_format">
								<option value="1" $format_1_sel>M-D-YY ($njy)</option>
								<option value="2" $format_2_sel>M-D-YYYY ($njY)</option>
								<option value="3" $format_3_sel>MM-DD-YYYY ($mdY)</option>
								<option value="4" $format_4_sel>Day M-D-YY ($Dnjy)</option>
								<option value="5" $format_5_sel>D-M-YY ($jny)</option>
								<option value="6" $format_6_sel>D-M-YYYY ($jnY)</option>
								<option value="7" $format_7_sel>DD-MM-YYYY ($dmY)</option>
								<option value="8" $format_8_sel>Day D-M-YY ($Djny)</option>
							</select>
						</div>
					
				</fieldset>
				
				<fieldset class="sections">\n
					<legend>Automatic Backups</legend>
					
						<div class="pref_title">Backup Email&nbsp;</div>
						<div class="pref_form">

							<input autocomplete="off" type="text" name="backup_email" value="{$user->backup_email}" />
							
						</div>
						
						<div class="pref_title">Frequency&nbsp;</div>
						<div class="pref_form">

							<select name="backup_frequency">
								<option value="0" $backup_never_sel>Never</option>
								<option value="1" $weekly_sel>Weekly</option>
								<option value="2" $biweekly_sel>Every 2 weeks</option>
								<option value="3" $monthly_sel>Monthly</option>
								<option value="4" $bimonthly_sel>Every 2 months</option>
							</select>
						</div>
				</fieldset>


				<div style="padding-top:10px">
					<input class="prefs_submit_button" type="submit" value="Submit" />
				</div>
			</form>
		</div>
		
EOF;

	###############################################################

		$webpage->add_content($content);

	###############################################################
	$html = $webpage->output();

	echo $html;

?>
