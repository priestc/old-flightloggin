<?php

include "classes/logbook_class.php";

$user = new logbook(true);

if(!empty($_GET['sec']))
	$user->pilot_id = $_GET['sec'];
	
$webpage = new page("404", 1, $user->auth);

###############################################################



$display .= "<span class=\"error_page\">404 Not Found</span>";

###############################################################

$webpage->add_content($display);

###############################################################

$html = $webpage->output();

echo $html;

?>
