<?php
//index.php
require('database_connection.php');

if(!isset($_SESSION["type"]))
{
 header("location: login.php");
}
  $message = "";
  $err = 0;
  if (isset($_POST['submit'])) {
    foreach ($_POST as $key => $value) {
      if (empty($_POST[$key])) {
        $err+=1;
      }
      else{
        $err=$err;
      }
    }
		if ($err>0) {
		$message = "<label>Every Fields are required</label>";
		}
		else{
      foreach ($_POST as $key => $value) {
        if ($key!="submit") {
          $data = explode("_", $key);
          $type1 = $data[1];
          $type2 = $data[0]+1;
          $sql = "UPDATE `hazard_area` SET `$type1` = '$value' WHERE `id` = '$type2'";
          $rs = mysql_select_db($dbname, $conn) or die("could not connect to database");
          if(mysql_query($sql,$conn))
          {
            $message = 'Update Successfully';
          }
        }
        
  		}
    }
}
$query = "SELECT * FROM hazard_area ORDER BY `hazard_area`.`id` ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Insert Hazard Area</title>
    <?php include('header.php'); ?>
   <div class="panel panel-default">
    <div class="panel-heading">Insert Hazard Area</div>
    <div id="user_login_status" class="panel-body">
    	<span class="text-danger"><?php echo $message; if (isset($_GET['error'])) {
        echo $_GET['error'];
      } ?></span>
    	<form action="hazard.php" method="POST">
    	<div class="table-responsive">
   		<table class="table table-bordered table-striped">
	    <tr>
	     <th>Hazard Zone Name</th>
	     <th>X<sub>1</sub></th>
       <th>Y<sub>1</sub></th>
       <th>X<sub>2</sub></th>
       <th>Y<sub>2</sub></th>
	    </tr>
    	<?php
    	foreach ($result as $key => $value) {
    		echo 
    		"<tr>
    		<td>".$value['hazard_zone_name']."</td>
    		<td><input type=\"text\" name=\"".$key.'_x1'."\" value=\"".$value['x1']."\" min=\"0\" max=\"10\"></td>
        <td><input type=\"text\" name=\"".$key.'_y1'."\" value=\"".$value['y1']."\" min=\"0\" max=\"10\"></td>
        <td><input type=\"text\" name=\"".$key.'_x2'."\" value=\"".$value['x2']."\" min=\"0\" max=\"10\"></td>
        <td><input type=\"text\" name=\"".$key.'_y2'."\" value=\"".$value['y2']."\" min=\"0\" max=\"10\"></td>
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