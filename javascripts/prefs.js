function toggle_visibility(item)
{
	var stylesheet = document.getElementById('stylesheet');
	
	if(document.getElementById('prefs_form')[item].checked)
	{
		var css = " #" + item + "_span" + " { visibility: visible; }";
	}	
	
	else
	{
		var css = " #" + item + "_span" + " { visibility: hidden; }";
	}
	
	

	stylesheet.innerHTML = stylesheet.innerHTML + css;
	

}
