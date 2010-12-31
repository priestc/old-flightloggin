function make_sig()
{
	var color = document.getElementById('sig_form').color.value;
	var time = document.getElementById('sig_form').time.value;

	document.getElementById('sig_image').src = "sig.php?id=" + pilot_id + "&h=" + hash + "&color=" + color + "&time=" + time;
	
	
	document.getElementById('html_code').innerHTML = "&lt;a href='http://flightlogg.in'&gt;&lt;img alt='flight time image' src='http://flightlogg.in/sig.php?id=" + pilot_id + "&h=" + hash + "&time=" + time + "&color=" + color + "' /&gt;&lt;/a&gt;";
	document.getElementById('forum_code').innerHTML = "[url=http://flightlogg.in][img]http://flightlogg.in/sig.php?id=" + pilot_id + "&h=" + hash + "&time=" + time + "&color=" + color + "[/img][/url]";
	
}
