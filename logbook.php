<?php

include "classes/logbook_class.php";

$user = new logbook(true);				//do the auth process
	
$webpage = new page("Logbook", 1, $user->auth);		//title, do the headers, and is the user authenticated or not.

###############################################################

###############################################################

$user->get_planes();
$user->get_logbook_info();

//print "current page:{$user->current_page}<br>total rows: {$user->total_rows}<br>per page:{$user->logbook_per_page}<br>total pages:{$user->total_number_of_pages}";

##############################################################



$logbook = $user->make_logbook($_POST);
$currency = $user->make_currency_boxes();

$display .= "<div class=\"central_div logbook_central_div\">
			<br>
		{$webpage->ads}

		$logbook

	   	$currency
	
	   </div>
	   
	   <div id=\"new_entry_popup\">
			<div id=\"flying_div\">" . $user->make_flying_form() . "</div>
			<div id=\"non_flying_div\" style=\"display:none\">" . $user->make_non_flying_form() . "</div>
	    </div>";

###############################################################

$webpage->add_content($display);

###############################################################

$html = $webpage->output();

echo $html;

?>
