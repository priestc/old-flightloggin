<?php

include "classes/print_class.php";

$user = new logbook_print(true);

if(!empty($user->real_name))
	$webpage = new page("{$user->real_name}'s Logbook - ". date("M d, Y"), false, $user->auth);
	
else	$webpage = new page("Logbook - ". date("M d, Y") , false, $user->auth);

##############################################################

	foreach(array("sic", "solo", "remarks", "type", "dual_recieved", "dual_given","flight_number","night", "approaches","sim",
			"act_instrument", "sim_instrument", "student", "instructor", "captain", "fo", "tailnumber") as $column)
	{
		$columns[$column] = $_POST[$column] ? true : false;
	}
	
	$text_size = $_POST['text_size'];

	//var_dump($_POST);

	if($_POST['color'] == "bw")
	
		$display .= "	<style type=\"text/css\">
					body				{font-family: sans-serif}
					.logbook_print			{font-size: $text_size; width: 100%; border-collapse: collapse;}
					.logbook_print td		{border:1px solid silver; text-align: center}
				
					.even				{background: white}
					.odd				{background: #F0F0F0}
				
					.header_row			{background: #DDDDDD}
				
					.non_flying_event		{font-weight: bold}
					.flying_event			{font-weight: bold}
				
				</style>
			
			</head>
			<body>";
		
	else
		$display .= "	<style type=\"text/css\">
					body				{font-family: sans-serif}
					.logbook_print			{font-size: $text_size; width: 100%; border-collapse: collapse;}
					.logbook_print td		{border:1px solid silver; text-align: center}
				
					.even				{background: #FFFFDD}
					.odd				{background: #F7FDF7}
				
					.header_row			{background: #DDDDDD}
				
					.non_flying_event		{font-weight: bold}
					.flying_event			{font-weight: bold; color: darkred}
				
				</style>
			
			</head>
			<body>";
	
	if(!empty($user->real_name))
		$display .= "<div style=\"padding: 20px\"><span style=\"font-size: x-large\">" . $user->real_name . "'s Logbook</span><br>
				<span style=\"font-size: medium\">Printed: " . date("j M, Y") . "</span></div>";

	$display .= $user->make_print_logbook($columns);
	
	$display .= "</body></html>";

##############################################################

	$webpage->add_content($display);

###############################################################

$html = $webpage->output();

echo $html;
?>
