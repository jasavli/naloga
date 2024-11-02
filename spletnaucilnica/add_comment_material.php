<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$gradivo_id = intval($_POST['gradivo_id']);
$vsebina = $conn->real_escape_string($_POST['vsebina']);
$avtor_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO komentarji_gradiva (ID_gradiva, ID_avtorja, vsebina) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $gradivo_id, $avtor_id, $vsebina);

if ($stmt->execute()) {
    header("Location: subject.php?id=" . $_GET['id']);
} else {
    echo "Napaka pri dodajanju komentarja: " . $conn->error;
}
?>
