<?php
// config.php

$servername = "localhost";
$username = "root"; // Spremenite v svoje uporabniško ime za bazo podatkov
$password = '';           // Spremenite v svoje geslo za bazo podatkov
$dbname = "solski_sistem";          // Ime baze podatkov

// Ustvarimo povezavo
$conn = new mysqli($servername, $username, $password, $dbname);

// Preverimo povezavo
if ($conn->connect_error) {
    die("Povezava ni uspela: " . $conn->connect_error);
}

// Nastavimo kodiranje znakov na UTF-8
$conn->set_charset("utf8");
?>
