<?php

include "main_classes.php";

class planes extends auth
{

	function get_planes_by_manufacturer()
	{
		$sql = sprintf("SELECT *
				FROM planes
				WHERE pilot_id='%s'
				ORDER BY CASE WHEN manufacturer IS NULL THEN 1 ELSE 0 END, manufacturer, tail_number",
				mysql_real_escape_string($this->pilot_id)
				);

		$result = mysql_query($sql);

		$this->raw_planes = $result;
		
		return $result;
	
	}
	
	function create_new_plane($POST)
	{
	
		$manufacturer = $POST['manufacturer_new'] == "" || $POST['manufacturer_new'] == "Manufacturer" ? 'NULL' : "'" . mysql_real_escape_string($POST['manufacturer_new']) . "'"; 
		$model = $POST['model_new'] == "" ? 'NULL' : "'" . mysql_real_escape_string($POST['model_new']) . "'";
		
		$tailwheel = $POST['tailwheel_new'] == "" ? "'0'" : "'1'";
		$type_rating = $POST['type_rating_new'] == "" ? "'0'" : "'1'";
	
		$sql = sprintf("INSERT INTO planes (`pilot_id`, `tail_number`, `type`, `model`, `manufacturer`, `category_class`, `tailwheel`, `type_rating`)
				VALUES('%s','%s','%s','%s',%s,'%s',%s,%s)",
				
			mysql_real_escape_string($this->pilot_id),
			mysql_real_escape_string($POST['tail_number_new']),
			mysql_real_escape_string($POST['type_new'] == "Type" ? "" : $POST['type_new']),
			mysql_real_escape_string($POST['model_new'] == "Model" ? "" : $POST['model_new']),
			$manufacturer,
			mysql_real_escape_string($POST['category_class_new']),
			$tailwheel,
			$type_rating
			);

		mysql_query($sql);
		
		######################################################################################################################
		
		$tags = $POST['tags_new'];
		$plane_id = mysql_insert_id();					//get the plane_id of the newly created plane
		
		$this->make_tags($tags, $plane_id);
		
	}
		
	function save_single_plane($POST, $plane_id)
	{
		$tail_number = "'" . mysql_real_escape_string($POST['tail_number_' . $plane_id]) . "'";
		$type = empty($POST['type_' . $plane_id]) ? 'NULL' : "'" . mysql_real_escape_string($POST['type_' . $plane_id]) . "'";
		$model = empty($POST['model_' . $plane_id]) ? 'NULL' : "'" . mysql_real_escape_string($POST['model_' . $plane_id]) . "'";
		$manufacturer = empty($POST['manufacturer_' . $plane_id]) ? 'NULL' : "'" . mysql_real_escape_string($POST['manufacturer_' . $plane_id]) . "'";
		$category_class = $POST['category_class_' . $plane_id];
		$tailwheel = empty($POST['tailwheel_' . $plane_id]) ? '0' : "'1'";
		$type_rating = empty($POST['type_rating_' . $plane_id]) ? '0' : "'1'";
	
		$sql = "UPDATE planes set 
			`tail_number` = $tail_number,
			`type` = $type,
			`model` = $model,
			`manufacturer` = $manufacturer,
			`category_class` = '$category_class',
			`tailwheel` = $tailwheel,
			`type_rating` = $type_rating
			
			WHERE `pilot_id`='{$this->pilot_id}' AND `plane_id`='$plane_id'";
			
		//print $sql;
			
		mysql_query($sql);
		
		#########################################################################
		$sql = "UPDATE tags SET `plane_id` = '-$plane_id' WHERE `plane_id` = $plane_id";
		
		mysql_query($sql);			//delete all existing tags for this plane
		
		//print $sql;
		
		$tags = $POST['tags_' . $plane_id];
		$tags_check = trim(str_replace(",", "", $tags));		//to make sure the user doesnt just enter commas and nothing else
		
		if(!empty($tags_check))
		{
			$tags_array = explode(",", $tags);			//make the array from the tags string
		
			array_walk($tags_array, "trim_array");			//trim whitespace
			
			$tags_array = array_unique($tags_array);		//remove duplicates

			foreach($tags_array as $tag)
			{
				if(!empty($tag))				//don't enter empty tags into the database
					mysql_query("INSERT INTO tags (`plane_id`, `tag`) VALUES ('$plane_id','" . mres($tag) . "')");

			}
		}	
	}
	
	function delete_plane($plane_id)
	{
		$sql = "UPDATE planes SET `pilot_id` = '-{$this->pilot_id}' WHERE `plane_id`='{$plane_id}' AND `pilot_id` = '{$this->pilot_id}' ";
		mysql_query($sql);
		
		$sql = "UPDATE tags SET `plane_id` = '-$plane_id' WHERE `plane_id`='{$plane_id}'";
		mysql_query($sql);
		
		return;
	}
	
	function save_all_planes($POST)
	{
		$all_ids = explode(" ", $POST['all_ids']);
	
		foreach($all_ids as $id)
		{
			$this->save_single_plane($POST, $id);
		
		}
	}
}
?>
