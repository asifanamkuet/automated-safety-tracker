<?php
//index.php
require('database_connection.php');

if(!isset($_SESSION["type"]))
{
 header("location: login.php");
}
  $message = "";
  if (isset($_POST['submit'])) {
		if (empty($_POST['WestLong']) || empty($_POST['EastLong']) || empty($_POST['NorthLat']) || empty($_POST['SouthLat'])) {
		$message = "<label>Every Fields are required</label>";
		}
		else{
		  $WestLong = $_POST['WestLong'];
		  $EastLong = $_POST['EastLong'];
		  $NorthLat = $_POST['NorthLat'];
		  $SouthLat = $_POST['SouthLat'];
		  $sql = "UPDATE `boundary_coordinate` SET `westLong` = '$WestLong',`eastLong` = '$EastLong',`northLat` = '$NorthLat',`southLat` = '$SouthLat'  WHERE `id` = '0'";
		  $rs = mysql_select_db($dbname, $conn) or die("could not connect to database");
		  if(mysql_query($sql,$conn))
		  {
		  	$message = 'Update Successfully';
		  }
		}

}
  $query = "SELECT * FROM boundary_coordinate ORDER BY `boundary_coordinate`.`id` ASC";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $count = $statement->rowCount();
  $boundary['WestLong'] = $result[0]['westLong'];
  $boundary['EastLong'] = $result[0]['eastLong'];
  $boundary['NorthLat'] = $result[0]['northLat'];
  $boundary['SouthLat'] = $result[0]['southLat'];

?>
<!DOCTYPE html>
<html>
<head>
  <title>Insert Boundary Coordinate</title>
    <?php include('header.php'); ?>
   <div class="panel panel-default">
    <div class="panel-heading">Insert Boundary Coordinate</div>
    <div id="user_login_status" class="panel-body">
    	<span class="text-danger"><?php echo $message; if (isset($_GET['error'])) {
        echo $_GET['error'];
      } ?></span>
    	<form action="boundary.php" method="POST">
    	<div class="table-responsive">
   		<table class="table table-bordered table-striped">
	    <tr>
	     <th>Position</th>
	     <th>Value</th>
	    </tr>
    	<?php
    	foreach ($boundary as $key => $value) {
    		echo 
    		"<tr>
    		<td>$key</td>
    		<td><input type=\"text\" name=\"$key\" value=\"".$value."\" min=\"0\" max=\"10\"></td>
    		</tr>";
    	}
    	?>
    	<tr>
    		<td colspan="3"><input type="submit" name="submit" class="button" value="Save"></td>
    	</tr>
    </table>
</div>
</form>
</div>
</div>
</div>
</body>
</html>