<?php

include "classes/main_classes.php";


$user = new auth($_COOKIE['id'], $_COOKIE['pass']);

$webpage = new page("Home", 1, $user->auth);

###############################################################

if(!empty($_GET['w']))
{
	$password = str_rot13($_GET['w']);
	$email    = $_GET['email'];
	$message_login = "<strong>Password Reset Successful</strong>";
}
else
{
	$auto_complete = "";
	$password = "";
}

if($_GET['login_fail'])
	$message_login = "<strong>Incorrect Login</strong>";
	
if($_GET['activate_fail'])
	$message_login = "<strong>Invalid Activation String</strong>";
	
if($_GET['email_dont_exist'])
{
	$message_forget = "<strong>That Email doesn't exist</strong>";
	$login_disp = "none";
	$email_disp = "block";
}
else
{
	if($_GET['reset_success'])
		$message_login = "Password reset. Check your mail.";
		
	$login_disp = "block";
	$email_disp = "none";
}

if($user->auth == "auth")
{
	$news_display = "block";
	$intro_display = "none";
}
else
{
	$news_display = "none";
	$intro_display = "block";
}

$walkthrough = file_get_contents("walkthrough.html");
$FAQ = file_get_contents("FAQ.html");
$news = file_get_contents("news.html");
$intro = file_get_contents("intro.html");


$login = <<<EOF

	<form id="login" action="login.php" method="post">
		<div style="display:$login_disp" class="login">
		
			<table style="margin-left:auto;margin-right:auto" summary="log in box">
				<tr>
					<td colspan="2" align="center">$message_login</td>
				</tr>
				
				<tr>
					<td colspan="2" align="center"></td>
				</tr>
				
				<tr>
					<td align="right">Email:</td>
					<td><input type="text" name="email" value="$email" /></td>
				</tr>
				<tr>
					<td align="right">Password:</td>
					<td><input type="password" name="password" value="$password" /></td>
				</tr>
				<tr>	
					<td colspan="2">Stay Logged In?<input type="checkbox" name="cookie_type" value="perm" /></td>
				</tr>
			</table>
			
			<input type="submit" name="submit" value="Login" style="margin:10px" /><br />
			
			<span class="fake_anchor" onclick="document.getElementById('forgot').style.display = 'block'; document.getElementById('login').style.display = 'none';"
				style="font-size:x-small">Forgot your password?</span>
		</div>
	</form>
	
	<div id="forgot" class="login" style="display:$email_disp">
		<form id="change_pass" action="change_pass.php" method="post">
		<div>
			<table style="margin-left:auto;margin-right:auto" summary="forgot password box">
				<tr>
					<td colspan="2" align="center">$message_forget</td>
				</tr>
				
				<tr>
					<td colspan="2" align="center"></td>
				</tr>
				
				<tr>
					<td align="right">Email:</td>
					<td><input type="text" name="email" /></td>
				</tr>
			</table>
			
			<input type="submit" id="submit" value="Reset Password" style="margin:10px" /><br />
			
			<span class="fake_anchor" onclick="document.getElementById('forgot').style.display = 'none'; document.getElementById('login').style.display = 'block';"
				style="font-size:x-small">Remembered your password?</span>
		</div>
		</form>
	</div>


EOF;

if(empty($_COOKIE['id']))					//make the login box if there is no cookie found
	$login_or_not = $login;
else	$login_or_not = "";

$page_content = <<<EOF

	<script src="javascripts/home.js" type="text/javascript"></script>

	$login_or_not

	<div class="central_div">

		{$webpage->ads}
	
		<div style="font-size:x-large;color:red;padding-bottom:1em">$message_sent</div>

		<div id="news" style="font-size:medium;text-align:left; display: $news_display">
		
			<div style="border:1px solid black;margin-bottom:20px">
				<h1>
					<span class="fake_anchor" onclick="front_page_toggle('faq');">F.A.Q.</span>&nbsp;-&nbsp;
					<span>News</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('intro');">Introduction</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('walk');">Walkthrough</span>
				</h1>
			</div>
			
			$news
	
	
		<div id="faq" style="font-size:medium;text-align:left;display:none">
		
			<div style="border:1px solid black;margin-bottom:20px">
				<h1>
					<span>F.A.Q.</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('news');">News</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('intro');">Introduction</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('walk');">Walkthrough</span>
				</h1>
			</div>
		
			$FAQ
	
		</div>
		
		<div id="intro" style="font-size:medium;text-align:left; display: $intro_display">
		
			<div style="border:1px solid black;margin-bottom:20px">
				<h1>
					<span class="fake_anchor" onclick="front_page_toggle('faq');">F.A.Q.</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('news');">News</span>&nbsp;-&nbsp;
					<span>Introduction</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('walk');">Walkthrough</span>
				</h1>
			</div>
			
			$intro			

		</div>
		
		<div id="walk" style="font-size:medium;text-align:left;display:none">
		
			<div style="border:1px solid black;margin-bottom:20px">
				<h1>
					<span class="fake_anchor" onclick="front_page_toggle('news');">F.A.Q.</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('news');">News</span>&nbsp;-&nbsp;
					<span class="fake_anchor" onclick="front_page_toggle('intro');">Introduction</span>&nbsp;-&nbsp;
					<span>Walkthrough</span>
				</h1>
			</div>

			$walkthrough

		</div>

	</div>

EOF;

###############################################################

	$webpage->add_content($page_content);
	
###############################################################
$html = $webpage->output();

echo $html;

?>
