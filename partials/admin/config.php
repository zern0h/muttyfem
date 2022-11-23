<?php

$host = "localhost"; 
$user = "root"; 
$password = ""; 
$dbname = "my-tutorial"; 

$con = mysqli_connect($host, $user, $password, $dbname);
// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>