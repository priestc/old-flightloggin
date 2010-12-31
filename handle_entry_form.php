<?php

include "classes/logbook_class.php";

$user = new logbook(true);

if(!empty($_POST))
{
	$page = empty($_POST['page']) ? "" : $_POST['page'];

	if($_POST['submit'] == "Delete Entry")
		$user->delete_entry($_POST['flight_id']);
		
	if($_POST['submit'] == "Submit New Entry")
		$user->submit_logbook($_POST);
		
	if($_POST['submit'] == "Submit Edit")	
		$user->submit_logbook($_POST, $_POST['flight_id']);
		
	header("Location: logbook.php?p={$page}");
	exit;
}

?>
