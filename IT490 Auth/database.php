<?php
//The IP is set to Matt's
$mydb = mysqli_connect('192.168.2.102','testuser','12345','testdb');
if ($mydb->errno != 0)
{
        echo "Failed To connect to DB: ". $mydb->error . PHP_EOL;
        exit(0);
}
//echo "successfully connected to database".PHP_EOL;

?>