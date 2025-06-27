<?php
require('database_connection.php');	
if(!isset($_SESSION["type"]))
{
 header("location: login.php");
}
function is_in_the_area($var_x,$var_y){
	global $connect,$height;
	$var_y = $height-$var_y;
	$query = "SELECT * FROM `hazard_area` ORDER BY `hazard_area`.`id` ASC";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach ($result as $key => $value) {
		$x1[$value['id']] = $value['x1'];
		$y1[$value['id']] = $value['y1'];
		$x2[$value['id']] = $value['x2'];
		$y2[$value['id']] = $value['y2'];
	}
	foreach ($x1 as $key => $value) {
		if ($var_x>=$x1[$key] && $var_x<=$x2[$key]) {
			if ($var_y>=$y2[$key] && $var_y<=$y1[$key]) {
				return $key;
			}
		}
	}
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

function get_devices_mobile($devices_id){
  	  global $connect;
	  $query = "SELECT devices_mobile FROM devices_details WHERE devices_id='$devices_id'";
	  $statement = $connect->prepare($query);
	  $statement->execute();
	  $result = $statement->fetchAll();
	  if (!empty($result)) {
	  	 return $result[0]['devices_mobile'];
	  }
	 
  }
function get_hazard_zone($zone_id){
  	  global $connect;
	  $query = "SELECT hazard_zone_name FROM hazard_area WHERE id='$zone_id'";
	  $statement = $connect->prepare($query);
	  $statement->execute();
	  $result = $statement->fetchAll();
	  if (!empty($result)) {
	  	 return $result[0]['hazard_zone_name'];
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
$width	  = 930;
$height   = 550;
$mask 	 	= 'ASTBD';
$query = "SELECT `devices_id`,`latitude`,`longitude`,`time` FROM `coordinate_details` ORDER BY `coordinate_details`.`devices_id` DESC, `coordinate_details`.`id` ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach ($result as $key => $value) {
	$x = ($width-(($eastLong-$value['longitude'])/($eastLong-$westLong))*$width);
	$y = ($height-(($northLat-$value['latitude'])/($northLat-$southLat))*$height);
	if (is_in_the_area($x,$y)) {
		$status = is_in_the_area($x,$y);
		//echo $status.' '.$value['longitude'].'<br>';
		$longitude = $value['longitude'];
		$devices_id = $value['devices_id'];
		$number = get_devices_mobile($devices_id);
		$time 	= strftime('%I:%M %p', $value['time']+14400);
		$sms = "Warning!! MR.".get_devices_name($devices_id).", you are in ".get_hazard_zone($status)." at ".$time.". Please change your position. AST-BD";
		try{
			$soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
			$paramArray = array(
			'apiKey'  => "8c2cc372-e", 
			'messageText'  => $sms,
			'numberList'  => $number,
			'smsType'  => "TEXT",
			'maskName'  => '',
			'campaignName'  => '',
			);
			$value = $soapClient->__call("NumberSms", array($paramArray));
			echo $value->NumberSmsResult;
			} catch (Exception $e) {
			echo $e->getMessage();
			}
		$sql = "UPDATE `coordinate_details` SET `status` = '$status' WHERE `longitude` = '$longitude'";
		if(mysql_query($sql,$conn))
		{
			echo '<label>Update Successfully</label>';
		}
	 }
}



?>