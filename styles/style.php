<?php
header("Content-type: text/css");

if($_GET['s'] == 1)
{
	$background  = 			"#cce4eb";
	$dark_color  = 			"#665b1e";
	$light_color = 			"#5dc7e9";
	$light_color2 = 		"#11566d";

	$fonts = 			"\"Bitstream Vera Sans\", sans-serif";

	//////////////////////////

	$h1 =				$dark_color;
	$h2 = 				$light_color2;
	$link = 			$light_color2;
	$active_link =			"yellow";

	$login_background = 		$light_color;

	$central_div_background = 	"white";
	$central_div_border = 		"1px solid black";
	
	/////
	
	$legend_text_color =		$light_color2;

	/////

	$active_navbar_background = 	$light_color;
	$navbar_background = 		$dark_color;
	$navbar_text = 			"white";
	$navbar_hover = 		"silver";
	$logged_in_as = 		"black";
	
	/////

	$news_background = 		$background;
	$news_border = 			"1px solid black";
	
	/////

	$textbox_border = 		"1px solid black";
	$button_border = 		"1px solid black";
	$dropdown_background = 		"#EEEEEE";
	$textbox_background =		"white";
	
	/////

	$popup_border = 		"3px double black";
	$popup_background = 		"white";
	$popup_dragbar = 		$light_color;
	$events_background = 		$background;
	$events_border = 		"1px solid black";
	$non_flying_border = 		"1px solid #999999 !important";

	/////

	$logbook_margins = 		$background;
	$logbook_border =		"1px solid black";
	$logbook_td_border = 		"1px solid silver";
	
	$logbook_odd = 			"#e2eeee";
	$logbook_even = 		"#FFFFFF";
	
	$logbook_overall_background = 	"#E0FFFF";
	$logbook_overall_border = 	"1px solid darkgray";
	
	$logbook_header_background = 	"#E0FFFF";
	$logbook_header_border =	"1px solid darkgray";
	
	$logbook_flying_event =		"darkred";
	$logbook_text_color = 		"black";

	/////

	$currency_good = 		$light_color;
	$currency_bad = 		"#AAAAAA";
	$currency_almost =		"#f184a3";
	
	$atp_qualified =		"green";
	$atp_not_qualified =		"#8B0000";
	
	$p135_msg_border =		"1px solid black";
	
	$atp_border = 			"1px solid black";
	$atp_background =		"#F5F5F5";

	/////

	$planes_background =		$background;
	$planes_border =		"1px solid black";
	$planes_text_color = 		"black";
	
	$planes_select_background =	$background;

	$planes_textbox_border =	"1px solid #ACCDDD";
	$planes_hidden_border =		"1px solid $background";
	
	$planes_textbox_hover_bg =	$light_color;
	$planes_textbox_hover_border =	"1px solid gray";
	
	$planes_tailnumber_text = 	$dark_color;
	
	/////
	
	$mass_checkbox_title_text =	"black";
	
	$mass_nongrayed_textbox_border ="1px solid black";
	$mass_grayed_textbox_border = 	"1px solid silver";
	
	$mass_grayed_textbox_text =	"#BABABA";
	
	$mass_color2 =			$logbook_even;
	$mass_color2_textbox =		"#DDDFFF";
	
	$mass_color1 =			$logbook_odd;
	$mass_color1_textbox =		"#DDDDDD";
	
	//
	
	$stats_career_border = 		"3px double black";
	$stats_select_box_border = 	"1px solid black";
	
	//
	
	$import_instructions =		"white";
	
	//
	
	$map_popup_font =		"Bitstream Vera Sans, sans-serif";
	
}
elseif($_GET['s'] == 2)
{
	$background  = 			"#ebcccc";
	$dark_color  = 			"#756464";
	$light_color = 			"#e44d4d";
	$light_color2 = 		"#11566d";

	$fonts = 			"\"Bitstream Vera Sans\", sans-serif";

	//////////////////////////

	$h1 =				$dark_color;
	$h2 = 				$light_color2;
	$link = 			$light_color2;
	$active_link =			"yellow";

	$login_background = 		$light_color;

	$central_div_background = 	"white";
	$central_div_border = 		"1px solid black";
	
	/////
	
	$legend_text_color =		$light_color2;

	/////

	$active_navbar_background = 	$light_color;
	$navbar_background = 		$dark_color;
	$navbar_text = 			"white";
	$navbar_hover = 		"silver";
	$logged_in_as = 		"black";
	
	/////

	$news_background = 		$background;
	$news_border = 			"1px solid black";
	
	/////

	$textbox_border = 		"1px solid black";
	$button_border = 		"1px solid black";
	$dropdown_background = 		"#EEEEEE";
	$textbox_background =		"white";
	
	/////

	$popup_border = 		"3px double black";
	$popup_background = 		"white";
	$popup_dragbar = 		$light_color;
	$events_background = 		$background;
	$events_border = 		"1px solid black";
	$non_flying_border = 		"1px solid #999999 !important";

	/////

	$logbook_margins = 		$background;
	$logbook_border =		"1px solid black";
	$logbook_td_border = 		"1px solid silver";
	
	$logbook_odd = 			"#eee2e4";
	$logbook_even = 		"#FFFFFF";
	
	$logbook_overall_background = 	"#fff9e6";
	$logbook_overall_border = 	"1px solid darkgray";
	
	$logbook_header_background = 	"#fff9e6";
	$logbook_header_border =	"1px solid darkgray";
	
	$logbook_flying_event =		"darkred";
	$logbook_text_color = 		"black";

	/////

	$currency_good = 		"#ce6b6b";
	$currency_bad = 		"#AAAAAA";
	$currency_almost =		"yellow";
	
	$atp_qualified =		"green";
	$atp_not_qualified =		"#8B0000";
	
	$p135_msg_border =		"1px solid black";
	
	$atp_border = 			"1px solid black";
	$atp_background =		"#F5F5F5";

	/////

	$planes_background =		$background;
	$planes_border =		"1px solid black";
	$planes_text_color = 		"black";
	
	$planes_select_background =	$background;

	$planes_textbox_border =	"1px solid #ACCDDD";
	$planes_hidden_border =		"1px solid $background";
	
	$planes_textbox_hover_bg =	$light_color;
	$planes_textbox_hover_border =	"1px solid gray";
	
	$planes_tailnumber_text = 	$dark_color;
	
	/////
	
	$mass_checkbox_title_text =	"black";
	
	$mass_nongrayed_textbox_border ="1px solid black";
	$mass_grayed_textbox_border = 	"1px solid silver";
	
	$mass_grayed_textbox_text =	"#BABABA";
	
	$mass_color2 =			$logbook_even;
	$mass_color2_textbox =		"#FFDFDD";
	
	$mass_color1 =			$logbook_odd;
	$mass_color1_textbox =		"#DDDDDD";
	
	//
	
	$stats_career_border = 		"3px double black";
	$stats_select_box_border = 	"1px solid black";
	
	//
	
	$import_instructions =		"white";
	
	//
	
	$map_popup_font =		"Bitstream Vera Sans, sans-serif";
}

