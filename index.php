<?php
//index.php
include('database_connection.php');

if(!isset($_SESSION["type"]))
{
  header("location: login.php");
}
  $check1 = "SELECT devices_name FROM devices_details";
  $check1_statement = $connect->prepare($check1);
  $check1_statement->execute();
  $check1_result = $check1_statement->fetchAll();
  $count1 = $check1_statement->rowCount();
  if ($count1>0) {
  }
  else{
  header("location: devices_entry.php?error=Please Entry Devices Details");
  }
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Automated Safety Tracker</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script>
$(document).ready(function(){
fetch_user_login_data();
setInterval(function(){
 fetch_user_login_data();
}, 3000);
function fetch_user_login_data()
{
 var action = "fetch_data";
 $.ajax({
  url:"create_line.php",
  method:"POST",
  data:{action:action},
  success:function(data)
  {
    //alert(data);
   $('#user_login_status').html(data);
  }
 });
}
});
</script>
  <?php include('header.php'); ?>
   <div class="panel panel-default">
    <div class="panel-heading">Dashboard</div>
    <div id="user_login_status" class="panel-body">
      <div class="cssload-thecube">
      <div class="cssload-cube cssload-c1"></div>
      <div class="cssload-cube cssload-c2"></div>
      <div class="cssload-cube cssload-c4"></div>
      <div class="cssload-cube cssload-c3"></div>
      </div>
      <br>
    </div>
   </div>
  </div>
 </body>
</html>

