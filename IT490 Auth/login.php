#!/usr/bin/php
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
session_start();
include('database.php');
//require_once('path.inc');
//require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "login";
}
$request = array();
$request['type'] = "login";
#$request['username'] = "user3@gmail.com";
#$request['password'] = "1234";
$request['username'] = $_POST['username'];
$request['password'] = $_POST['pass'];
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);
echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";
if ($response == 0 ) {
	$date = date_create();
	file_put_contents('events.log', "[".date_format($date, 'm-d-Y H:i:s')."]"." Login error for user with username: ".$_POST['username'].".\n", FILE_APPEND);
	header("location:loginerror.html");
}
else {
	global $mydb;
	
	$username = $_POST['username'];
	$query = mysqli_query($mydb,"SELECT username FROM user WHERE username='$username'");
	$user = mysqli_fetch_array($query,MYSQLI_ASSOC);
	$_SESSION['userid'] = $user['username'];
	$date = date_create();
	file_put_contents('events.log', "[".date_format($date, 'm-d-Y H:i:s')."]"." User with username: ".$_POST['username']." logged in successfully.\n", FILE_APPEND);
	header("Location: loginsuccess.html");
}
?>
