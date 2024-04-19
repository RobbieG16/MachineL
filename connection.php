<?php 
$mysqli = new mysqli("192.168.1.2","roots","raspberry","esp_data");

if($mysqli->connect_error){
    die("connection Failed:". $mysqli->connect_error);
}
echo "connected successfully";
?>