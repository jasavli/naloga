<?php
// index.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik že prijavljen
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $conn->real_escape_string($_POST['username_or_email']);
    $password = $_POST['password'];

    // Preverimo, ali obstaja uporabnik z danim uporabniškim imenom ali emailom
    $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE uporabnisko_ime = ? OR email = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Preverimo geslo
        if (password_verify($password, $user['geslo'])) {
            // Shranimo podatke v sejo
            $_SESSION['user_id'] = $user['ID_uporabnika'];
            $_SESSION['username'] = $user['uporabnisko_ime'];
            $_SESSION['role'] = $user['vloga'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Napačno geslo.";
        }
    } else {
        $error = "Uporabniško ime ali e-pošta ne obstajata.";
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Prijava</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Zgornja naslovna vrstica -->
    <div class="header">
        <div class="logo">
            <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <img src="logo2.png" alt="Logo">
                <h2>Spletna učilnica</h2>
            </a>
        </div>
    </div>

    <!-- Vsebina -->
    <div class="centered-container">
        <h2>Prijava v sistem</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="index.php" method="post" class="form-container">
            <label>Uporabniško ime ali e-pošta:</label>
            <input type="text" name="username_or_email" required><br>
            <label>Geslo:</label>
            <input type="password" name="password" required><br>
            <button type="submit" name="login">Prijava</button>
        </form>
        <p>Če še nimate računa, se lahko <a href="register.php">registrirate tukaj</a>.</p>
    </div>
</body>
</html>
