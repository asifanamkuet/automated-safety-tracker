<?php
require('database_connection.php');
if(!isset($_SESSION["type"]))
{
 header("location: login.php");
}
$check2 = "SELECT `devices_id`,`latitude`,`longitude` FROM `coordinate_details` ORDER BY `coordinate_details`.`devices_id` DESC, `coordinate_details`.`id` ASC";
$check2_statement = $connect->prepare($check2);
$check2_statement->execute();
$check2_result = $check2_statement->fetchAll();
$count = $check2_statement->rowCount();
if ($count>2) {
}
else{
    die('<center><h1>Data is not recorded yet!!</h1><center>');
}
  function get_devices_name($devices_id){
  	  global $connect;
	  $query = "SELECT devices_name FROM devices_details WHERE devices_id='$devices_id'";
	  $statement = $connect->prepare($query);
	  $statement->execute();
	  $result = $statement->fetchAll();
	  if (!empty($result)) {
	  	 return $result[0]['devices_name'];
	  }
	 
  }
  function get_devices_id($devices_name){
  	  global $connect;
	  $query = "SELECT devices_id FROM devices_details WHERE devices_name='$devices_name'";
	  $statement = $connect->prepare($query);
	  $statement->execute();
	  $result = $statement->fetchAll();
	  if (!empty($result)) {
	  	 return $result[0]['devices_id'];
	  }
	 
  }

  function get_devices_color($devices_name){
  	  global $connect;
	  $query = "SELECT devices_color FROM devices_details WHERE devices_name='$devices_name'";
	  $statement = $connect->prepare($query);
	  $statement->execute();
	  $result = $statement->fetchAll();
	  if (!empty($result)) {
	  	 return $result[0]['devices_color'];
	  }
	 
  }
$query = "SELECT * FROM boundary_coordinate ORDER BY `boundary_coordinate`.`id` ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result 	= $statement->fetchAll();
$westLong 	= $result[0]['westLong'];
$eastLong 	= $result[0]['eastLong'];
$northLat	= $result[0]['northLat'];
$southLat 	= $result[0]['southLat'];
list($width, $height) = getimagesize('plan1.jpg');
$query = "SELECT * FROM plan_calibration ORDER BY `plan_calibration`.`id` ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$count = $statement->rowCount();
$height_calibration = $result[0]['height_calibration'];
$width_calibration = $result[0]['width_calibration'];
$width    = $width+$width_calibration;
$height   = $height+$height_calibration;
$query = "SELECT `devices_id`,`latitude`,`longitude` FROM `coordinate_details` ORDER BY `coordinate_details`.`devices_id` DESC, `coordinate_details`.`id` ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$i = 0;
$id = 0;
$tmp = 0;
foreach ($result as $key => $value) {
	$x = ($width-(($eastLong-$value['longitude'])/($eastLong-$westLong))*$width);
	$y = ($height-(($northLat-$value['latitude'])/($northLat-$southLat))*$height);
	$data[$value['devices_id']][$i] = array($i => array($x,$y));
	$i++;
}
$devices_key = array_keys($data);
foreach ($devices_key as $devices => $devices_id) {
	$query1 = "SELECT * FROM `devices_details` WHERE devices_id='$devices_id'";
	$statement1 = $connect->prepare($query1);
	$statement1->execute();
	$result1 = $statement1->fetchAll();
	foreach ($result1 as $key_color => $color) {
		$devices_name[$devices_id] = $color['devices_name'];
		$devices_color[$devices_id] = $color['devices_color']; 
	}
}
	$dest = imagecreatefromjpeg('plan1.jpg');
	imagesetthickness($dest,3);
