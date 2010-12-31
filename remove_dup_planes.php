<?php

include "classes/planes_class.php";
$user = new planes(true);

$sql = "DELETE from planes where planes.pilot_id = {$user->pilot_id} and (select count(*) from flights where plane_id=planes.plane_id) = 0";

mysql_query($sql);

header('Location: planes.php');
?>
