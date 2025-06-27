<?php
//index.php
require('database_connection.php');

if(!isset($_SESSION["type"]))
{
 header("location: login.php");
}
  $message = "";
  if(isset($_POST['submit']) && isset($_FILES['image'])){
    $file_name = $_FILES['image']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['image']['tmp_name'];
    $file_type=$_FILES['image']['type'];
    $tmp = explode('.',$_FILES['image']['name']);
    $file_ext=strtolower(end($tmp));
    $extensions= array("jpg");
    if(in_array($file_ext,$extensions)=== false){
       $message="Extension not allowed, please choose a JPEG or PNG file.";
    }
    if($file_size > 2097152){
       $message='File size must be excately 2 MB';
    }
    if(empty($errors)==true){
       move_uploaded_file($file_tmp,'plan1.jpg');
       $message =  "Upload Successful";
    }
    if (empty($_POST['height_calibration']) || empty($_POST['width_calibration'])) {
    $message = "<label>Every Fields are required</label>";
    }
    else{
      $height_calibration = $_POST['height_calibration'];
      $width_calibration = $_POST['width_calibration'];
      $sql = "UPDATE `plan_calibration` SET `height_calibration` = '$height_calibration',`width_calibration` = '$width_calibration' WHERE `id` = '0'";
      $rs = mysql_select_db($dbname, $conn) or die("could not connect to database");
      if(mysql_query($sql,$conn))
      {
        $message = 'Update Successfully';
      }
    }
  }
  
  $query = "SELECT * FROM plan_calibration ORDER BY `plan_calibration`.`id` ASC";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $count = $statement->rowCount();
  $img['height_calibration'] = $result[0]['height_calibration'];
  $img['width_calibration'] = $result[0]['width_calibration'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Plan</title>
    <?php include('header.php'); ?>
   <div class="panel panel-default">
    <div class="panel-heading">Upload Plan</div>
    <div id="user_login_status" class="panel-body">
    	<span class="text-danger"><?php echo $message; if (isset($_GET['error'])) {
        echo $_GET['error'];
      } ?></span>
    	<form action="" method="POST" enctype="multipart/form-data">
         
    	<div class="table-responsive">
   		<table class="table table-bordered table-striped">
      <tr>
       <th>Option</th>
       <th>Value</th>
      </tr>
	    <tr>
       <td>Select Plan(.jpg file) to upload</td>
	     <td>
        <input type="file" name="image">
      </td>
	    </tr>
    	<?php
      foreach ($img as $key => $value) {
        echo 
        "<tr>
        <td>".ucfirst($key)."</td>
        <td><input type=\"text\" name=\"$key\" value=\"".$value."\" min=\"0\" max=\"10\"></td>
        </tr>";
      }
      ?>
    	<tr>
    		<td colspan="3"><input  type="submit" name="submit" class="button" value="Upload"></td>
    	</tr>
    </table>
</div>
</form>
</div>
</div>
</div>
</body>
</html

