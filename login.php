<?php
include "classes/main_classes.php";

if(empty($_POST))
{
	header('Location: index.php');
	exit;
}
else				//handle input if it's via POST
{
	$login = new auth();
	
	if($login->auth_by_email_and_password($_POST['email'], $_POST['password'], $_POST['cookie_type']))
	{
		session_start();
		$login->get_prefs();
		$login->set_cookies();
		$login->create_session_variables();
		
		header('Location: index.php');
		exit;
	
	}
	else
	{
		header('Location: index.php?login_fail=1');
		exit;
	}
}
?>
