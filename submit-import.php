<?php

include "classes/import_class.php";

$user = new import_(true);

################################################################

if(!empty($_POST['submit']))
{
	$user->file = file($_POST['filename']);
	
	if($_POST['submit'] == "Replace")
		mysql_query("DELETE FROM flights WHERE pilot_id = {$user->pilot_id}");
		
	if($errorline = $user->put_into_database())				//an error occured
	{
		$webpage = new page("Import Error", 1);
		
		$webpage->add_content("There was an error trying to enter line <b>$errorline</b> into the database :(");
		
		echo $webpage->output();
	}
	else							//no errors
	{
		print "all good";
		header("Location: logbook.php{$user->proper_q}");
		exit;
	}
}
?>
