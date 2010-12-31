function hide_column(the_class)
{
	var cssRules;
	if (document.all)
		cssRules = 'rules';

	else if (document.getElementById)
	  	cssRules = 'cssRules';

	for (var S = 0; S < document.styleSheets.length; S++)
	{
		for (var R = 0; R < document.styleSheets[S][cssRules].length; R++)
		{
			if (document.styleSheets[S][cssRules][R].selectorText == the_class)
				document.styleSheets[S][cssRules][R].style["display"] = "none";
		}
	}	
}

function unhide_column(the_class)			//#internal function
{
	var cssRules;
	if (document.all)
		cssRules = 'rules';

	else if (document.getElementById)
	  cssRules = 'cssRules';

	for (var S = 0; S < document.styleSheets.length; S++)
	{
		for (var R = 0; R < document.styleSheets[S][cssRules].length; R++)
		{
			if (document.styleSheets[S][cssRules][R].selectorText == the_class)
				document.styleSheets[S][cssRules][R].style["display"] = "";
		}
	}	
}

function toggle_hide_columns()
{
	if(document.getElementById("unhide_columns_text_link").style.display == "inline")
		document.getElementById("unhide_columns_text_link").style.display = "none";
	else
		document.getElementById("unhide_columns_text_link").style.display = "inline";

	if(document.getElementById("rehide_columns_text_link").style.display == "inline")
		document.getElementById("rehide_columns_text_link").style.display = "none";
	else
		document.getElementById("rehide_columns_text_link").style.display = "inline";
}

function toggle_hide_entries()
{
	if(document.getElementById("unhide_entries_text_link").style.display == "inline")
		document.getElementById("unhide_entries_text_link").style.display = "none";
	else
		document.getElementById("unhide_entries_text_link").style.display = "inline";

	if(document.getElementById("rehide_entries_text_link").style.display == "inline")
		document.getElementById("rehide_entries_text_link").style.display = "none";
	else
		document.getElementById("rehide_entries_text_link").style.display = "inline";
}

function reset_hide_entries()			//#resets the status of the hide link text, changing to the edit window messed up things up, so this function fixes it.
{
	document.getElementById("rehide_entries_text_link").style.display = "none"
	document.getElementById("unhide_entries_text_link").style.display = "inline";
}

