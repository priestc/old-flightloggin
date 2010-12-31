


function make_graph()
{
	var time = document.getElementById("the_form").bar_time.value;
	var item = document.getElementById("the_form").bar_item.value;
	var image = document.createElement('img');
	var graph_div = document.getElementById("graph_div");
	
	var sec  = document.getElementById("the_form").sec.value;
	var and_sec = sec == "" ? "" : "&sec=" + sec;
	
	while(graph_div.hasChildNodes()) graph_div.removeChild(graph_div.firstChild);

	image.setAttribute("src", "list_graph.php?time=" + time + "&type=" + item + and_sec); 
	
	graph_div.appendChild(image);
}

function make_line()
{
	var item = document.getElementById("the_form").line_item.value;
	var year = document.getElementById("the_form").line_year.value;
	var sec  = document.getElementById("the_form").sec.value;
	
	var and_sec = sec == "" ? "" : "&sec=" + sec;
	
	//var month = document.getElementById("the_form").line_month.value;
	
	
	var image = document.createElement('img');
	var graph_div = document.getElementById("graph_div");
	
	while(graph_div.hasChildNodes()) graph_div.removeChild(graph_div.firstChild);

	if(year == "all")
		image.setAttribute("src", "line_graph.php?type=" + item + and_sec);
	else
		image.setAttribute("src", "line_graph.php?type=" + item + "&year=" + year + and_sec);
	
	
	//#######################################
	graph_div.appendChild(image);
}

function make_sig()
{
	var color = document.getElementById('sig_form').color.value;
	var time = document.getElementById('sig_form').time.value;

	
	var graph_div = document.getElementById("graph_div");
	
	while(graph_div.hasChildNodes()) graph_div.removeChild(graph_div.firstChild);
	
	/////////////////////////////////
	
	var image = document.createElement('img');
	image.setAttribute("src", "sig.php?id=" + pilot_id + "&h=" + hash + "&color=" + color + "&time=" + time);
	
	//////////////////////////////////
	
	var html_code = document.createElement('div');
	html_code.innerHTML = "HTML code:<br /><pre>&lt;a href='http://flightlogg.in'&gt;&lt;img alt='flight time image' src='http://flightlogg.in/sig.php?id=" + pilot_id + "&h=" + hash + "&time=" + time + "&color=" + color + "' /&gt;&lt;/a&gt;</pre>";
	
	/////////////////////////////////
	
	var forum_code = document.createElement('div');
	forum_code.innerHTML = "<br />Forum Code:<br /><pre>[url=http://flightlogg.in][img]http://flightlogg.in/sig.php?id=" + pilot_id + "&h=" + hash + "&time=" + time + "&color=" + color + "[/img][/url]</pre>";
	
	
	graph_div.appendChild(image);
	graph_div.appendChild(html_code);
	graph_div.appendChild(forum_code);
}
