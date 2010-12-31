<?php

//include ("styles/jpgraph2-r1006/src/jpgraph.php");
//include ("styles/jpgraph2-r1006/src/jpgraph_bar.php");

include ("styles/jpgraph-2.3/src/jpgraph.php");
include ("styles/jpgraph-2.3/src/jpgraph_line.php");
include ("styles/jpgraph-2.3/src/jpgraph_date.php");

include "classes/stats_class.php";

$user = new stats(true);

if(!empty($_GET['sec']))
	$user->pilot_id = $_GET['sec'];

################################################################

function time_tick_label_no_date($aVal)	//this function formats the x-axis tick marks without the date
{
	return Date('M Y', $aVal);
}

function time_tick_label_with_date($aVal)	//this function formats the x-axis tick marks with the date
{
	return Date('M d, Y', $aVal);
}

################################################################

switch($item = $_GET['type'])		//type pf time to display; in database table format
{
	case "total":		{$title = "Total";$is_time=true;break;}
	case "dual_given":	{$title = "Dual Given";$is_time=true;break;}
	case "dual_recieved":	{$title = "Dual Recieved";$is_time=true;break;}
	case "night":		{$title = "Night";$is_time=true;break;}
	case "xc":		{$title = "Cross Country";$is_time=true;break;}
	case "day_landings":	{$title = "Day Landings";break;}
	case "night_landings":	{$title = "Night Landings";break;}
	case "pic":		{$title = "PIC";$is_time=true;break;}
	case "sic":		{$title = "SIC";$is_time=true;break;}
	case "act_instrument":	{$title = "Actual Instrument";$is_time=true;break;}
	case "sim_instrument":	{$title = "Simulated Instrument";$is_time=true;break;}
	case "solo":		{$title = "Solo";$is_time=true;break;}
	case "multi":		{$title = "Multi-Engine";$is_time=true;$item = "total"; $and_clause = "(category_class = 2 OR category_class = 4) AND ";break;}
	case "single":		{$title = "Single-Engine";$is_time=true;$item = "total"; $and_clause = "(category_class = 1 OR category_class = 3) AND ";break;}
	case "sea":		{$title = "Sea-Plane";$is_time=true;$item = "total"; $and_clause = "(category_class = 3 OR category_class = 4) AND ";break;}
}

$year = $_GET['year'];
$month = $_GET['month'];

switch($user->style)
{
	case 1:
	{
		$bar_color2 = "#1148d4";
		$inside_text_color = "white";
		$outside_text_color = "black";
		$graph_background = "lightblue";
		$border_color = "lightgray";
		break;
	}
	
	case 2:
	{
		$bar_color2 = "black"; //"#f35151";
		$inside_text_color = "white";
		$outside_text_color = "black";
		$graph_background = "#f3c6c6";
		$border_color = "lightgray";
		break;
	}
	
	case 3:
	{
		$bar_color2 = "green";
		$inside_text_color = "orange";
		$outside_text_color = "orange";
		$graph_background = "yellow";
		$border_color = "lightblue";
		break;
	}
}

	$sql = "SELECT UNIX_TIMESTAMP(date) as the_date, COALESCE(SUM($item),0) FROM flights, planes WHERE flights.pilot_id={$user->pilot_id} AND $and_clause flights.plane_id=planes.plane_id GROUP BY the_date";
	$end_time = strtotime("now");
	$career = true;

if(!empty($year))		//create a query restricted to a certain year
{
	$start_sql = "SELECT COALESCE(SUM($item),0) FROM flights, planes WHERE YEAR(date) < '$year' AND $and_clause flights.plane_id=planes.plane_id AND flights.pilot_id={$user->pilot_id}";	//get the total when the year began
	$result = mysql_query($start_sql);
	$start_total = mysql_fetch_array($result);
	$start_total = $start_total[0];
	
	//print "-----$start_sql-----";
	
	$sql = "SELECT UNIX_TIMESTAMP(date) as the_date, COALESCE(SUM($item),0) FROM flights, planes WHERE YEAR(date) = '$year'
			AND flights.pilot_id={$user->pilot_id} AND $and_clause flights.plane_id=planes.plane_id GROUP BY the_date";
	
	$start_time = strtotime($year . "-01-01");		//graph starts jan 1 this year
	$end_time = strtotime($year + 1 . "-01-01");		//graph ends jan 1 the next year

	$career = false;
	
	$year_title = " For $year";
}
	
if(!empty($month))
{
	$start_sql = "SELECT COALESCE(SUM($item),0) FROM flights WHERE DATE_FORMAT(date,'%Y%m') < '$year$month' AND pilot_id={$user->pilot_id}";	//get the total when the year began
	$result = mysql_query($start_sql);
	$start_total = mysql_fetch_array($result);
	$start_total = $start_total[0];
	
	//$start_total=0;
	
	//print "-----$start_sql-----";
	
	$sql = "SELECT UNIX_TIMESTAMP(date) as the_date, COALESCE(SUM($item),0) FROM flights WHERE DATE_FORMAT(date,'%Y%m') = '$year$month' AND pilot_id={$user->pilot_id} GROUP BY the_date";
	//$sql = "SELECT date as the_date, COALESCE(SUM($item),0) FROM flights WHERE DATE_FORMAT(date,'%Y%m') = '$year$month' AND pilot_id={$user->pilot_id} GROUP BY the_date";
	
	
	$max_day = date("t", strtotime($year . "-" . $month . "-04"));
	
	
	$start_time = strtotime($year . "-" . $month . "-01");				//graph starts the first of the month
	$end_time = strtotime($year . "-" . $month . "-" . $max_day);			//graph ends the first of the next month
	
	$date_on_ticks = true;				//add the date to the tick marks
	$career = false;
}

