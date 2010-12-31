<?php

include "classes/mass_class.php";

$user = new mass(true);

if(!empty($_POST))
{

	$user->submit_mass_entry($_POST);
	
	header('Location: logbook.php');
	exit;

}


$webpage = new page("Mass Entry", 1, $user->auth);

################################################################
if(!empty($_GET['p']))
{

	$mass_entry .= "<div class=\"central_div\" style=\"width:100%;padding:0px\">
				<br>
				{$webpage->ads}
	
				<script type=\"text/javascript\" src=\"javascripts/mass_entry.js\"></script>
				
				<form id=\"myform\" method=\"post\" action=\"mass_entry.php\">

					<div class=\"big_mass_outer_div\">" . 

						$user->make_mass_entry_rows() . 
					
					"</div>	
				</form>
				
			</div>";	
}

else
{
	$mass_entry .= "<div class=\"central_div\" style=\"width:100%;padding:0px\">
			<br>
			{$webpage->ads}
	
			<script type=\"text/javascript\" src=\"javascripts/mass_entry.js\"></script>
				<form id=\"myform\" method=\"post\" action=\"mass_entry.php\">

					<div class=\"big_mass_outer_div\">" . 

						$user->make_blank_mass_entry_rows() . 
					
					"</div>	
				</form>
				
			</div>";
}
$webpage->add_content($mass_entry);

###############################################################

$html = $webpage->output();

echo $html;


?>
