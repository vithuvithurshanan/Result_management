<?php
$host = "localhost";     // localhost for local server
$user = "root";          // default username for phpMyAdmin
$password = "";          // default password is empty in XAMPP
$database = "ati_rms";   // your database name

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
