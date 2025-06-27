<?php
if ($_SERVER['HTTP_HOST']!="localhost") {
	$dbname = "bicvta5rvhpjer8yntq8";
	$user 	= "uhjpsjf42cddttvr";
	$pass 	= "xKHgw1T7dW800q7VPW5a";
}
else{
	$dbname = "online_user";
	$user 	= "root";
	$pass 	= "";
}
$smsuser 	= '01765403041';
$smspass 	= 'anambd78';
$mask 	 	= 'ASTBD';
$width	  = 930;
$height   = 550;
$connect = new PDO('mysql:host=bicvta5rvhpjer8yntq8-mysql.services.clever-cloud.com;dbname='.$dbname, $user, $pass);
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
$conn  = @mysql_connect("localhost", $user, $pass) or die("could not connect");
$rs = mysql_select_db($dbname, $conn) or die("could not connect to database");
if(isset($_GET['hash'])){
	if ($_GET['hash']=="ef549f9301ebf3963f64c391b7d67943") {
		$devices_id = $_GET['id'];
		$latitude 	= $_GET['lat'];
		$longitude 	= $_GET['lon'];
		$x 			= ($width-(($eastLong-$longitude)/($eastLong-$westLong))*$width);
		$y 			= ($height-(($northLat-$latitude)/($northLat-$southLat))*$height);
		$time  		= time();
		$hazard 	= is_in_the_area($x,$y);
		if ($hazard) {
			$status = is_in_the_area($x,$y);
				$number = get_devices_mobile($devices_id);
				$time 	= strftime('%I:%M %p', time()+14400);
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
							}
		else{
			$status = 0;
		}
		$sql = "INSERT INTO `coordinate_details`(`devices_id`,`latitude`,`longitude`,`time`,`status`) VALUES ('$devices_id','$latitude','$longitude','$time','$status')";
		if(mysql_query($sql,$conn)){
			echo 'Saved Successfully';
		}
	}
	else{
		echo "not ok";
	}
}
?>