elseif($_GET['s'] == 3)
{
	$background  = 			"lightyellow url('stupid/background-3.gif')";
	$dark_color  = 			"green";
	$light_color = 			"pink";
	$light_color2 = 		"purple";

	$fonts = 			"\"Comic Sans MS\", \"Times New Roman\", serif";

	//////////////////////////

	$h1 =				$dark_color;
	$h2 = 				"black";
	$link = 			$light_color2;
	$active_link =			"lightblue";

	$login_background = 		$light_color;

	$central_div_background = 	"#DD12DE url(stupid/plaid-orange.png)";
	$central_div_border = 		"2px dotted cyan";
	
	/////
	
	$legend_text_color =		$light_color2;

	/////

	$active_navbar_background = 	$light_color;
	$navbar_background = 		$dark_color;
	$navbar_text = 			"white";
	$navbar_hover = 		"blue";
	$logged_in_as = 		"black";
	
	/////

	$news_background = 		"cyan";
	$news_border = 			"3px dashed black";
	
	/////

	$textbox_border = 		"2px dotted #25E431";
	$button_border = 		"1px dashed red";
	$dropdown_background = 		"#EEFF00";
	$textbox_background =		"lightgreen";
	
	/////

	$popup_border = 		"8px double black";
	$popup_background = 		"yellow";
	$popup_dragbar = 		$light_color;
	$events_background = 		$background;
	$events_border = 		"6px groove black";
	$non_flying_border = 		"1px solid brown !important";

	/////

	$logbook_margins = 		$background;
	$logbook_border =		"1px solid black";
	$logbook_td_border = 		"1px solid silver";
	
	$logbook_odd = 			"darkgreen";
	$logbook_even = 		"black";
	
	$logbook_overall_background = 	"darkgray";
	$logbook_overall_border = 	"1px solid red";
	
	$logbook_header_background = 	"darkgray";
	$logbook_header_border =	"1px solid red";
	
	$logbook_flying_event =		"darkred";
	$logbook_text_color = 		"yellow";

	/////

	$currency_good = 		"#ce6b6b url(stupid/good.jpg)";
	$currency_bad = 		"#AAAAAA url(stupid/bad.jpg)";
	$currency_almost =		"#f184a3";
	
	$atp_qualified =		"green";
	$atp_not_qualified =		"#8B0000";
	
	$p135_msg_border =		"1px solid black";
	
	$atp_border = 			"1px solid black";
	$atp_background =		"url(stupid/plaid-purple.png)";

	/////

	$planes_background =		$background;
	$planes_border =		"1px solid black";
	$planes_text_color = 		"black";
	
	$planes_select_background =	$background;

	$planes_textbox_border =	"1px solid #ACCDDD";
	$planes_hidden_border =		"1px solid $background";
	
	$planes_textbox_hover_bg =	$light_color;
	$planes_textbox_hover_border =	"1px solid gray";
	
	$planes_tailnumber_text = 	$dark_color;
	
	/////
	
	$mass_checkbox_title_text =	"black";
	
	$mass_nongrayed_textbox_border ="1px solid black";
	$mass_grayed_textbox_border = 	"1px solid silver";
	
	$mass_grayed_textbox_text =	"#BABABA";
	
	$mass_color2 =			$logbook_even;
	$mass_color2_textbox =		"#FFDFDD";
	
	$mass_color1 =			$logbook_odd;
	$mass_color1_textbox =		"#DDDDDD";
	
	//
	
	$stats_career_border = 		"3px double lime";
	$stats_select_box_border = 	"1px dashed black";
	
	//
	
	$import_instructions =		"green";
	
	//
	
	$map_popup_font =		"Bitstream Vera Sans, sans-serif";
}
?>


