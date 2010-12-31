<?php

//include ("styles/jpgraph2-r1006/src/jpgraph.php");
//include ("styles/jpgraph2-r1006/src/jpgraph_bar.php");

include ("styles/jpgraph-2.3/src/jpgraph.php");
include ("styles/jpgraph-2.3/src/jpgraph_bar.php");

include "classes/stats_class.php";

$user = new stats(true);

if(!empty($_GET['sec']))
	$user->pilot_id = $_GET['sec'];

################################################################

$item = $_GET['type'];			//tailnumber, etc
$time = $_GET['time'];			//SIC, PIC, total, etc

switch($user->style)
{
	case 1:
	{
		$bar_color = "#517ff3";
		$bar_color2 = "#1148d4";
		$inside_text_color = "white";
		$outside_text_color = "black";
		$graph_background = "lightblue";
		$border_color = "lightgray";
		break;
	}
	
	case 2:
	{
		$bar_color = "#f35151";
		$bar_color2 = "#ff9b5d";
		$inside_text_color = "black";
		$outside_text_color = "black";
		$graph_background = "#f3c6c6";
		$border_color = "lightgray";
		break;
	}
	
	case 3:
	{
		$bar_color = "green";
		$bar_color2 = "yellow";
		$inside_text_color = "purple";
		$outside_text_color = "gray";
		$graph_background = "lightgreen";
		$border_color = "orange";
		break;
	}
}


switch($item)
{
	case "manufacturer":
		{$type_title .= "Manufacturers";break;}
		
	case "type":
		{$type_title .= "Types";break;}
		
	case "model":
		{$type_title .= "Models";break;}
		
	case "category_class":
		{$type_title .= "Category/Classes";break;}
		
	case "tail_number":
		{$type_title .= "Tail Numbers";break;}
		
	case "tags":
		{$type_title .= "Tags";break;}

	case "year":
		{$type_title .= "Year";break;}
		
	case "instructor":
		{$type_title .= "Instructor";break;}
		
	case "student":
		{$type_title .= "Student";break;}
		
	case "captain":
		{$type_title .= "Captain";break;}
		
	case "fo":
		{$type_title .= "First Officer";break;}
		
	case "month":
		{$type_title .= "Months";break;}
}

switch($time)
{
	case "total":
		{$sql = $user->get_total_list_sql($item); $time_title .= "Total";break;}
		
	case "dual_given":
		{$sql = $user->get_normal_list_sql($item, "dual_given"); $time_title .= "Dual Given";break;}
		
	case "dual_recieved":
		{$sql = $user->get_normal_list_sql($item, "dual_recieved"); $time_title .= "Dual Recieved";break;}
		
	case "night":
		{$sql = $user->get_normal_list_sql($item, "night"); $time_title .= "Night";break;}
		
	case "xc":
		{$sql = $user->get_normal_list_sql($item, "xc"); $time_title .= "Cross Country";break;}
		
	case "solo":
		{$sql = $user->get_normal_list_sql($item, "solo"); $time_title .= "Solo";break;}
		
	case "landings":
		{$sql = $user->get_composite_list_sql($item, "landings");
		 $time_title = "Landings";
		 $time_title1 .= "Night Landings";
		 $time_title2 .= "Day Landings";break;}
		 
	case "pic_sic":
		{$sql = $user->get_composite_list_sql($item, "pic/sic");
		 $time_title = "PIC / SIC Time";
		 $time_title1 .= "SIC";
		 $time_title2 .= "PIC";break;}
		 
	case "instrument":
		{$sql = $user->get_composite_list_sql($item, "instrument");
		 $time_title = "Instrument Time";
		 $time_title1 .= "Actual";
		 $time_title2 .= "Simulated";break;}
}

###############################################################

//print $sql;

