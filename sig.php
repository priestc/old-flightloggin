<?php

include "classes/main_classes.php";
include ("styles/jpgraph-2.3/src/jpgraph.php");
include ("styles/jpgraph-2.3/src/jpgraph_line.php");
include ("styles/jpgraph-2.3/src/jpgraph_date.php");

#######################################

$pilot_id = mres($_GET['id']);
$time = mres($_GET['time']);
$color = $_GET['color'];
$user_hash = $_GET['h'];


$insert = imagecreatefrompng("styles/sigs/sig-base-$color.png");

$font = "styles/fonts/Vera.ttf";
$fontb= "styles/fonts/VeraBd.ttf";
$size = 10;
$angle = 0;

#######################################

switch($time)
{
	case "total": {$display_title = "Total Time"; break; }
	case "act_instrument": {$display_title = "Instrument Time"; break; }
	case "multi": {$display_title = "Multi-Engine Time"; break; }
	case "turbine": {$display_title = "Turbine Time"; break; }
	case "turbine_pic": {$display_title = "Turbine PIC"; break; }
	case "dual_given": {$display_title = "Dual Given"; break; }
}


#######################################

if($time == "turbine")
	$sql = "SELECT sum(total)
		FROM flights, planes
		WHERE flights.pilot_id = $pilot_id
		AND flights.plane_id = planes.plane_id
		AND planes.plane_id in (SELECT plane_id FROM tags WHERE UPPER(tag) = 'TURBINE')";
		 
elseif($time == "multi")
	$sql = "SELECT sum(total)
		FROM flights, planes
		WHERE flights.pilot_id = $pilot_id
		AND flights.plane_id = planes.plane_id
		AND planes.category_class IN (2,4)";
		
elseif($time == "turbine_pic")
	$sql = "SELECT sum(pic)
		FROM flights, planes
		WHERE flights.pilot_id = $pilot_id
		AND flights.plane_id = planes.plane_id
		AND planes.plane_id in (SELECT plane_id FROM tags WHERE UPPER(tag) = 'TURBINE')";
		
else	$sql = "SELECT sum($time)
		FROM flights
		WHERE flights.pilot_id = $pilot_id";

if(substr(sha256($pilot_id . "dongs"),0,10) == $user_hash)
{
	$result = mysql_query($sql);
	$display_time = mysql_fetch_array($result);
	$display_time = number_format($display_time[0],1);
}
else
{
	$display_title = "";
	$display_time = "invalid";
}
#######################################

switch($color)
{
	case 1: {$col = imagecolorallocate($insert, 0, 0, 0); break;}
	case 2: {$col = imagecolorallocate($insert, 0, 0, 0); break;}
	case 3: {$col = imagecolorallocate($insert, 0, 0, 128); break;}
}

###############################################################

$bbox = imagettfbbox ($size, $angle, $font, $display_title);
$bbox["left"] = 0- min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
$bbox["top"] = 0- min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);
$bbox["width"] = max($bbox[0],$bbox[2],$bbox[4],$bbox[6]) - min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
$bbox["height"] = max($bbox[1],$bbox[3],$bbox[5],$bbox[7]) - min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);
extract ($bbox, EXTR_PREFIX_ALL, 'bb');
//check width of the image
$width = imagesx($insert);
$height = imagesy($insert);
$pad = 0;
//write text
// bottom:
imagettftext($insert, $size, $angle, ($width/2 - $bb_width/2) + 75, 14, $col, $font, $display_title);

###############################################################

$bbox = imagettfbbox ($size, $angle, $fontb, $display_time);
$bbox["left"] = 0- min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
$bbox["top"] = 0- min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);
$bbox["width"] = max($bbox[0],$bbox[2],$bbox[4],$bbox[6]) - min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
$bbox["height"] = max($bbox[1],$bbox[3],$bbox[5],$bbox[7]) - min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);
extract ($bbox, EXTR_PREFIX_ALL, 'bb');
//check width of the image
$width = imagesx($insert);
$height = imagesy($insert);
$pad = 0;
//write text
// bottom:
imagettftext($insert, $size, $angle, ($width/2 - $bb_width/2) + 75, 27, $col, $fontb, $display_time);

###############################################################

//output picture
header("Content-type:  image/png");
imagepng($insert);
imagedestroy($insert);
?>
