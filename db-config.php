<?php

$server = 'localhost';
$dbname= 'login';
$user = 'root';
$password = '';

$con = new mysqli($server, $user, $password, $dbname);

if($con->connect_error){
  die("Connection failed: " . $con->connect_error);
}
?>