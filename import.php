<?php

include "classes/import_class.php";

$user = new import_(true);
$webpage = new page("Import/Export", 1, $user->auth);

################################################################

if(!empty($_POST))
{
	$output .= "<div class=\"central_div import_central_div\">";

		$user->handle_upload($_FILES['userfile']['tmp_name']);
		$user->get_import_values();

	$output .= $user->make_import_logbook();

	$output .= "</div>";



	$webpage->add_content($output);
	$html = $webpage->output();
	echo $html;
}
else
{

$share_url = "http://flightlogg.in/logbook.php?share={$user->pilot_id}&token=" . substr(sha256($user->pilot_id) . "poop",0, 10);
	
$upload = <<<EOF

	<div class="central_div">

		{$webpage->ads}

		<form enctype="multipart/form-data" action="import.php{$user->proper_q}" method="post">
		<div class="big_time_box"><input type="hidden" name="MAX_FILE_SIZE" value="500000" />
			Import from file
			<br />
			<input name="userfile" type="file" />
			<br />
			<input type="submit" value="Import" style="margin: 10px" />
		</div>
		</form>
		
		<div class="big_time_box">
			Link others to your logbook<br>
			<a href="$share_url">$share_url</a>
		</div>
		
		<div class="big_time_box">
			Create backup file
			<br />
			<button style="margin: 10px" onclick="window.location = 'backup.php'; return false;">Export</button>
		</div>
		
		<form action="print.php" method="post" target="_blank">
			<div class="big_time_box">
				Create printer friendly logbook
				<br />
			
				<table style="font-size: medium; margin: 10px; margin-left:auto; margin-right: auto;width: 80%">
				<tr>
					<td style="width:50%; text-align:right">
						Student<input type="checkbox" name="student" /><br />
						Instructor<input type="checkbox" name="instructor" /><br />
						First Officer<input type="checkbox" name="fo" /><br />
						Captain<input type="checkbox" name="captain" /><br />
						Flight Number<input type="checkbox" name="flight_number" /><br />
						Aircraft Type<input type="checkbox" name="type" checked="checked" /><br />
						Seperate Simulator<input type="checkbox" name="sim" checked="checked" /><br />
						Remarks<input type="checkbox" name="remarks" checked="checked" /><br />
					</td>
				
					<td style="width:50%; text-align:left">
						<input type="checkbox" name="solo" />Solo<br />
						<input type="checkbox" name="sic" />SIC<br />
						<input type="checkbox" name="dual_recieved" checked="checked" />Dual Received<br />
						<input type="checkbox" name="dual_given" />Dual Given<br />
						<input type="checkbox" name="act_instrument" checked="checked" />Actual Instrument<br />
						<input type="checkbox" name="sim_instrument" />Simulated Instrument<br />
						<input type="checkbox" name="approaches" />Approaches<br />
						<input type="checkbox" name="night" checked="checked" />Night<br />
					</td>
				</tr>
				</table>
			
				<select name="text_size">
					<option value="xx-small">Extra Extra Small Text</option>
					<option value="x-small">Extra Small Text</option>
					<option value="small" selected="selected">Small Text</option>
					<option value="medium">Medium Text</option>
					<option value="large">Large Text</option>
					<option value="x-large">Extra Large Text</option>
					<option value="xx-large">Extra Extra Large Text</option>
				</select>
			
				<select name="color">
					<option value="bw">Grayscale</option>
					<option value="color">Color</option>
				</select>
			
				<br />
			
				<input type="submit" name="submit" value="Generate" style="margin: 10px" />
			</div>
		</form>
		

		
		<div class="import_instructions">
		
			<h1>Importing from Logshare</h1>
			
			Follow the "<strong>Export your logbook</strong>" link. Select "<strong>Text with tab-separated values</strong>". The resulting file will work unmodified.
		
			<h1>Importing from Logbook Pro</h1>
			
			Click on <strong>File -> Export -> Export to Excel</strong> then follow the Excel instructions below.
			
			<div style="text-align: center; padding:10px">
				<img alt="Logbook Pro screenshot" src="import/export.png" />
			</div>
			
			If you do not have Excel, or any other spreadsheet program, then you can do it the alternate/hard way.<br /><br />
			
			Export as "CSV file". Open that file in Notepad. Click on <strong>Edit -> Replace</strong>.
			
			<div style="text-align: center; padding:10px">
				<img alt="Notepad replace screenshot" src="import/replace.png" />
			</div>
			
			You want to replace all commas with a tab character. <strong>Click on Edit-> Replace</strong>. Since you can't enter a tab character by just pressing tab in the Replace dialog,
			you'll have to copy/paste a tab character manually. It may show up as a little square, depending on what operating system and system font you are using.
			Remember, if there are commas in your Remarks, then those commas will be replaced with tabs. If that were to happen, your remarks will cut out after the first comma.
			
			<h1>Importing from an Excel spreadsheet</h1>
			
			Format the spreadsheet so there are no extraneous rows or columns. Also be sure that there are no tab characters or newlines in the columns. Then make sure your column
			headers are named correctly. If the headers do not match what Flightlogg.in' is expecting, that column will not be recognized. For instance, if your day landings
			column is named "Day LNDG", it should be renamed to "Day Landings". Flightlogg.in' tried to guess what a column is if its not named correctly, but there are too many
			possibilities for it to guess them correctly 100% of the time. Usually if they import does not go successfully, the problem is misnamed column headers. Look below under the
			"Technical Details" section for a list of supported column header names.<br /><br />
			
			Once the column headers are correctly named, click <strong>File -> Save As,</strong> and save as a tab separated file by selecting 
			<strong>"Text (Tab delimited) (*.txt)"</strong> at the bottom.
		
			<div style="text-align: center; padding:10px">
				<img alt="Notepad screenshot" src="import/text-tab.png" />
			</div>
			
			Excel spreadsheets created by Logbook Pro by default have newline characters in the header row (such as in the screenshot above). You must remove these new
			line characters before importing into your FlightLogg.in' logbook. If the resulting tab separated text file looks like the image below (with
			<strong>Format -> Word Wrap</strong> turned off), then you need to remove the newline characters.
			
			<div style="text-align: center; padding:10px">
				<img alt="Notepad screenshot" src="import/notepad-before.png" />
			</div>
			
			Remove the newlines so the header row all appears on one line.
			
			<div style="text-align: center; padding:10px">
				<img alt="Excel screenshot" src="import/notepad-after.png" />
			</div>
			
			It should then be ready to be imported into FlightLogg.in'!
		
			<h1>Technical Details</h1>
		
			<ul>
			
				<li>The backup file must be a plain text file in the following format:<br /><br />
			
				The first line must contain the title of each column, each item separated by a tab character:<br /><br />
			
				Date <i>[tab]</i> Route <i>[tab]</i> Plane [...] <br /><br />
			
				After that, each line must contain tab separated values that correspond with the header row:<br /><br />
			
				10/13/2004 <i>[tab]</i> LGA-JFK <i>[tab]</i> N1234 [...]<br /><br />
				
				If you have an Excel document that is set up this way, usually exporting as a tab separated file will yield a file that is compatible.<br /><br /></li>
				
				<li>If you already have a digitalized logbook in Excel or any other spreadsheet application and are having trouble
				getting it to import, try asking on the forums.<br /><br /></li>
				
				<li>Comma separated values will not work. Many times, commas are present in the comments column, which makes importing troublesome.<br /><br /></li>
			
				<li>The following columns will be accepted: <strong>'Date'</strong> (in various formats) , <strong>'Plane'</strong> (containing only the tailnumber),
				<strong>'Type'</strong> (containing the type of aircraft such as 'PA-28'), <strong>'Route'</strong>, <strong>'Total'</strong>, <strong>'Solo</strong>,
				<strong>'SIC'</strong>, <strong>'Dual Received'</strong>, <strong>'Dual Given'</strong>, <strong>'Actual Instrument'</strong>, <strong>'Simulated Instrument'</strong>,
				<strong>'Approaches'</strong>, <strong>'Night'</strong>, <strong>'Cross Country'</strong>, <strong>'Day Landings'</strong>, <strong>'Night Landings'</strong>,
				<strong>'Student'</strong>, <strong>'Instructor'</strong> (including the name of the instructor), <strong>'Captain'</strong>, <strong>'First Officer'</strong>,
				<strong>'Flight Number'</strong>, <strong>'Remarks'</strong>, and two additional columns,
				<strong>'Flying'</strong>, and <strong>'Non-Flying'</strong>. Any non-supported column found will be ignored.<br /><br />
				
				In addition to the above, any column named by Logbook Pro's export to Excel feature will be recognized (as long as the newlines are removed), with one exception.
				The "Instructor" column needs to be renamed to either "As Instructor" or "Dual Given" or otherwise it will think it's a column with the name of
				the instructor<br /><br /></li>

				<li>The purpose of the 'Flying' columns is to document flying events such as Holding (H), Tracking (T), pilot (P) and CFI (C) checkrides,
				Flight Reviews (B), and Instrument Proficiency Checks (I). Just add each corresponding letter to that column to document that event.<br /><br /></li>
			
				<li>The 'Non-Flying' column works the same way as the 'Flying', except it is used to document non-flying events such as 1st Class Medical (1),
				2nd Class Medical (2), 3rd Class Medical (3), Student Signoffs (S), and CFI Refreshers (R). Only one non-flying code can exist per flight.<br /><br /></li>
			
				<li>If any entries are surrounded by quotation marks (""), the quotation marks will be automatically removed. Any quotation marks appearing in the
				middle of an entry will be kept.<br /><br /></li>
			
				<li>For best results, make sure tab characters exist only to seperate the data between each columns.<br /><br /></li>
			
				<li>Importing will not replace any entries, it will only add them. If accidentally mess everything up 
				and you would like your logbook reset, make a request on the forums.<br /><br /></li>
			</ul>
		</div>
	</div>
EOF;



##############################################################

	$webpage->add_content($upload);

###############################################################
$html = $webpage->output();

echo $html;
}
?>
