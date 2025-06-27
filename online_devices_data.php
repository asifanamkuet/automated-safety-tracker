<?php
//index.php
require('database_connection.php');

if(!isset($_SESSION["type"]))
{
 header("location: login.php");
}
  $output = '';
  $time = time()-2;
  //echo $time;
  $query = "SELECT devices_id FROM coordinate_details WHERE time > $time";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $count = $statement->rowCount();
  $output .= '
  <div class="table-responsive">
   <div align="right">
    '.$count.' Device(s) Online
   </div>
   <table class="table table-bordered table-striped">
    <tr>
     <th>No</th>
     <th>Device ID</th>
     <th>Device Name</th>
    </tr>
  ';

  $i = 0;
  foreach($result as $row){
  	$devices_id = $row['devices_id'];
  	$query_device_name = "SELECT * FROM devices_details WHERE devices_id = '$devices_id'";
	$statement1 = $connect->prepare($query_device_name);
	$statement1->execute();
	$devices_name = $statement1->fetchAll();	
	$i = $i + 1;
	$output .= '
	<tr> 
	<td>'.$i.'</td>
	<td>'.$row["devices_id"].'</td>
	<td>'.$devices_name[0]["devices_name"].'</td>
	</tr>
	';
  }
  $output .= '</table></div>';
  echo $output;
?>
