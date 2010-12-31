<?php

include "classes/logbook_class.php";

$user = new logbook(true);

if(!empty($_GET['sec']))
	$user->pilot_id = $_GET['sec'];
	
$webpage = new page("403", 1, $user->auth);

###############################################################



$display .= "<span class=\"error_page\">403 Forbidden</span>";

###############################################################

$webpage->add_content($display);

###############################################################

$html = $webpage->output();

echo $html;

?>
