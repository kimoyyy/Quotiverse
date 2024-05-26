<?php
$host = "localhost";
$server = "root";
$password = "";
$db_name = "quotiverse";

$conn = mysqli_connect($host, $server, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>