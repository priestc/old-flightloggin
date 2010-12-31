function un_gray(object)
{
	object.className = object.className.replace(/(^|\s)mass_grayed(\s|$)/g,'');
}

function re_gray(object)
{
	if( !object.className.match(/(^|\s)mass_grayed(\s|$)/) )
		object.className += " mass_grayed";
}

function on_focus(object)
{
	if( object.value == "Instructor" && object.name.substr(0,10) == "instructor")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Student" && object.name.substr(0,7) == "student")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "First Officer" && object.name.substr(0,2) == "fo")
	{
		object.value = '';
		un_gray(object);
	}
		
	else if( object.value == "Captain" && object.name.substr(0,7) == "captain")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Date (mm/dd/yyyy)" && object.name.substr(0,4) == "date")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Route" && object.name.substr(0,5) == "route")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Flight Number" && object.name.substr(0,13) == "flight_number")
	{
		object.value = '';
		un_gray(object);
	}

	else if( object.value == "Remarks" && object.name.substr(0,7) == "remarks")
	{
		object.value = '';
		un_gray(object);
	}
	
	//#######################
	
	else if( object.value == "Total" && object.name.substr(0,5) == "total")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "PIC" && object.name.substr(0,3) == "pic")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "SIC" && object.name.substr(0,3) == "sic")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Solo" && object.name.substr(0,4) == "solo")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Giv" && object.name.substr(0,10) == "dual_given")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Rec" && object.name.substr(0,13) == "dual_recieved")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Act" && object.name.substr(0,14) == "act_instrument")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Ngt" && object.name.substr(0,5) == "night")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Hd." && object.name.substr(0,14) == "sim_instrument")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "Ap." && object.name.substr(0,10) == "approaches")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "N." && object.name.substr(0,14) == "night_landings")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "D." && object.name.substr(0,12) == "day_landings")
	{
		object.value = '';
		un_gray(object);
	}
	
	else if( object.value == "XC" && object.name.substr(0,2) == "xc")
	{
		object.value = '';
		un_gray(object);
	}
	
}

function off_focus(object)
{
	
	if( object.value == "" && object.name.substr(0,10) == "instructor")
	{
		re_gray(object);
		object.value="Instructor";		
	}
	
	else if( object.value == "" && object.name.substr(0,7) == "student")
	{
		re_gray(object);
		object.value="Student";		
	}
	
	else if( object.value == "" && object.name.substr(0,2) == "fo")
	{
		re_gray(object);
		object.value="First Officer";		
	}
	
	else if( object.value == "" && object.name.substr(0,7) == "captain")
	{
		re_gray(object);
		object.value="Captain";
	}
	
	else if( object.value == "" && object.name.substr(0,4) == "date")
	{
		re_gray(object);
		object.value="Date (mm/dd/yyyy)";		
	}
	
	else if( object.value == "" && object.name.substr(0,5) == "route")
	{
		re_gray(object);
		object.value="Route";		
	}
	
	else if( object.value == "" && object.name.substr(0,7) == "remarks")
	{
		re_gray(object);
		object.value="Remarks";		
	}
	
	else if( object.value == "" && object.name.substr(0,13) == "flight_number")
	{
		re_gray(object);
		object.value="Flight Number";		
	}
	
	//##########################
	
	else if( object.value == "" && object.name.substr(0,5) == "total")
	{
		re_gray(object);
		object.value = 'Total';		
	}
	
	else if( object.value == "" && object.name.substr(0,3) == "pic")
	{
		re_gray(object);
		object.value = 'PIC';
	}
	
	else if( object.value == "" && object.name.substr(0,3) == "sic")
	{
		re_gray(object);
		object.value = 'SIC';		
	}
	
	else if( object.value == "" && object.name.substr(0,4) == "solo")
	{
		re_gray(object);
		object.value = 'Solo';		
	}
	
	else if( object.value == "" && object.name.substr(0,13) == "dual_recieved")
	{
		re_gray(object);
		object.value = 'Rec';		
	}
	
	else if( object.value == "" && object.name.substr(0,10) == "dual_given")
	{
		re_gray(object);
		object.value = 'Giv';		
	}
	
	else if( object.value == "" && object.name.substr(0,14) == "act_instrument")
	{
		re_gray(object);
		object.value = 'Act';		
	}
	
	else if( object.value == "" && object.name.substr(0,14) == "sim_instrument")
	{
		re_gray(object);
		object.value = 'Hd.';
	}
	
	else if( object.value == "" && object.name.substr(0,14) == "night_landings")
	{
		re_gray(object);
		object.value = 'N.';
	}
	
	else if( object.value == "" && object.name.substr(0,5) == "night")
	{
		re_gray(object);
		object.value = 'Ngt';
	}
	
	else if( object.value == "" && object.name.substr(0,2) == "xc")
	{
		re_gray(object);
		object.value = 'XC';
	}
	
	else if( object.value == "" && object.name.substr(0,12) == "day_landings")
	{
		re_gray(object);
		object.value = 'D.';
	}
	
	else if( object.value == "" && object.name.substr(0,6) == "actual")
	{
		re_gray(object);
		object.value = 'Act';	
	}
	
	else if( object.value == "" && object.name.substr(0,10) == "approaches")
	{
		re_gray(object);
		object.value = 'Ap.';
		
	}
}