###############################################################

//print $sql;


	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
		
	if(empty($result))
		die;
		
	$data = array();
	$time = array();
	
	$time[] = $rows[0];
	$data[] = $rows[1];
	
	if(empty($year) && empty($month))		//if its the career ("all") graph, then start at the time of the first flight
		$start = $rows[0];
	
	while($rows = mysql_fetch_array($result))
	{
		$data[] = $rows[1];
		$time[] = $rows[0];
	}
	
############################################################### make into an accumulated data plot, and create a variable containing the last value

	//$data = array(2,3,4,5);
	
	if(!$career)							//if it's a restricted graph... i.e NOT a career graph
	{
		$acc_data[0] = $start_total;				//make the first value the previous total
		array_unshift($data, "0");				//add to the first spot, a value of 0...
		array_unshift($time, $start_time);			//and the time, which is the end of the graph
	}
	else	$acc_data = array();					//create an empty array
	
	$i = 1;
	foreach($data as $index => $value)
	{
		$acc_data[$index] = $value + $acc_data[$index-1];
		$i++;
	}
	
	//var_dump($time);
	//var_dump($data);
	//var_dump($acc_data);
	
	if(!$career)				//make an extra data point at the begining and end of the set to continue the line all the way to the ends of the graph
	{
		foreach($acc_data as $value)
			$new_acc_data[] = $value + $start_total;
		
		if($year != date("Y", strtotime("this year")))
			$end_value = end($new_acc_data);		//the last value in the array, but dont do it if the year is this year (which would extend it into the future)
	}	
	else
		$new_acc_data = $acc_data;
		
	if($career || $year == date("Y"))
	{
		$time[] = "" . strtotime("now");						//add a new point for right now
		$new_acc_data[] = $new_acc_data[count($new_acc_data)-1];		//duplicate the last value
		
		
	}
	else
	{
		array_push($new_acc_data, $end_value);			//add a new point at the begining of the data array
		array_push($time, $end_time);				//add a new point at the begining of the time array
	}
	
		
	//var_dump($new_acc_data);
	//var_dump($time);
	//print $start_time . " - " . $end_time;
	
###############################################################
	
	// Size of graph

	$top = 30;
	$bottom = 90;
	$left = 80;
	$right = 30;

	$width = 800;
	$height= 600;
	
###############################################################

$graph = new Graph($width,$height);

if($career)
	$graph->SetScale("intlin",0,0,0,0);		//set the scale
else
	$graph->SetScale("intlin",0,0,$start_time,$end_time);		//set the scale

if($is_time)
	$graph->title->Set("$title Flight Time Accumulation$year_title");	//title text - flight time
else	$graph->title->Set("$title Accumulation$year_title");			//title text - landings

$graph->title->SetFont(FF_VERA, FS_BOLD,16);			//title font

$graph->SetMargin($left,$right,$top,$bottom);			//margins

$graph->yaxis->SetFont(FF_VERA);				//y-axis font
$graph->yaxis->HideFirstTickLabel();				//don't show the first label

if($is_time)
	$graph->yaxis->title->Set($title . " Flight Hours");	//y-axis label - flight time
else	$graph->yaxis->title->Set($title);			//y-axis label - landings

$graph->yaxis->title->SetFont(FF_VERA);				//y-axis font
$graph->yaxis->SetTitleMargin(50);				//move the label to the side so it doesn't overlap

$graph->ygrid->SetWeight(1);					//grid line 1 pixel
$graph->ygrid->Show();						//show y-grid lines

$graph->xaxis->SetFont(FF_VERA);				//x-axis font
$graph->xaxis->SetLabelAngle(45);				//rotate labels 45 degrees


$graph->xgrid->SetWeight(1);					//x-grid lines 1 pixel
$graph->xgrid->Show();						//show X-grid lines

if(!$career)						//change the x-labels from unix timestamps, to human readable dates
{	//restricted
	$graph->xaxis->SetLabelFormatCallback('time_tick_label_with_date');
	$graph->xaxis->scale->ticks->Set(2628000,648000);			//ticks every month, minor ticks every week
}
else	//unrestricted - entire career
{
	$graph->xaxis->SetLabelFormatCallback('time_tick_label_no_date');
	//$graph->xaxis->scale->ticks->Set(15552000,7776000);		//mame major ticks every three months, and minor ticks every month.
}
###

$line = new LinePlot($new_acc_data, $time);
$line->SetStepStyle();

$line->SetColor($bar_color2);
$line->SetWeight(1);

###

$graph->Add($line);
$graph->SetColor($graph_background);
$graph->SetMarginColor($border_color);
$graph->SetShadow();

$graph->Stroke();
?>
