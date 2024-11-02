<?php
include('config.php');

$result = $conn->query("SELECT ID_uporabnika, geslo FROM uporabniki");

while ($user = $result->fetch_assoc()) {
    $id = $user['ID_uporabnika'];
    $plain_password = $user['geslo'];

    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE uporabniki SET geslo = ? WHERE ID_uporabnika = ?");
    $stmt->bind_param("si", $hashed_password, $id);
    $stmt->execute();
}

echo "Gesla so bila uspešno posodobljena.";
?>