foreach ($devices_color as $devices => $color_id) {
	list($red, $green, $blue) = sscanf($color_id, "#%02x%02x%02x");

	$color[$color_id] = @imagecolorallocate($dest, $red, $green, $blue);
	foreach ($data[$devices] as $coordinate_key => $coordinate_value) {
		$plot_x[] = $data[$devices][$coordinate_key][$coordinate_key][0];
		$plot_y[] = $data[$devices][$coordinate_key][$coordinate_key][1];
		if ($id+1==count($data[$devices])) {
			$id = 0;
			break;
		}
		else{
			imageline($dest,
			$data[$devices][$coordinate_key][$coordinate_key][0],
			$height-$data[$devices][$coordinate_key][$coordinate_key][1],
			$data[$devices][$coordinate_key+1][$coordinate_key+1][0],
			$height-$data[$devices][$coordinate_key+1][$coordinate_key+1][1], $color[$color_id]);	
		}
		$id++;
	}
}
ob_start();
imagejpeg($dest);
$rawImageBytes = ob_get_clean();
$type = "jpg";
$img = '
<table border="0">
		<tr>
		<td align="center"><img src="data:image/' . $type . ';base64,' . base64_encode($rawImageBytes).'"><td align="center"><td align="center">
<table class="table table-bordered table-striped">
		<tr align="center">
		<td>Device Name</td>
		<td>Legend</td>
		</tr>';
imagedestroy($dest);
  $query = "SELECT devices_name,devices_color FROM devices_details  ORDER BY `devices_details`.`id` ASC";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $count = $statement->rowCount();
  foreach ($result as $key => $value) {
  	list($red, $green, $blue) = sscanf($value['devices_color'], "#%02x%02x%02x");
	$pixx = imagecreate(10, 10);
	imagecolorallocate($pixx, $red, $green, $blue);
	ob_start();
	imagejpeg($pixx);
	$rawImageBytes = ob_get_clean();
	$type = "jpg";
	$sign = '<img src="data:image/' . $type . ';base64,' . base64_encode($rawImageBytes).'">';
	imagedestroy($pixx);
  	$img.='<tr align="center">
		<td>'.$value['devices_name'].'</td>
		<td>'.$sign.'</td>
		</tr>';
  }
$img.='</table>
		<table class="table table-bordered table-striped">
		<tr align="center">
			<td align="center">Name</td>
			<td align="center">Hazard Zone</td>
			<td align="center">Time</td>
			<td align="center">Number Enrty</td>
		</tr>
		<tr align="center">';
$query = "SELECT `devices_id`,`status`,`time` FROM `coordinate_details` WHERE `status` != 0 ORDER BY `coordinate_details`.`time` DESC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach ($result as $key => $value) {
	$hazard_zone[$value['devices_id']][$key]= $value['status'];
	$time[$value['devices_id']][] = $value['time'];
}
//print_r($hazard_zone);
foreach ($hazard_zone as $key => $value) {
	$name = get_devices_name($key);
	$entered_device[$name] = array_count_values($value);
	
}
//print_r($entered_device);
//echo "break\n";
//print_r($time);
foreach ($entered_device as $entered_device_name => $zone) {

	list($red, $green, $blue) = sscanf(get_devices_color($entered_device_name), "#%02x%02x%02x");
	$pix = imagecreate(10, 10);
	imagecolorallocate($pix, $red, $green, $blue);
	ob_start();
	imagejpeg($pix);
	$rawImageBytes = ob_get_clean();
	$type = "jpg";
	$sign = '<img src="data:image/' . $type . ';base64,' . base64_encode($rawImageBytes).'">';
	imagedestroy($pix);
	$img.='<td align="center" rowspan="'.count($entered_device[$entered_device_name]).'">'.$entered_device_name.' '.$sign.'</td>';
	foreach ($zone as $zone_no => $no_of_times) {
		//echo get_devices_id($entered_device_name);

		$last_time = strftime('%I:%M %p', $time[get_devices_id($entered_device_name)][$tmp+$no_of_times-1]+14400);
		$img.="<td align=\"center\">$zone_no</td>";
		$img.="<td align=\"center\">$last_time</td>";
		$img.="<td align=\"center\">$no_of_times</td></tr>";
		$tmp = $tmp+$no_of_times;
	}
	$tmp = 0; 
	$img.='</tr><tr align="center">';
}	
$img.='</table>
<table>';
	  
echo "$img";
?>

