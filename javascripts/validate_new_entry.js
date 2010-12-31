function validate_new_entry_form(the_form) 
{
	var date_regex = /(?=\d)^(?:(?!(?:10\D(?:0?[5-9]|1[0-4])\D(?:1582))|(?:0?9\D(?:0?[3-9]|1[0-3])\D(?:1752)))((?:0?[13578]|1[02])|(?:0?[469]|11)(?!\/31)(?!-31)(?!\.31)|(?:0?2(?=.?(?:(?:29.(?!000[04]|(?:(?:1[^0-6]|[2468][^048]|[3579][^26])00))(?:(?:(?:\d\d)(?:[02468][048]|[13579][26])(?!\x20BC))|(?:00(?:42|3[0369]|2[147]|1[258]|09)\x20BC))))))|(?:0?2(?=.(?:(?:\d\D)|(?:[01]\d)|(?:2[0-8])))))(\/)(0?[1-9]|[12]\d|3[01])\2(?!0000)((?=(?:00(?:4[0-5]|[0-3]?\d)\x20BC)|(?:\d{4}(?!\x20BC)))\d{4}(?:\x20BC)?)(?:$|(?=\x20\d)\x20))?((?:(?:0?[1-9]|1[012])(?::[0-5]\d){0,2}(?:\x20[aApP][mM]))|(?:[01]\d|2[0-3])(?::[0-5]\d){1,2})?$/;
	
	var route = the_form.route.value;
	var date = the_form.date.value;
	
	var tracking = the_form.tracking.checked;
	var holding = the_form.holding.checked;
	var pilot_checkride = the_form.pilot_checkride.checked
	var cfi_checkride = the_form.cfi_checkride.checked
	var bfr = the_form.bfr.checked
	var ipc = the_form.ipc.checked
	
	var plane_id = the_form.plane_id.value;
	var star = plane_id.charAt(plane_id.length-1);
	var is_sim = star == "*";
	
	var total = Number(the_form.total.value);
	var pic = Number(the_form.pic.value);
	var sic = Number(the_form.sic.value);
	var solo = Number(the_form.solo.value);
	var act_instrument = Number(the_form.act_instrument.value);
	var sim_instrument = Number(the_form.sim_instrument.value);
	var dual_recieved = Number(the_form.dual_recieved.value);
	var dual_given = Number(the_form.dual_given.value);
	var night = Number(the_form.night.value);
	var xc = Number(the_form.xc.value);
	
	var approaches = Number(the_form.approaches.value);
	
	var day_landings = Number(the_form.day_landings.value);
	var night_landings = Number(the_form.night_landings.value);
	
	//###################################################################################
	
	//############################# date must be valid
	
	if (!date_regex.test(date))
	{
		alert('Invalid date');
		return false;
	}
	
	//############################ total must be greater than zero
	
	if (total <= 0)
	{
		alert('Total time can not be 0');
		return false;
	}
	
	
	if (route <= 0)
	{
		if(!confirm('Route should not be empty\n\nContinue anyways?'))
			return false;
	}
	
	//############################ no negative values
	
	if (pic < 0 || sic < 0 || approaches < 0 || solo < 0 || act_instrument < 0 || sim_instrument < 0 ||
		dual_given < 0 || dual_recieved < 0 || night < 0 || xc < 0 || day_landings < 0 || night_landings < 0)
	{
		alert('Negative values not allowed');
		return false;
	}
	
	if(is_sim)
	{
	
		if(pic > 0)
		{
			if(!confirm('You can not log PIC time in a simulator or FTD\nContinue anyways?'))
				return false;
		}
		
		if(sic > 0)
		{
			if(!confirm('You can not log SIC time in a simulator or FTD\n\nContinue anyways?'))
				return false;
		}
		
		if(solo > 0)
		{
			if(!confirm('You can not log solo time in a simulator or FTD\n\nContinue anyways?'))
				return false;
		}
		
		if(act_instrument > 0)
		{
			if(!confirm('You can not log actual instrument time in a simulator or FTD\n\nContinue anyways?'))
				return false;
		}
		
		if (total < sim_instrument)
		{
			if(!confirm('Simulated Instrument time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
	
		if (act_instrument == 0 && sim_instrument == 0 && approaches > 0)
		{
			if(!confirm('You should log some Instrument time in order to log Approaches\n\nContinue anyways?'))
				return false;
		}

		if (act_instrument == 0 && sim_instrument == 0 && holding)
		{
			if(!confirm('You should log some Instrument time in order to log holds\n\nContinue anyways?'))
				return false;
		}
	
		if (act_instrument == 0 && sim_instrument == 0 && tracking)
		{
			if(!confirm('You should log some Instrument time in order to log intercepting and tracking\n\nContinue anyways?'))
				return false;
		}

	}
	else
	{
		//############################## total time should be greater than other times logged.
	
		if (total < pic)
		{
			if(!confirm('PIC time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < sic)
		{
			if(!confirm('SIC time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < solo)
		{
			if(!confirm('Solo time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < dual_given)
		{
			if(!confirm('Dual Given time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < dual_recieved)
		{
			if(!confirm('Dual recieved time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < sim_instrument)
		{
			if(!confirm('Simulated Instrument time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < act_instrument)
		{
			if(!confirm('Actual Instrument time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < night)
		{
			if(!confirm('Night time should not exceed Total time\n\nContinue anyways?'))
				return false;
		}
		if (total < xc)
		{
			if(!confirm('Cross Country time should not exceed Total time\n\nContinue anyways?'))
				return false;

		}
	
		//############################### night time and/or day time needed to log day/night landings
	
		if (night == 0 && night_landings > 0)
		{
			if(!confirm('Night time should be logged in order to log Night Landings\n\nContinue anyways?'))
				return false;
		}
		if (night == total && day_landings > 0)
		{
			if(!confirm('Day time should be logged in order to log Day Landings\n\nContinue anyways?'))
				return false;
		}
	
		//################################ instrument time needed to log approaches, tracking and holds
	
		if (act_instrument == 0 && sim_instrument == 0 && approaches > 0)
		{
			if(!confirm('You should log some Instrument time in order to log Approaches\n\nContinue anyways?'))
				return false;
		}

		if (act_instrument == 0 && sim_instrument == 0 && holding)
		{
			if(!confirm('You should log some Instrument time in order to log holds\n\nContinue anyways?'))
				return false;
		}
	
		if (act_instrument == 0 && sim_instrument == 0 && tracking)
		{
			if(!confirm('You should log some Instrument time in order to log intercepting and tracking\n\nContinue anyways?'))
				return false;
		}
	
		//############################## time should be either dual recieved, SIC, or PIC
	
		if (Math.round((pic + sic + dual_recieved)*10)/10 != Math.round(total*10)/10)
		{
			if(!confirm('Dual Recieved + PIC + SIC should equal total time.\n\nContinue anyways?'))
				return false;
		}
		
		//############################## all dual given should be PIC
		if (dual_given > pic)
		{
			if(!confirm('All Dual Given time should be also logged as PIC time.\n\nContinue anyways?'))
				return false;
		}
	}
}
