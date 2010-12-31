<?php

include "classes/map_class.php";

$user = new map(true);

if($_GET['all'] == "true")			//if "all" is set, then show the page, no matter what, even if the user is not logged in.
{
	$user->all = $page_auth = "sec";
	$all = "&all=true";
}
else
	$page_auth = $user->auth;

$webpage = new page("Map", 1, $page_auth);

################################################

if(empty($_GET['type']) && $user->all)		//"all" is set, then no matter what the map type is "markers"
	$map_type = "markers";

elseif(empty($_GET['type']))			//all is not set, and no type is specefied, then it's cat_line
	$map_type = "cat_line";

else	$map_type = $_GET['type'];		//otherwise, get variable is the map type

###############################################################################################################

$display = <<<EOF
			<script src="map_data.php?type=$map_type{$all}{$user->proper_a}" type="text/javascript" charset="UTF-8"></script>
			<script src="javascripts/map_$map_type.js" type="text/javascript"></script>
					
			<div id="content" style="margin:0;padding:0;height:100%">

				<div id="map_canvas">
				</div>
			</div>
			
EOF;

###############################################################

	$display .= "	<div id=\"message_box\">
				<div id=\"open\" onclick=\"open_missing_planes()\">&gt;&gt;</div>
				<div id=\"missing_planes\"></div>
				<div id=\"close\" onclick=\"close_missing_planes()\">&lt;&lt;</div>
			</div>\n\n";
				
###############################################################

	$display .= "	</body>
		</html>";


	$webpage->add_content($display);

###############################################################

$html = $webpage->output();

echo $html;
?>
