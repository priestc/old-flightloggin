<?php

include "classes/register_class.php";


$user = new register();

$webpage = new page("Register", 1, $user->auth);

###############################################################

$page_content = <<<EOF
<div class="central_div">
	<h1>Register a New Account</h1>

	<script LANGUAGE="JavaScript" src="javascripts/validate_register.js" type="text/javascript"></script>
	
	<form name="myform" action="register-submit.php" method="post" onsubmit="return validate_register_form();">
	
	<div class="login">
		<table border=0 style="margin-right:auto;margin-left:auto">
			<tr>
				<td colspan=2 align=center>$message</td>
			</tr>
		
			<tr>
				<td align=right>Email:</td>
				<td><input type="text" name="email"></td>
			</tr>
		
			<tr>
				<td align=right>Password:</td>
				<td><input type="password" name="password"></td>
			</tr>
		
			<tr>
				<td align=right>Verify:</td>
				<td><input type="password" name="verify"></td>
			</tr>
		
			<tr>
				<td align=right>Stay Logged In?</td>
				<td><input type="checkbox" name="cookie_type" value="perm"></td>
			</tr>
			
			<tr>
				<td colspan=2 style="text-align:center;padding-top:5px">
				<input type="submit" name="submit" value="submit"></td>
			</tr>
		</table>
	</div>

	
	</form>
</div>

EOF;


###############################################################

$webpage->add_content($page_content);
	
###############################################################
$html = $webpage->output();

echo $html;

?>