function prepare_and_fire_entry_form(type, flight_id, date, remarks, flightarray, non_flying_value)
{
	if(type == "edit_flight")
	{
		plane_id = flightarray[0];
		route = flightarray[1];
		total = flightarray[2];
		pic = flightarray[3];
		solo = flightarray[4];
		sic = flightarray[5];
		dual_recieved = flightarray[6];
		dual_given = flightarray[7];
		actual = flightarray[8];
		hood = flightarray[9];
		approaches = flightarray[10];
		night = flightarray[11];
		xc = flightarray[12];
		day_landings = flightarray[13];
		night_landings = flightarray[14];
		student = flightarray[15];
		instructor = flightarray[16];
		fo = flightarray[17];
		captain = flightarray[18];
		flight_number = flightarray[19];
		holding = flightarray[20];
		tracking = flightarray[21];
		bfr = flightarray[22];
		ipc = flightarray[23];
		pilot = flightarray[24];
		cfi = flightarray[25];
	
		switch_to_flying();
	
		document.getElementById("new_entry_form").flight_id.value=flight_id;
	
		document.getElementById("new_entry_form").date.value=date;
		document.getElementById("new_entry_form").plane_id.value=plane_id;
		document.getElementById("new_entry_form").route.value=route;
		document.getElementById("new_entry_form").total.value=total;
		document.getElementById("new_entry_form").pic.value=pic;
		document.getElementById("new_entry_form").solo.value=solo;
		document.getElementById("new_entry_form").sic.value=sic;
		document.getElementById("new_entry_form").dual_recieved.value=dual_recieved;
		document.getElementById("new_entry_form").dual_given.value=dual_given;
		document.getElementById("new_entry_form").act_instrument.value=actual;
		document.getElementById("new_entry_form").sim_instrument.value=hood;
		document.getElementById("new_entry_form").approaches.value=approaches;
		document.getElementById("new_entry_form").night.value=night;
		document.getElementById("new_entry_form").xc.value=xc;
		document.getElementById("new_entry_form").day_landings.value=day_landings;
		document.getElementById("new_entry_form").night_landings.value=night_landings;
	
		document.getElementById("new_entry_form").student.value=student;
		document.getElementById("new_entry_form").instructor.value=instructor;
		document.getElementById("new_entry_form").captain.value=captain;
		document.getElementById("new_entry_form").fo.value=fo;
		document.getElementById("new_entry_form").flight_number.value=flight_number;
		document.getElementById("new_entry_form").remarks.value=remarks;
		
		document.getElementById("new_entry_form").holding.checked = holding;
		document.getElementById("new_entry_form").tracking.checked = tracking;
		document.getElementById("new_entry_form").bfr.checked = bfr;
		document.getElementById("new_entry_form").ipc.checked = ipc;
		document.getElementById("new_entry_form").pilot_checkride.checked = pilot;
		document.getElementById("new_entry_form").cfi_checkride.checked = cfi;
		
		document.getElementById("entry_popup_title").innerHTML = "<big>Edit Entry</big>";
		
		document.getElementById("new_entry_buttons").style.display = "none";
		document.getElementById("edit_entry_buttons").style.display = "block";
		
		hide_entries(mode);	//#?_?
		unhide_all_entries();
		
		switch_sim();
	
		
		fire_popup("new_entry_popup", 600);
	}
	
	else if(type == "new_flight")
	{
		switch_to_flying();
		 
		d = new Date();
		month = (d.getMonth()+1).toString().length < 2 ? "0" + (d.getMonth()+1) : (d.getMonth()+1)	//#add a leading zero if its only one digit, +1 because javascript is dum
		day = (d.getDate()).toString().length < 2 ? "0" + (d.getDate()) : (d.getDate())
		
		var todays_date = month + "/" + day + "/" + d.getFullYear();
		
		//#####################
	
		document.getElementById("new_entry_buttons").style.display = "block";
		document.getElementById("edit_entry_buttons").style.display = "none";
		
		hide_entries(mode);	//#?_?
		reset_hide_entries();
	
		document.getElementById("entry_popup_title").innerHTML = "<big>New Entry</big>";		//#change the title of the popup
		document.getElementById("new_entry_form").reset();
		
		document.getElementById("new_entry_form").date.value=todays_date;
		
		switch_sim();
		
		fire_popup("new_entry_popup", 600);
	}
	
	else if(type == "new_non_flight")
	{
		d = new Date();
		month = (d.getMonth()+1).toString().length < 2 ? "0" + (d.getMonth()+1) : (d.getMonth()+1)	//#add a leading zero if its only one digit, +1 because javascript is dum
		day = (d.getDate()).toString().length < 2 ? "0" + (d.getDate()) : (d.getDate())
		
		var todays_date = month + "/" +day + "/" + d.getFullYear();
		
		//########################
	
		switch_to_non_flying();
		hide_entries(mode);	//#?_?
		
		document.getElementById("non_popup_title").innerHTML = "<big>New Non-flying Event</big>";
		
		document.getElementById("new_non_buttons").style.display = "block";
		document.getElementById("edit_non_buttons").style.display = "none";
		
		document.getElementById("non_flying_form").date.value=todays_date;
		
		fire_popup("new_entry_popup", 600);
	}
	
	else if(type == "edit_non_flight")
	{
		switch_to_non_flying();
		hide_entries(mode);	//#?_?
		
		document.getElementById("non_popup_title").innerHTML = "<big>Edit Non-flying Event</big>";
		document.getElementById("edit_non_buttons").style.display = "block";
		document.getElementById("new_non_buttons").style.display = "none";
		
		document.getElementById("non_flying_form").flight_id.value = flight_id;
		document.getElementById("non_flying_form").remarks.value = remarks;
		document.getElementById("non_flying_form").date.value = date;
		
		document.getElementById("non_flying_form").non_flying[non_flying_value - 1].checked = "checked";
				
		fire_popup("new_entry_popup", 600);
	}
	
}

function switch_to_non_flying()
{

	document.getElementById("flying_div").style.display = "none";
	document.getElementById("non_flying_div").style.display = "block";
}

function switch_to_flying()
{

	document.getElementById("non_flying_div").style.display = "none";
	document.getElementById("flying_div").style.display = "block";
}

function show_all_columns()
{
		unhide_column('.captain_col');
		unhide_column('.fo_col');
		unhide_column('.flight_number_col');
		unhide_column('.student_col');
		unhide_column('.instructor_col');
		unhide_column('.sic_col');
		unhide_column('.pic_col');
		unhide_column('.sim_col');
		unhide_column('.solo_col');
		unhide_column('.dual_recieved_col');
		unhide_column('.dual_given_col');
}

