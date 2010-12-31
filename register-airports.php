<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head>
<body>
<?

include "machine_information.php";

//header("content-type: text/plain");


$sql= " SELECT *
	FROM airports
	WHERE NOT `lat` = 0
	AND NOT `long` = 0
	AND NOT location_type = 'N'
	ORDER BY identifier";

$result = mysql_query($sql);

function escape($input)
{
	$input = utf8_encode($input);
	$new = str_replace("&quot;", '\"', $input);
	return str_replace("&#39;", "\'", $new);
}


while($row = mysql_fetch_assoc($result))
{
	$airport = $row['identifier'];
	$name = escape($row['airport_name']);
	$city = escape($row['city']);
	$sector = escape($row['sector']);

	if(strlen($airport) == 3 && !preg_match("/[0-9]/", $airport) && $sector != "Hawaii" && $sector != "Alaska")		//add the "K" if it needs one.
		$identifier = "K" . $airport;
	else	$identifier = $airport;
	
	
	
	$lat = $row['lat'];
	$long = $row['long'];
	
	$output .= "<br>Base.objects.get_or_create(identifier=\"$identifier\", name=\"$name\", city=\"$city\", sector=\"$sector\", lat=\"$lat\", long=\"$long\")";

}
	print $output;
	
	//print mb_detect_encoding(utf8_encode($ident))
?>
</body>
</html>
