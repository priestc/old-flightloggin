	var map;
	var center_lat = "36.0000";
	var center_long = "-97.1419";
	var center_zoom = 4;
	
	var icon = new GIcon;
	icon.image = "http://maps.google.com/mapfiles/kml/pal4/icon49.png";
	icon.iconSize = new GSize(32, 32);
	icon.iconAnchor = new GPoint(16, 16);
	icon.infoWindowAnchor = new GPoint(16, 16);

function add_marker(latitude, longitude, identifier, airport, city, sector)
{
	var marker = new GMarker(new GLatLng(latitude, longitude), icon);
	
	GEvent.addListener(marker, 'click',
	
		function()
		{
			if(airport != "")
				marker.openInfoWindowHtml("<span><strong>" + identifier + "</strong><br />" + airport + "<br />" + city + ", " + sector + "</span");
			else	marker.openInfoWindowHtml("<span><strong>" + identifier + "</strong><br />" + city + ", " + sector + "</span");
		}
	);
	
	map.addOverlay(marker);
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
		
		for(id in markers)
		{
			add_marker(markers[id].latitude, markers[id].longitude, markers[id].identifier, markers[id].airport, markers[id].city, markers[id].sector);
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
