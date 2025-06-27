<?php
//index.php
include('database_connection.php');

if(!isset($_SESSION["type"]))
{
 header("location: login.php");
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
  url:"online_devices_data.php",
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
    <div class="panel-heading">Online Devices Details</div>
    <div id="user_login_status" class="panel-body">
      <br>
    </div>
   </div>
  </div>
 </body>
</html>

