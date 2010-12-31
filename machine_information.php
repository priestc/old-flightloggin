<?php

$hostName = "localhost";
$userName = "flphp";
$password = "D1E3T%4Y5^N7";
$dbName = "logbook";

mysql_connect($hostName, $userName, $password) or die("Unable to connect to host $hostName");
mysql_select_db($dbName) or die( "Unable to connect to database");

$save_location = "uploads/";

function sha256($phrase)
{
	return hash('sha256', $phrase);
}

function mres($item)
{
	return mysql_real_escape_string($item);
}

function trim_array(&$value) 
{ 
    $value = trim($value); 
}

?>
