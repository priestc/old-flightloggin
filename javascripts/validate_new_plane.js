function validate_all_planes()
{
	var all_ids = document.getElementById("planes_form").all_ids.value;
	var all_ids_array = all_ids.split(" ");

	for( i in all_ids_array)						//go through the "all_ids" field and validate each form
	{	
		if(!validate_new_plane_form(all_ids_array[i]))
			return false;					//if one form does not validate, return false
	}	
		
	return true;							//if all forms validate, return true
}

function validate_new_plane_form(plane_id)						//validate a plane form
{
	var tail_number = document.getElementById("tail_number_" + plane_id).value;
	var type = document.getElementById("type_" + plane_id).value;
	var tags = document.getElementById("tags_" + plane_id).value;
	var model = document.getElementById("model_" + plane_id).value;
	
	//alert("planes_form.tail_number_" + plane_id);
	
	if(tail_number == "Tailnumber")
	{
		alert('You must enter a tailnumber');
		return false;
	}
	
	if(tail_number == "")
	{
		alert('Tail number can not be empty');
		return false;
	}
	
	if((type == "" && model == "") || (type == "Type" && model == "Model"))
	{
		alert('You must either enter a type, or a model name');
		return false;
	}
	
	return true;
}
	