.captain_col			{display: table-cell;}
.date_col			{display: table-cell;white-space: nowrap}
.fo_col				{display: table-cell;}
.flight_number_col		{display: table-cell;}
.student_col			{display: table-cell;}
.instructor_col			{display: table-cell;}
.night_col			{display: table-cell;white-space: nowrap}
.xc_col				{display: table-cell;white-space: nowrap}
.dual_given_col			{display: table-cell;white-space: nowrap}
.dual_recieved_col		{display: table-cell;white-space: nowrap}
.sic_col			{display: table-cell;white-space: nowrap}
.pic_col			{display: table-cell;white-space: nowrap}
.solo_col			{display: table-cell;white-space: nowrap}
.sim_col			{display: table-cell;white-space: nowrap}



html, #holder			{min-height: 100%;
				 width:100%; height: 100%;}
				 
html>body, html>body #holder	{height: auto;}

body				{background: <?echo $background ?>;
				 margin: 15px;
				 margin-top:0px;
				 padding:0;
				 font-size: small;
				 font-family: <?echo $fonts ?>;
				 min-height: 100%;
				 height: 100%;}
				 
#holder				{position: absolute;
				 top: 0;
				 left: 0;
				 overflow: hidden;}
				 
#header				{margin:15px;
				 overflow: hidden;}
				 
#content			{margin-left: 0px;
				 padding: 15px;
				 padding-top: 0px;
				 height: auto;}
				 
#footer				{clear: both;
				 height: 15px;
				 bottom: 0;
				 left: 0;
				 text-align:center;
				 font-size: x-small;
				 border: 0px solid black;
				 width: 100%;}
				 
