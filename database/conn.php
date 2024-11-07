<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'uehstationery';
$port = '3306';

$conn = mysqli_connect($server, $user, $pass, $database, $port);

if ($conn) {
    mysqLi_query($conn, "SET NAMES 'utf8' ");
} else {
    echo "Fail";
}
