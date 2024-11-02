<?php

$servername = "localhost";
$username = "root"; 
$password = '';           
$dbname = "solski_sistem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Povezava ni uspela: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