blockquote			{font-style: italic}



/* Change back in everything except Opera 5 and 6, still hiding from Mac IE5 */
/* \*/
 head:first-child+body div#footer		{position: absolute;} 
/* */

/*--------------------------------------------------------------------------------------------------------------*/

input[type="button"]:hover	{cursor: pointer}
input[type="submit"]:hover	{cursor: pointer}
button:hover			{cursor: pointer}

input[type="submit"]		{border: <? echo $button_border ?>}
input[type="button"]		{border: <? echo $button_border ?>}
button				{border: <? echo $button_border ?>}

input[type="password"]		{border: <? echo $textbox_border ?>;
				 background: <? echo $textbox_background ?>}
				 
input[type="text"]		{border: <? echo $textbox_border ?>;
				 background: <? echo $textbox_background ?>}

textarea			{border: <? echo $textbox_border ?>;
				 font-family: <? echo $fonts ?>;
				 background: <? echo $textbox_background ?>}

select				{border: <? echo $textbox_border ?>;
				 background: <? echo $dropdown_background ?>}
				 
A				{color: <? echo $link ?>}

H1				{text-align: center;
				 font-weight: bold;
				 color: <? echo $h1 ?>;
				 font-size: x-large;}

H2				{font-weight: bold;
				 color: <? echo $h2 ?>;
				 font-size: large}
				 
.dragme				{cursor: move }
				 
.fake_anchor			{cursor: pointer;
				 color: <? echo $link ?>;
				 text-decoration: underline}


.nav_bar			{background: <? echo $navbar_background ?>;
				 border: 0px;
				 width: 100%}
				 
.nav_bar_logged			{width: 10%}
#nav_bar_non tr			{width: 33%}

.nav_bar A			{color: <? echo $navbar_text ?>;
				 font-weight: bold;
				 text-decoration: none}
.nav_bar A:hover		{color: <? echo $navbar_hover ?>;
				 font-weight: bold;
				 text-decoration: underline}
				 				 

				 

				 
.empty_message			{font-size: large;
				 margin:20px;
				 text-align: center}
				 
.login				{border: 1px solid black;
				 font-size: small;
				 padding-bottom:15px;
				 background:<? echo $login_background ?>;
				 text-align:center;
				 width:40em;
				 margin-right:auto;
				 margin-left:auto;
				 margin-bottom:20px;}
				 
hr				{display:none}

.news_box			{border: 1px solid black;
				 width:50em;
				 margin-right:auto;
				 margin-left:auto;
				 margin-bottom: 15px;
				 padding:5px;
				 background: #cce4eb}

.news_box p			{font-size: medium}

.news_box big			{font-size: x-large;
				 font-weight: bold}
				 
.news_box small			{font-size: small}
				 
.top_nav_selected		{background: <? echo $active_navbar_background ?>}

#advert_box			{float:right;
				 height:67px;
				 color: <? echo $logged_in_as ?>;
				 text-align: right}
				 
.error_page			{font-size: xx-large;
				 font-weight: bold;}

/*------------------------------central divs----------------------------------------*/

.central_div			{background: <? echo $central_div_background ?>;
				 padding: 1em;
				 overflow:hidden;
				 width: 73em;
				 margin-left: auto;
				 margin-right: auto;
				 text-align: center;
				 border: <? echo $central_div_border ?>}
				 
.central_prefs input		{width:35%}
.central_prefs select		{width:35%}

.logbook_central_div		{width:100%;
				 padding:0}
				 
.import_central_div		{width:100%;
				 padding:0}

/*---------------------------------------------------- new entry popup--------------*/

#new_entry_popup		{width:50em;				/* THE WHOLE POPUP WINDOW ITSELF */
				 position:absolute;
				 display:none;
				 left:300px;
				 top:500px;
				 background: <? echo $popup_background ?>;
				 border: <? echo $popup_border ?>;
				 text-align:center;
				 padding:1em;
				 padding-top:2.5em;}

		 
