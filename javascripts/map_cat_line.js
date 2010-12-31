	var map;
	var center_lat = "36.0000";
	var center_long = "-97.1419";
	var center_zoom = 4;
	
	var icon = new GIcon;
	icon.image = "http://maps.google.com/mapfiles/kml/pal4/icon49.png";
	icon.iconSize = new GSize(32, 32);
	icon.iconAnchor = new GPoint(16, 32);
	icon.infoWindowAnchor = new GPoint(16, 16);

function add_line(start_lat, start_long, end_lat, end_long, color)
{
	var polyOptions = {geodesic:true};
	var line = new GPolyline([new GLatLng(start_lat, start_long), new GLatLng(end_lat, end_long)], color, 2, 0.5, polyOptions);
	
	map.addOverlay(line);
}

function initialize()
{
	if (GBrowserIsCompatible())
	{
		map = new GMap2(document.getElementById("map_canvas"));
		var polyOptions = {geodesic:true};
				
		map.setCenter(new GLatLng(center_lat, center_long), center_zoom);
		
		map.addMapType(G_PHYSICAL_MAP);
		map.setMapType(G_PHYSICAL_MAP);
		
		map.addControl(new GScaleControl());
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.enableContinuousZoom();
		map.enableScrollWheelZoom();
		
		for(id in lines)
		{
			add_line(lines[id].start_lat, lines[id].start_long, lines[id].end_lat, lines[id].end_long, lines[id].color);
		}
		
		if(missing_planes[0] != "no_failed_airports")
		{
			var disp = "<strong>Invalid identifiers: </strong>";
		
			missing_div = document.getElementById("missing_planes");
			
			disp += missing_planes.join(", ");

			missing_div.innerHTML = disp;
		}
		else
		{
			var disp = "<strong>All Identifiers found!</strong>";
		
			missing_div = document.getElementById("missing_planes");
			
			missing_div.innerHTML = disp;
		}
	}
}

function open_missing_planes()
{
	document.getElementById('missing_planes').style.display = 'block';
	
	document.getElementById('message_box').style.width = '50%';
	document.getElementById('message_box').style.background = 'white';
	document.getElementById('message_box').style.border = '1px solid black';
	
	document.getElementById('open').style.display = 'none';
	document.getElementById('close').style.display = 'block';
}

function close_missing_planes()
{
	document.getElementById('missing_planes').style.display = 'none';
	
	document.getElementById('message_box').style.width = '';
	document.getElementById('message_box').style.background = 'none';
	document.getElementById('message_box').style.border = '0px solid black';
	
	document.getElementById('open').style.display = 'block';
	document.getElementById('close').style.display = 'none';
}

window.onload = initialize;
window.onunload = GUnload;
