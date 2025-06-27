<?php
//database_connection.php
if ($_SERVER['HTTP_HOST']!="localhost") {
	$host 	= "bicvta5rvhpjer8yntq8-mysql.services.clever-cloud.com";
	$dbname = "bicvta5rvhpjer8yntq8";
	$user 	= "uhjpsjf42cddttvr";
	$pass 	= "xKHgw1T7dW800q7VPW5a";
}
else{
	$host 	= "localhost";
	$dbname = "online_user";
	$user 	= "root";
	$pass 	= "anambd78";
}
$connect = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass) or die("could not connect");
$conn 	 = @mysql_connect("bicvta5rvhpjer8yntq8-mysql.services.clever-cloud.com", $user, $pass) or die("could not connect to database");
$rs 	 = mysql_select_db($dbname, $conn);
session_start();
?>