.new_entry_popup_dragbar	{width:100%;				/* THE PART OF THE POPUP WHERE YOU DRAG */
				 border-bottom:1px solid black;
				 background: <? echo $popup_dragbar ?>;
				 position:absolute;
				 top:0;
				 right:0}
				 
.new_entry_popup_dragbar big	{font-weight:bold}			/* POPUP WINDOW TITLE TEXT */
				 
.inner_title			{float:left;
				 width:50em;}
				 
.close_button			{margin:1px;				/* THE "X" AT THE CORNER OF THE WINDOW */
				 padding:0;
				 float:right;
				 color:black !important;
				 width:1em;
				 text-align:center;
				 text-decoration:none;
				 border:1px solid black;
				 font-weight:bold}


.title_entry_left		{float:right;				/*THE LEFT SIDE DIVS THAT ALIGN THE TEXTBOXESDF*/
				 height:25px;
				 padding-right:10px;
				 text-align:right;
				 line-height:25px}
				 
.entry_entry_left		{float:right;
				 width:13.5em;
				 height:25px}
				 
.title_entry_right		{float:left;
				 height:25px;
				 text-align:left;
				 line-height:25px}

.entry_entry_right		{float:left;
				 text-align:center;
				 height:25px}

.long_textbox			{width:150px;
				 font-size:small;
				 font-family: <? echo $fonts ?>}
				 
.short_textbox			{width:3em;
				 font-size:small;
				 font-family: <? echo $fonts ?>}

.plane_dropdown			{width:150px;}

.keep_vertical			{overflow:hidden;
				 width:100%;
				 border:0px solid black}

.events_div			{float:right;
				 background: <? echo $events_background ?>;
				 width:100%;
				 border: <? echo $events_border ?>;
				 margin-top:5px;
				 margin-bottom:5px}
				 
.landing_boxes			{float:left;
				 width:100%;
				 margin-top:5px;
				 padding-top:3px;
				 padding-bottom:3px}

.new_entry_left			{overflow:hidden;
				 float:left;
				 width:59%}

.new_entry_right		{float:right;
				 width:37%}

.new_entry_right input[type="button"] {margin-right:5px}

/*-----------------------------------------------------logbook view--------------------*/

.logbook_inner			{background: <? echo $logbook_margins ?>;
				 border: <? echo $logbook_border ?>;
				 margin-bottom: 10px;
				 margin-left: 10px;
				 margin-right: 10px;
				 margin-top: 0}
				 
.logbook_table			{background: <? echo $logbook_margins ?>;
				 color: <? echo $logbook_text_color ?>;
				 width:100%}

.logbook_table td		{border: <? echo $logbook_td_border ?>;
				 border-spacing: 0;
				 font-size: x-small;
				 text-align: center}
				 
.center_page_index		{font-size: small;
				 font-weight:bold;
				 text-align:center}

.last_page_index		{font-size: small;
				 font-weight:bold;
				 text-align:right}
				 
.first_page_index		{font-size: small;
				 font-weight:bold;
				 text-align:left}

.odd				{background-color: <? echo $logbook_odd ?>}

.even				{background-color: <? echo $logbook_even ?>}

.overall_row td			{background-color: <? echo $logbook_overall_background ?>;
				 border: <? echo $logbook_overall_border ?>}

.header_row td			{background-color: <? echo $logbook_header_background ?>;
				 border: <? echo $logbook_header_border ?>}

.page_row td			{background-color: <? echo $logbook_overall_background ?>;
				 border: <? echo $logbook_overall_border ?>}
				 
.no_plane_alert			{width:150px;
				 background-color:#ADDFFF;
				 margin-right:auto;
				 margin-left:auto}
				 
.flying_event			{color:<? echo $logbook_flying_event ?>}

.non_flying_event		{color:black;font-weight:bold;
				 border: <? echo $non_flying_border ?>}

/*------------------------------------------------- preferences.php--------------------*/

.sections			{font-size: x-small;
				 padding: 10px}

.sections legend		{font-size: large;
				 font-weight: bold;
				 color: <? echo $legend_text_color ?>}

.pref_title			{font-size: small;
				 height:25px;
				 float:left;
				 text-align: right;
				 line-height: 25px;
				 width: 48%;
				 padding: 0}
				 
