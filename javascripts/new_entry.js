function total_button(the_form)
{
	if(mode == "All")
	{
		document.getElementById(the_form).pic.value = eval(document.getElementById(the_form).total.value);
	}

	if(mode == 'Student')
	{
		document.getElementById(the_form).dual_recieved.value = eval(document.getElementById(the_form).total.value);
	}

	if(mode == "Default")
	{
		document.getElementById(the_form).pic.value = eval(document.getElementById(the_form).total.value);
	}

	if(mode == "Private")
	{
		document.getElementById(the_form).pic.value = eval(document.getElementById(the_form).total.value);
	}

	if(mode == "Instructor")
	{
		document.getElementById(the_form).dual_given.value = eval(document.getElementById(the_form).total.value);
		document.getElementById(the_form).pic.value = eval(document.getElementById(the_form).total.value);
	}

	if(mode == "First Officer")
	{
		document.getElementById(the_form).sic.value = eval(document.getElementById(the_form).total.value);
		document.getElementById(the_form).xc.value = eval(document.getElementById(the_form).total.value);
	}

	if(mode == "Captain")
	{
		document.getElementById(the_form).pic.value = eval(document.getElementById(the_form).total.value);
		document.getElementById(the_form).xc.value = eval(document.getElementById(the_form).total.value);
	}
	
	document.getElementById(the_form).total.value = eval(document.getElementById(the_form).total.value);

}
function unhide_all_entries()
{
	//bring back all divs
	document.getElementById("pic_div").style.display = "block";
	document.getElementById("sic_div").style.display = "block";
	document.getElementById("solo_div").style.display = "block";
	document.getElementById("dual_given_div").style.display = "block";
	document.getElementById("dual_recieved_div").style.display = "block";
	document.getElementById("sim_instrument_div").style.display = "block";
	document.getElementById("act_instrument_div").style.display = "block";
	document.getElementById("night_div").style.display = "block";
	document.getElementById("xc_div").style.display = "block";
	document.getElementById("instructor_div").style.display = "block";
	document.getElementById("student_div").style.display = "block";
	document.getElementById("fo_div").style.display = "block";
	document.getElementById("captain_div").style.display = "block";
	document.getElementById("flight_number_div").style.display = "block";

	//make the "show all fields" link go away
	document.getElementById("unhide_entries_text_link").style.display = "none";
}
function hide_entry(to_hide)							//#internal function
{
	document.getElementById(to_hide + "_div").style.display = "none";
}

function hide_entries()								//#hide the irrelevent fields according to the selected logbook mode (set is a global variable)
{
	if(mode == "All")
	{
		/* ha, do nothing */
	}	
	
	else if(mode == "Default")
	{
		hide_entry("instructor");
		hide_entry("student");
		hide_entry("fo");
		hide_entry("captain");
		hide_entry("flight_number");
	}

	else if(mode == "Student")
	{
		hide_entry("sic");
		hide_entry("pic");
		hide_entry("dual_given");
		hide_entry("student");
		hide_entry("fo");
		hide_entry("captain");
		hide_entry("flight_number");
	}

	else if(mode == "Private")
	{
		hide_entry("sic");
		hide_entry("instructor");
		hide_entry("student");
		hide_entry("dual_recieved");
		hide_entry("dual_given");
		hide_entry("fo");
		hide_entry("captain");
		hide_entry("flight_number");
	}

	else if(mode == "Instructor") 
	{
		hide_entry("sic");
		hide_entry("instructor");
		hide_entry("dual_recieved");
		hide_entry("solo");
		hide_entry("fo");
		hide_entry("captain");
		hide_entry("flight_number");
	}
	
	else if(mode == "First Officer")
	{
		hide_entry("instructor");
		hide_entry("student");
		hide_entry("pic");
		hide_entry("solo");
		hide_entry("dual_given");
		hide_entry("dual_recieved");
		hide_entry("sim_instrument");
		hide_entry("fo");
	}

	else if(mode == "Captain")
	{
		hide_entry("sic");
		hide_entry("solo");
		hide_entry("instructor");
		hide_entry("student");
		hide_entry("sim_instrument");
		hide_entry("dual_given");
		hide_entry("dual_recieved");
		hide_entry("captain");
	}

	else if(mode == "Instrument")
	{
		hide_entry("sic");
		hide_entry("instructor");
		hide_entry("student");
		hide_entry("dual_recieved");
		hide_entry("fo");
		hide_entry("captain");
		hide_entry("flight_number");
	}
}	
	var ie = document.all;
	var nn6 = document.getElementById &&! document.all;

	var isdrag = false;
	var x, y;
	var dobj;

	function movemouse( e )
	{
		if( isdrag )
		{
			dobj.style.left = (nn6 ? tx + e.clientX - x : tx + event.clientX - x) + "px";
			dobj.style.top  = (nn6 ? ty + e.clientY - y : ty + event.clientY - y) + "px";
			return false;
		}
	}
	
	function unclick()
	{
		isdrag=false;	
	}

	function selectmouse( e )
	{
		var fobj       = nn6 ? e.target : event.srcElement;
		var topelement = nn6 ? "HTML" : "BODY";
		
		while (fobj.tagName != topelement && fobj.className != "dragme")
		{
			fobj = nn6 ? fobj.parentNode : fobj.parentElement;
		}

		if (fobj.className=="dragme")
		{
		
			isdrag = true;
			dobj = document.getElementById("new_entry_popup");
			
			tx = parseInt(dobj.style.left + 0);
			ty = parseInt(dobj.style.top + 0);
			x = nn6 ? e.clientX : event.clientX;
			y = nn6 ? e.clientY : event.clientY;
			document.onmousemove=movemouse;
			return false;
		}
	}
	
		document.onmousedown=selectmouse;
		document.onmouseup=unclick;
