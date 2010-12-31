<?php
include "main_classes.php";

class records extends auth
{

	function get_records()
	{
	
		$sql = sprintf(
			"SELECT records
			FROM pilots
			WHERE pilot_id='%s'
			LIMIT 1",
			mysql_real_escape_string($this->pilot_id),
			mysql_real_escape_string($this->salted_hash)
			);
		
		$result = mysql_query($sql);
	
		$records = mysql_fetch_array($result);
		
		$this->records = $records[0];
	
	}
	
	function submit_records($records)
	{
		$records = str_replace("<", "&lt;", $records);
		$records = str_replace(">", "&gt;", $records);
	
		if(empty($records))
			$records = 'NULL';
			
		else	$records = "'" . $records . "'";
	
		$sql = sprintf(
			"UPDATE pilots
			SET records = %s
			WHERE pilot_id='%s'
			LIMIT 1",
			$records,
			$this->pilot_id
			);
		
		mysql_query($sql);
	
	}
}
