function validate_register_form() 
{

	var x = document.forms[0].email.value;
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(x))
	{
		
		if(document.forms[0].password.value == document.forms[0].verify.value)
			return true;
		else
		{
			alert('Passwords do not match');
			return false;
		}

		
	}
	else
	{
		alert('Please enter a valid email address');
		return false;
	}
}
