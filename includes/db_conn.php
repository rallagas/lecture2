<?php

$servername="localhost";
$dbusername="root";
$dbpassword="";
$dbname="lecture";

$conn = mysqli_connect($servername,$dbusername,$dbpassword,$dbname);
$_GLOBAL['db'] = $conn;

// Check connection
if (!$conn){
    die("Maintenance Mode.");
}

session_start();




include_once "utilities.inc.php";
