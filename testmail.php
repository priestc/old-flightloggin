<?php

require("email/class.phpmailer.php");
	$mail = new PHPMailer();
	
	
	$mail->IsSMTP();				// telling the class to use SMTP
	$mail->SMTPAuth   = true;			// enable SMTP authentication
	$mail->Host       = "smtp.webfaction.com";	// sets the SMTP server
	$mail->Port       = 25;
	$mail->Username   = "nbv4";			// SMTP account username
	$mail->Password   = "DSFSDFDSFDSF3434";		// SMTP account password
	
	$mail->SetFrom('info@flightlogg.in', "Flightlogg.in'");
	
	///////////////////////////////////////////////////////
	
	$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";
	
	$mail->AddAddress("nbvfour@gmail.com", "Bob Jones" );
	
	$mail->MsgHTML("dongs terds lol laffo");
	
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
	else
	{
		echo "Message sent!";
	}
?>