.pref_form			{font-size: x-small;height:25px;
				 float:right;
				 text-align: left;
				 width: 48%}
				 
.prefs_submit_button		{width:10em !important}
				 
.currency_day			{font-size: x-small;
				 height:25px;
				 float:left;
				 text-align: center;
				 line-height: 25px;
				 width: 5%;
				 padding: 0px}


.currency_night			{font-size: x-small;
				 height:25px;
				 float:left;
				 text-align: center;
				 line-height: 25px;
				 width: 5%}
				 
.currency_dummy			{font-size: x-small;
				 height:25px;
				 float:left;
				 text-align: left;
				 width: 35%}
				 


/*------------------------------------------ planes.php-------------------------------*/

.plane_box input[type="text"] 		{width: 9em;
					 background: <? echo $planes_background ?>;
					 margin:2px;
					 text-align: center;
					 font-weight:bold;
					 border: <? echo $planes_hidden_border ?>}
				 
.plane_box input[type="text"]:hover 	{width: 9em;
					 background: <? echo $planes_textbox_hover_bg ?>;
					 margin:2px;
				 	 border: <? echo $planes_textbox_hover_border ?>}
				 	 
.plane_box input[type="text"]:focus 	{width: 9em;
					 background: <? echo $planes_textbox_hover_bg ?>;
					 margin:2px;
				 	 border: <? echo $planes_textbox_hover_border ?>}
					 
.planes_tail_number		{font-size: x-large;
				 text-align:left !important;
				 border: <? echo $planes_textbox_border ?>;
				 width: 6.5em !important;
				 font-weight:bold;
				 color: <? echo $planes_tailnumber_text ?>;
				 background: <? echo $planes_background ?>}
				 					 
.planes_tail_number:hover:focus	{border: <? echo $planes_textbox_hover_border ?>;
				 text-align:left;}
				 				 
.plane_box input[type="submit"]	{margin:2px;}
				 
.plane_box select		{width: 200px;
				 background: <? echo $planes_select_background ?>;
				 margin:2px;
				 margin-left:10px;}
				 

				 

				 
.planes_tags			{width:90% !important;
				 text-align: left !important;
				 font-weight:normal !important}

.planes_manufacturer		{width:100%;
				 font-size:large;
				 text-align:left}

.plane_box			{overflow:hidden;
				 color: <? echo $planes_text_color ?>;
				 text-align:left;
				 padding:5px;
				 font-size: x-small;
				 background: <? echo $planes_background ?>;
				 border: <? echo $planes_border ?>;
				 margin-top: 1em;
				 margin-bottom: 1em;}
				

/*---------------------------------------mass entry section---------------------------*/



#mass_entry_div			{margin-left: auto;
				 margin-right: auto;
				 padding: 15px;
				 background: white;
				 border: 1px #111111 solid;}

/*---------------------------------------import section-------------------------------*/

.import-announce		{background: white;
				 text-align: center;
				 font-size: large;
				 width: 80%;
				 border: 1px solid black;
				 margin-left: auto;
				 margin-right: auto;
				 margin-top: 10px;
				 margin-bottom: 10px;
				 padding: 10px}
				 
.alert				{color: red;
				 font-weight: bold}
				 
.import_instructions		{text-align:left;
				 background: <? echo $import_instructions ?>;
				 border:0px solid black;
				 padding: 20px;
				 padding-top: 5px;}
				 
.import_records			{width:80%;
				 border:1px solid black;
				 background: white;
				 padding:5px;
				 margin:10px;
				 margin-left: auto;
				 margin-right:auto}

.import_planes_table		{border: 1px solid black;
				 width:80%;
				 margin-left: auto;
				 margin-right: auto;}

.import_planes_table td		{border:1px outset black}
				 
/*---------------------------------------stats page-----------------------------------*/
				 
.small_select_box		{width:100%;
				 padding:0px;
				 display: none;}
				 
.big_select_box			{display:none;}
				 
.big_time_box			{width:90%;
				 padding:5px;
				 border: <? echo $stats_select_box_border ?>;
				 margin-right:auto;
				 margin-left:auto;
				 margin-bottom:10px;
				 font-size: large}

