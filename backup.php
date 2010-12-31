<?php
	include "classes/main_classes.php";
	
	$filename = "flightlogg.in-backup-" . date('M-d-Y_H:i');
	header("Content-Disposition: attachment; filename=\"$filename.tsv\"");		// so the browser tries to download it.
	$user = new auth(true);
	
	print $user->make_backup();
?>