if(!($time == "instrument" || $time == "pic_sic" || $time == "landings"))	//is not a composite plot
{
	$result = mysql_query($sql);
	
	//print $sql;
	
	$rows = mysql_fetch_array($result);
		
	if(empty($result))
		die;
		
	$data1 = array();
	$title = array();
	$maximum = $rows[1];
		
	$i=0;			//used to determine the height of the graph
	do
	{
		$i++;
		$display_total = $rows[1] == "" ? $rows[2] : $rows[1];
			
		if($item == "category_class")
			$display_title = switchout_category($rows[0]);
		else	$display_title = $rows[0];
	
		$data1[] = $display_total;
		$title[] = $display_title;
	
	}while($rows = mysql_fetch_array($result));
	
	// Size of graph

	$top = 30;
	$bottom = 30;
	$left = 180;
	$right = 30;
	
	$width = 800;
	$height= ($i * 25) + $bottom + $top;
	###########################################
	
	// Create a bar pot
	$bplot = new BarPlot($data1);
	$bplot->SetFillColor($bar_color);
	
	// Width
	$bplot->SetAbsWidth(20);
	
	// Show values next to the bars
	$bplot->value-> Show();
}
else										//is a composite plot
{
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	
	if(empty($result))
		die;

	$data1 = array();
	$data2 = array();
	
	$title = array();
	$maximum = $rows[3];
	
	$i=0;			//used to determine the height of the graph
	do
	{
		$i++;
		
		if($item == "category_class")
			$title[] = switchout_category($rows[0]);
		else	$title[] = $rows[0];
		
		$data1[] = $rows[1];
		$data2[] = $rows[2];		
	
	}while($rows = mysql_fetch_array($result));
	
	// Size of graph
	$top = 30;
	$bottom = 50;
	$left = 180;
	$right = 30;

	$width = 800;
	$height= ($i * 25) + $bottom + $top;

	##############################################
	
	// Create a bar pot
	$bplot1 = new BarPlot($data1);
	$bplot1->SetFillColor($bar_color);
	$bplot1->SetValuePos("center");
	
	// Show values next to the bars
	$bplot1->value->SetFont(FF_VERASERIF);
	$bplot1->value->SetColor($inside_text_color);
	$bplot1->value-> Show();
	
	$bplot1->SetLegend($time_title2);
	
	###############################################
	
	// Create a bar pot
	$bplot2 = new BarPlot($data2);
	$bplot2->SetFillColor($bar_color2);
	$bplot2->SetValuePos("center");
	
	// Show values next to the bars
	$bplot2->value->SetFont(FF_VERASERIF);
	$bplot2->value->SetColor($inside_text_color);
	$bplot2->value->Show();
	
	$bplot2->SetLegend($time_title1);
	
	###############################################
	
	$bplot  = new AccBarPlot (array($bplot1 ,$bplot2));
	
	$bplot->SetValuePos("center");
	
	//$bplot->value-> Show();				//uncomment this when the stupid bug gets fixed
}
		
###############################################################

//var_dump($title);

if($time == "landings" || $time == "approaches")			//remove the decimal places its its landings or approaches
{
	$bplot2->value-> SetFormat('%1.0f');
	$bplot1->value-> SetFormat('%1.0f');
	$bplot->value-> SetFormat('%1.0f');
}

$bplot->SetAbsWidth(20);
$bplot->value->SetFont(FF_VERASERIF);
$bplot->value->SetColor($outside_text_color);


// Set the basic parameters of the graph 
$graph = new Graph($width,$height,'auto');

$graph->SetScale("textlin", 0, $maximum + (0.08 * $maximum));	//scale the width of the graph appropriatly

$graph->yaxis->HideTicks(true,true);				//hide the grid, y-axis, and all the tick marks
$graph->xaxis->HideTicks(true,true);
$graph->xgrid->SetWeight(0); 
$graph->ygrid->SetWeight(0);
$graph->yaxis->Hide();

$graph->Set90AndMargin($left,$right,$top,$bottom);		//rotate graph 90 degrees
								
$graph->SetShadow();						//Nice shadow

$graph->xaxis->SetTickLabels($title);				//labels for the items (tailnumbers, students, etc)
$graph->xaxis->SetLabelAlign('right','center','right');
$graph->xaxis->SetFont(FF_VERASERIF, FS_BOLD);
								//title at the top
$graph->title->Set("$time_title by $type_title");
$graph->title->SetFont(FF_VERA, FS_BOLD,16);

$graph->legend->SetLayout(LEGEND_HOR);				//position the legend
$graph->legend->SetPos(0.7,0.90,'center','center'); 
/////////////////////////////////

$graph->Add($bplot);
$graph->SetColor($graph_background);
$graph->SetMarginColor($border_color);



$graph->Stroke();
?>
