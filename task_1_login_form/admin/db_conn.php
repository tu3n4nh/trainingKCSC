<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "kcsc";

$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed" . mysqli_connect_error());
}

// echo "Connected successfully!";