function hide_the_columns(mode)
{
	if(mode == "All")
	{
		//blah nothing
	}

	if(mode == 'Student')
	{
		hide_column('.captain_col');
		hide_column('.fo_col');
		hide_column('.flight_number_col');
		hide_column('.student_col');
		hide_column('.dual_given_col');
		hide_column('.sic_col');
	}

	if(mode == "Default")
	{
		hide_column('.captain_col');
		hide_column('.fo_col');
		hide_column('.flight_number_col');
		hide_column('.student_col');
		hide_column('.instructor_col');
	}

	if(mode == "Private")
	{
		hide_column('.captain_col');
		hide_column('.fo_col');
		hide_column('.flight_number_col');
		hide_column('.student_col');
		hide_column('.instructor_col');
		hide_column('.dual_given_col');
		hide_column('.dual_recieved_col');
	}

	if(mode == "Instructor")
	{
		hide_column('.captain_col');
		hide_column('.fo_col');
		hide_column('.flight_number_col');
		hide_column('.instructor_col');
		hide_column('.dual_recieved_col');
		hide_column('.sic_col');
		hide_column('.solo_col');
		hide_column('.sim_col');
	}

	if(mode == "First Officer")
	{
		hide_column('.fo_col');
		hide_column('.dual_given_col');
		hide_column('.student_col');
		hide_column('.instructor_col');
		hide_column('.sim_col');
		hide_column('.dual_recieved_col');
		hide_column('.solo_col');
		hide_column('.pic_col');
	}

	if(mode == "Captain")
	{
		hide_column('.captain_col');
		hide_column('.student_col');
		hide_column('.instructor_col');
		hide_column('.sic_col');
		hide_column('.solo_col');
		hide_column('.dual_recieved_col');
		hide_column('.dual_given_col');
		hide_column('.sim_col');
	}
	
}

function close_popup(id)
{
	 document.getElementById(id).style.display = "none";
}

function fire_popup(id, width)
{
	if(document.getElementById(id).style.display == 'block')			//the popup is already made, do nothing more
		return;

	// Determine how much the visitor had scrolled

	var scrolledX, scrolledY;
	
	if( self.pageYOffset )
	{
		scrolledX = self.pageXOffset;
		scrolledY = self.pageYOffset;
	}
	else if( document.documentElement && document.documentElement.scrollTop )
	{
		scrolledX = document.documentElement.scrollLeft;
		scrolledY = document.documentElement.scrollTop;
	}
	else if( document.body )
	{
		scrolledX = document.body.scrollLeft;
		scrolledY = document.body.scrollTop;
	}

	// Determine the coordinates of the center of the page

	var centerX, centerY;
	
	if( self.innerHeight )
	{
		centerX = self.innerWidth;
		centerY = self.innerHeight;
	}
	else if( document.documentElement && document.documentElement.clientHeight )
	{
		centerX = document.documentElement.clientWidth;
		centerY = document.documentElement.clientHeight;
	}
	else if( document.body )
	{
		centerX = document.body.clientWidth;
		centerY = document.body.clientHeight;
	}
	
	var leftOffset = scrolledX + (centerX - width) / 2;
	var topOffset = scrolledY + (centerY - width) / 2;
	
	document.getElementById(id).style.top = topOffset + "px";
	document.getElementById(id).style.left = leftOffset + "px";
	document.getElementById(id).style.display = "block";
}

function switch_sim()
{
	if(document.getElementById("plane_dropdown").value.indexOf("*") >= 0)
	{
		document.getElementById("total_label").innerHTML = "Simulator";
		
		document.getElementById("sic_div").style.visibility = "hidden";
		document.getElementById("pic_div").style.visibility = "hidden";
		document.getElementById("solo_div").style.visibility = "hidden";
		document.getElementById("dual_given_div").style.visibility = "hidden";
		document.getElementById("act_instrument_div").style.visibility = "hidden";
		document.getElementById("xc_div").style.visibility = "hidden";
		document.getElementById("night_div").style.visibility = "hidden";
	}
	else
	{
		document.getElementById("total_label").innerHTML = "Total";
		
		document.getElementById("sic_div").style.visibility = "visible";
		document.getElementById("pic_div").style.visibility = "visible";
		document.getElementById("solo_div").style.visibility = "visible";
		document.getElementById("dual_given_div").style.visibility = "visible";
		document.getElementById("act_instrument_div").style.visibility = "visible";
		document.getElementById("xc_div").style.visibility = "visible";
		document.getElementById("night_div").style.visibility = "visible";
	}
}



