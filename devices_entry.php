<?php
//index.php
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
if ($count>0) {
}
else{
    $die_message = '<center><h1>Data is not recorded yet!!</h1><center>';
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
  function get_devices_color($devices_id){
  	  global $connect;
	  $query = "SELECT devices_color FROM devices_details WHERE devices_id='$devices_id'";
	  $statement = $connect->prepare($query);
	  $statement->execute();
	  $result = $statement->fetchAll();
	  if (!empty($result)) {
	  	 return $result[0]['devices_color'];
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
  $message = "";
  $query = "SELECT id,devices_id FROM coordinate_details  ORDER BY `coordinate_details`.`id` ASC";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $count = $statement->rowCount();
  if ($count>0) {
	foreach ($result as $key => $value) {
	$devices_id[$value['id']] = $value['devices_id'];
	}
	$result = array_unique($devices_id);
	if (isset($_POST['submit'])) {
	foreach ($result as $i => $value) {
	$devices_id = $_POST['devices_id_'.$i];
	$devices_name = $_POST['devices_name_'.$i];
	$devices_color = $_POST['devices_color_'.$i];
	$devices_mobile = $_POST['devices_mobile_'.$i];
	if (empty($devices_name) || empty($devices_color) || empty($devices_mobile)) {
	$message = "<label>Every Fields are required</label>";
	}
	else{
		$sql = "INSERT INTO `devices_details`(`id`,`devices_id`,`devices_name`,`devices_mobile`,`devices_color`) VALUES ('$i','$devices_id','$devices_name','$devices_mobile','$devices_color')";
		if(mysql_query($sql,$conn)){
			$message = '<label>Saved Successfully</label>';
		}
	else
	{
	  $sql = "UPDATE `devices_details` SET `devices_name` = '$devices_name',`devices_color` = '$devices_color',`devices_mobile` = '$devices_mobile'  WHERE `devices_id` = '$devices_id'";
	  if(mysql_query($sql,$conn))
	  {
	  $message = '<label>Update Successfully</label>';
	  }
	}
	}
	}
	}
  

}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Devices Entry</title>
    <?php include('header.php'); ?>
   <div class="panel panel-default">
    <div class="panel-heading">Devices Entry</div>
    <div id="user_login_status" class="panel-body">
    	<span class="text-danger"><?php echo $message; if (isset($die_message)) { die($die_message); } if (isset($_GET['error'])) { echo $_GET['error']; } ?></span>
    	<form action="devices_entry.php" method="POST">
    	<div class="table-responsive">
   		<table class="table table-bordered table-striped">
	    <tr>
	     <th>Device ID</th>
	     <th>Device Name</th>
	     <th>Device Mobile Number</th>
	     <th>Device Color</th>
	    </tr>
    	<?php
    	foreach ($result as $key => $value) {
    		echo 
    		"<tr>
    		<td><input type=\"text\" name=\"devices_id_$key\" value=\"$value\" readonly></td>
    		<td><input type=\"text\" name=\"devices_name_$key\" value=\"".get_devices_name($value)."\"></td>
    		<td><input type=\"text\" name=\"devices_mobile_$key\" value=\"".get_devices_mobile($value)."\"></td>
    		<td><input type=\"text\" name=\"devices_color_$key\" value=\"".get_devices_color($value)."\"></td>
    		</tr>";
    	}
    	?>
    	<tr>
    		<td colspan="4"><input type="submit" name="submit" class="button" value="Save"></td>
    	</tr>
    </table>
</div>
</form>
</div>
</div>
</div>
</body>
</html>