<?php
//index.php
require('database_connection.php');

if(!isset($_SESSION["type"]))
{
 header("location: login.php");
}
   $message='';
if (isset($_POST['Coordinate_Details']) || isset($_POST['Devices_Details'])) {
	if (isset($_POST['Coordinate_Details']) && $_POST['Coordinate_Details']=='Delete') {
		  $delete = "Delete FROM `coordinate_details`";
  		  $delete_statement = $connect->prepare($delete);
  		  $delete_statement->execute();
          $delete_result = $delete_statement->fetchAll();
          if ($delete_result>0) {
          	$message = "<label>Coordinate_Details Table Deleted Successfully";
          }
          else{
          	$message = "<label>Coordinate_Details Table is Empty";
          }
	}
	else if(isset($_POST['Devices_Details']) && $_POST['Devices_Details']=='Delete') {
		  $delete = "Delete FROM `devices_details`";
  		  $delete_statement = $connect->prepare($delete);
  		  $delete_statement->execute();
          $delete_result = $delete_statement->fetchAll();
          if ($delete_result>0) {
          	$message = "<label>Devices_Details Table Deleted Successfully";
          }
          else{
          	$message = "<label>Devices_Details Table is Empty";
          }
	}
}
  $query = "SELECT id,devices_id FROM coordinate_details";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $count['Coordinate_Details'] = $statement->rowCount();

  $query = "SELECT id,devices_id FROM devices_details";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $count['Devices_Details'] = $statement->rowCount();
  
?>
<!DOCTYPE html>
<html>
<head>
  <title>Database Management</title>
    <?php include('header.php'); ?>
   <div class="panel panel-default">
    <div class="panel-heading">Database Management</div>
    <div id="user_login_status" class="panel-body">
    	<span class="text-danger"><?php echo $message; ?></span>
    	<form action="" method="POST">
    	<div class="table-responsive">
   		<table class="table table-bordered table-striped">
	    <tr>
	     <th>Table Name</th>
	     <th>Table Count</th>
	     <th>Action</th>
	    </tr>
    	<?php
    	foreach ($count as $key => $value) {
    		echo 
    		"<tr>
    		<td>$key</td>
    		<td>$value</td>
    		<td><input type=\"submit\" name=\"$key\" class=\"delete\" value=\"Delete\"></td>	
    		</tr>";
    	}
    	?>
    </table>
</div>
</form>
</div>
</div>
</div>
</body>
</html>	