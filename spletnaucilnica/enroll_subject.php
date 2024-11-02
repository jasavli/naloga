<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'učenec') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['enroll']) && isset($_POST['subject_id']) && isset($_POST['vpisni_kljuc'])) {
    $subject_id = intval($_POST['subject_id']);
    $vpisni_kljuc = $conn->real_escape_string($_POST['vpisni_kljuc']);

    $stmt = $conn->prepare("SELECT * FROM ucenci_predmeti WHERE ID_ucenca = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "V ta predmet ste že vpisani.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM predmeti WHERE ID_predmeta = ? AND vpisni_kljuc = ?");
        $stmt->bind_param("is", $subject_id, $vpisni_kljuc);
        $stmt->execute();
        $subject = $stmt->get_result()->fetch_assoc();

        if ($subject) {
            $stmt = $conn->prepare("INSERT INTO ucenci_predmeti (ID_ucenca, ID_predmeta) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $subject_id);
            if ($stmt->execute()) {
                $success = "Uspešno ste se vpisali v predmet " . htmlspecialchars($subject['ime_predmeta']) . ".";
            } else {
                $error = "Napaka pri vpisu v predmet: " . $conn->error;
            }
        } else {
            $error = "Napačen vpisni ključ ali predmet ne obstaja.";
        }
    }

    header("Location: dashboard.php?success=" . urlencode($success) . "&error=" . urlencode($error));
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
