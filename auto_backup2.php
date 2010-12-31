<?php
require("email/class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();                                // telling the class to use SMTP
$mail->Host       = "localhost";       // sets the SMTP server
$mail->Port       = 25;
//$mail->Username   = "";                     // SMTP account username
//$mail->Password   = "";         // SMTP account password
$mail->SetFrom('info@flightlogg.in', "Flightlogg.in'");

$mail->AddAddress('nbvfour@gmail.com');
$mail->Subject  = 'test';
$mail->Body     = "email test";

$mail->send();

$mail->ErrorInfo;

//mail('nbvfour@gamail.com', 'test', 'test message')

require_once "Mail.php";

$from = "Sandra Sender <test@flightlogg.in>";
$to = "Ramona Recipient <nbvfour@gmail.com>";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";

$host = "flightlogg.in";
$username = "";
$password = "";

$headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
$smtp = Mail::factory('smtp',
  array ('host' => $host,
    'auth' => false,
    'username' => $username,
    'password' => $password));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
 } else {
  echo("<p>Message successfully sent!</p>");
 }
?>
