<?php
	include "classes/main_classes.php";
	$user = new auth(false);

	require("email/class.phpmailer.php");
	
	################################################################################
	
	$tstamp = strtotime("now");
	
	$month_number = (date("n", $tstamp)-1) % 2;
	
	switch(date("j", $tstamp))
	{
		case "1":
			{$week_number = "0";break;}	
		case "7":
			{$week_number = "1";break;}
		case "14":
			{$week_number = "2";break;}
		case "21":
			{$week_number = "3";break;}
	}
	
	//  4=bimonthly
	//  3=monthly
	//  2=biweekly    DSFSDFDSFDSF3434
	//  1=weekly
	//  0=never
	
	################################################################################
	
	if($month_number == "0" && $week_number == "0")				//begining of "even" month, get everyone
		$sql = "SELECT backup_email, email, pilot_id, backup_frequency, real_name
			FROM `pilots`
			WHERE backup_frequency > 0
			AND 1
			ORDER BY pilot_id DESC";
			
	elseif($month_number == "1" && $week_number == "0")			//begining of "odd" month, get everyone but the bimonthly people
		$sql = "SELECT backup_email, email, pilot_id, backup_frequency, real_name
			FROM `pilots`
			WHERE backup_frequency > 0
			AND backup_frequency != 4
			ORDER BY pilot_id DESC";
			
	elseif($week_number == "0" || $week_number == "2")			//begining of any month, or second week of any month, get everyone but the monthly and bimonthly people
		$sql = "SELECT backup_email, email, pilot_id, backup_frequency, real_name
			FROM `pilots`
			WHERE backup_frequency > 0
			AND backup_frequency != 3 AND backup_frequency != 4
			ORDER BY pilot_id DESC";

	else									//any week, get only the weekly people
		$sql = "SELECT backup_email, email, pilot_id, backup_frequency, real_name
			FROM `pilots`
			WHERE backup_frequency > 0
			AND backup_frequency = 1
			ORDER BY pilot_id DESC";
			
	##################################################################################
	
	$result = mysql_query($sql);
	
	print $sql . "\n";		//print to the cron.log
	print "$month_number - [" . date("m-d-Y", $tstamp) ."] $week_number \n----------------------------------------------\n";		//for debugging in the cron.log
	
	if(!empty($result))
		while($line = mysql_fetch_assoc($result))
		{
			$mail = new PHPMailer();
		
			$mail->IsSMTP();				// telling the class to use SMTP
			$mail->Host       = "localhost";	// sets the SMTP server
			$mail->Port       = 25;
			//$mail->Username   = "nbv4";			// SMTP account username
			//$mail->Password   = "DSFSDFDSFDSF3434";		// SMTP account password
	
			$mail->SetFrom('info@flightlogg.in', "Flightlogg.in'");
			
			//////////////////////////////////////////////////////////////////////////////////////////////
		
			$email = empty($line['backup_email']) ? $line['email'] : $line['backup_email'];
						
			switch($line['backup_frequency'])
			{
				case "1": {$disp_often = "two months";break;}
				case "2": {$disp_often = "month";break;}
				case "3": {$disp_often = "two weeks";break;}
				case "4": {$disp_often = "week";break;}
			}
		
			$next['two months'] = date("m/d/Y", strtotime("+2 month"));
			$next['month'] = date("m/d/Y", strtotime("+1 month"));
			$next['two weeks'] = date("m/d/Y", strtotime("+2 week"));
			$next['week'] = date("m/d/Y", strtotime("+1 week"));
		
			$real_name = empty($line['real_name']) ? "FlightLogg.in' backup" : $line['real_name'] . "'s logbook backup";
			
			$mail->AddAddress($email);
			$mail->Subject  = "$real_name [" . date("m/d/Y", $tstamp) . "]";  
			$mail->Body     = "Currently you are set to recieve these emailed backup files every $disp_often.\n" .
						"http://flightlogg.in";
		
			$filename = "flightlogg.in-backup-" . date("m-d-Y", $tstamp) . ".tsv";
		
			$mail->AddStringAttachment($user->make_backup($line['pilot_id']),$filename, "8bit", "text/plain");
			
			print "\n\n";
			print $email;
			print " $real_name [" . date("m/d/Y", $tstamp) . "]\n";
			
			if(!$mail->Send())
			{  
				echo "Message was not sent.\n";  
				echo "Mailer error: " . $mail->ErrorInfo . "\n\n";
			}
			else  
				echo "Message has been sent.\n\n";		
		}
?>
