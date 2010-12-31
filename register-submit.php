<?php

include "classes/register_class.php";

if(empty($_POST))
{
	header('Location: register.php');
	exit;
}
else //second time, handle input
{
	$user = new register;
	
	if($_POST['cookie_type'] != "perm")
		$cookie_type = "session";
	else	$cookie_type = "perm";
	
	$user->put_in_database($_POST['email'], $_POST['password'], $cookie_type);
	
	header('Location: index.php?new_reg=yes');
	exit;
}
?>
