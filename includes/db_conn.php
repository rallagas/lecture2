<?php

$servername="localhost";
$dbusername="root";
$dbpassword="";
$dbname="lecture";

$conn = mysqli_connect($servername,$dbusername,$dbpassword,$dbname);

// Check connection
if (!$conn){
    die("Maintenance Mode.");
}
