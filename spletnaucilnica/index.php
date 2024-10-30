<?php
// index.php
session_start();
include('config.php');

// Če je uporabnik že prijavljen, ga preusmerimo na nadzorno ploščo
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Pripravimo poizvedbo z uporabo pripravljenih izjav
    $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE uporabnisko_ime = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Preverimo geslo
        if (password_verify($password, $user['geslo'])) {
            $_SESSION['user_id'] = $user['ID_uporabnika'];
            $_SESSION['role'] = $user['vloga'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Napačno uporabniško ime ali geslo.";
        }
    } else {
        $error = "Napačno uporabniško ime ali geslo.";
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
                <img src="logo.png" alt="Logo">
                <h2>Moja Šola</h2>
            </a>
        </div>
    </div>

    <!-- Vsebina -->
    <div class="centered-container">
        <h2>Prijava v sistem</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="index.php" method="post" class="form-container">
            <label>Uporabniško ime:</label>
            <input type="text" name="username" required><br>
            <label>Geslo:</label>
            <input type="password" name="password" required><br>
            <button type="submit" name="login">Prijava</button>
        </form>
        <p>Če še nimate računa, se lahko <a href="register.php">registrirate tukaj</a>.</p>
    </div>
</body>
</html>