.big_time_box strong		{text-decoration: underline}

.career_totals			{font-size: large;
				 margin-right:auto;
				 margin-left:auto;
				 border:<? echo $stats_career_border ?>;
				 margin-top:20px}

.career_totals td		{width:30%}

.eighty710_table		{font-size: x-small;
				 border:1px outset black;
				 empty-cells: show;}
				 
.eighty710_table td		{width: 5.88%;
				 border:1px inset black}
				 
.eighty710_blanked		{background-color: silver}

/*--------------------------------------currency section------------------------------*/

.current			{border:1px solid black;
				 background: <? echo $currency_good ?>;
				 float:left;
				 font-size:small;
				 margin-bottom:10px;
				 margin-right:10px;
				 padding:3px;
				 width:18em;
				 height:6em}
				 
.current small			{font-size:x-small}
				 
.almost_expired			{background: <? echo $currency_almost ?>}

.not_current			{background: <? echo $currency_bad ?>;}

.landings_currency_container	{width: 100%;
				 margin: 10px;
				 margin-top:0;
				 margin-bottom:0;
				 float:left}
				 
.times_currency_container	{width:100%;
				 margin: 10px;
				 margin-top:0;
				 float:left}


.div_135			{border: <? echo $atp_border ?>;
				 height:220px;
				 overflow:hidden;
				 float:left;
				 background: <? echo $atp_background ?>;
				 margin-left:0px;
				 margin-right: 10px;
				 padding:3px;
				 width:33em}
				 
.div_ATP			{border: <? echo $atp_border ?>;
				 height:220px;
				 overflow:hidden;
				 float:left;
				 background: <? echo $atp_background ?>;
				 margin-left:0px;
				 padding:3px;
				 width:38em;}
				 
.landings_currency_container big{text-decoration: underline;
				 font-size: large}
				 
.div_135 table			{margin-left:auto;
				 font-size:small;
				 width:100%;
				 margin-right:auto;
				 margin-top:10px}
				 
.div_135 table td		{text-align:right}

.top_135			{text-align:center !important;
				 font-size: larger;
				 border: <? echo $p135_msg_border ?>;
				 font-weight:bold}
			
.qualified			{color: <? echo $atp_qualified ?>}

.not_qualified			{color: <? echo $atp_not_qualified ?>}

.div_ATP table			{margin-left:auto;
				 border:0px solid black;
				 font-size:small;
				 width:50%;
				 margin-right:auto;
				 margin-top:10px}
				 
.div_ATP table td		{text-align:right}

/*--------------------------------------mass entry section----------------------------*/


.mass_grayed			{color: <? echo $mass_grayed_textbox_text ?> !important;
				 border: <? echo $mass_grayed_textbox_border ?> !important;
				 font-weight: normal !important;}


.mass_checkmark_boxes td	{color: <? echo $mass_checkbox_title_text ?>}

input.mass_numbers		{width:2.5em;
				 font-weight: bold;
				 font-size:small;
				 border: <? echo $mass_nongrayed_textbox_border ?>;}

input.mass_small_numbers	{width:1.5em; font-weight: bold;
				 font-size:small;
				 border: <? echo $mass_nongrayed_textbox_border ?>;}
				 
input.mass_names 		{width:97%; font-weight: bold;
				 font-size:x-small;
				 border: <? echo $mass_nongrayed_textbox_border ?>;
				 margin:2px}
				 
.non_remarks			{width:98.5%;
				 font-size:x-small;
				 border: <? echo $mass_grayed_textbox_border ?>;
				 margin:2px;
				 font-weight: bold}

.big_mass_outer_div		{margin:10px !important}

#mass_entry_table		{font-size:x-small;
				 width:100%;
				 padding:0}


#mass_entry_table select	{width:7em !important;
				 font-size:x-small}

.mass_color_1			{background: <? echo $mass_color1 ?>}

.mass_color_1 input		{background: <? echo $mass_color1_textbox ?>}

.mass_color_2			{background: <? echo $mass_color2 ?>}
				 
.mass_color_2 input		{background: <? echo $mass_color2_textbox ?>}

