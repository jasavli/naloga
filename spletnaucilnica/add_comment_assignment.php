<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$naloga_predmet_id = intval($_POST['naloga_predmet_id']);
$vsebina = $conn->real_escape_string($_POST['vsebina']);
$avtor_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO komentarji_naloge (ID_naloge_predmet, ID_avtorja, vsebina) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $naloga_predmet_id, $avtor_id, $vsebina);

if ($stmt->execute()) {
    header("Location: assignment.php?id=" . $naloga_predmet_id);
    exit();
} else {
    echo "Napaka pri dodajanju komentarja: " . $conn->error;
}
?>
