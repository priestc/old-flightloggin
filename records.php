<?php

include "classes/records_class.php";

$user = new records($_COOKIE['id'], $_COOKIE['pass'],true);

###############################################################

if(!empty($_POST))
{
	$user->submit_records($_POST['records']);
	
	header("Location: logbook.php{$user->get_sec_q}");
	exit;
}
else
{
	$webpage = new page("Records", 1, $user->auth);
	
	$user->get_records();
	
	if($user->auth != "share")
		$save_button = "<input type=\"submit\" value=\"Save\" />";
	
	$content = "<div class=\"central_div\" style=\"text-align:center\">

			{$webpage->ads}
	
			<div>Use this space to record any information you'd normally put in the back of a paper logbook. Phone numbers and addresses of contacts; dates of endorsements; student signoffs and outcomes; notes, etc.
			
			</div><br />

			<form action=\"records.php\" method=\"post\">
				<div>
					<input type=\"hidden\" name=\"execute\" value=\"set\" />
					<textarea name=\"records\" style=\"width:80%;height:400px\" cols=\"50\" rows=\"10\">{$user->records}</textarea>
			
					<br /><br />
		
					$save_button
				</div>
		
			</form>
		</div>";
}


###############################################################

	$webpage->add_content($content);

###############################################################

$html = $webpage->output();

echo $html;

?>
