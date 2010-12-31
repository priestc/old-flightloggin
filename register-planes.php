<?

include "machine_information.php";

header("content-type: text/plain");

$sql= "SELECT planes.tail_number as tail_number, planes.plane_id as plane_id, planes.manufacturer as manufacturer, planes.pilot_id as pilot_id,
		planes.type as type, planes.model as model, planes.category_class as category_class, tags.tag as tag
	FROM planes LEFT JOIN tags
	ON tags.plane_id = planes.plane_id
	WHERE pilot_id > 0";

$result = mysql_query($sql);


while($row = mysql_fetch_assoc($result))
{
	if($tail_number == $row['tail_number'])		//if the last tailnumber equals the current tailnumber...
		print ' "' . addslashes($row['tag']) . '"';
	else
	{
	    if($row['tag'] != "")
	        $tag = '"' . addslashes($row['tag']) . '"';
	    else
	        $tag = "";
	        
	    print "')\nPlane.objects.get_or_create(pk=\"{$row['plane_id']}\",tailnumber=\"{$row['tail_number']}\",manufacturer=\"{$row['manufacturer']}\",model=\"{$row['model']}\",type=\"{$row['type']}\",cat_class=\"{$row['category_class']}\",tags='$tag";
    }
    
	$tail_number = $row['tail_number'];
}
	
?>
