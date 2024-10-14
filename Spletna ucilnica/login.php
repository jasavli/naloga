<?php
session_start();
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $link = new mysqli("localhost", "root", "", "SpletnaUcilnica");

        if ($link->connect_error) {
            die("Povezava ni uspela: " . $link->connect_error);
        }

        // Preverimo, ali e-pošta obstaja
        $query = "SELECT id_ucenca, ime, priimek, geslo FROM Ucenec WHERE mail = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $ime, $priimek, $hashedPassword);
        $stmt->fetch();

        if ($id) {
            // Preverimo geslo
            if (password_verify($password, $hashedPassword)) {
                // Nastavimo sejo
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $ime . ' ' . $priimek;
                $_SESSION['logged_in'] = true;

                // Preusmerimo na stran za učence
                header("Location: Spletna uclinica - Ucenci.php");
                exit();
            } else {
                // Napačno geslo
                $error_message = "Napačno geslo.";
            }
        } else {
            // Uporabnik ne obstaja
            $error_message = "Uporabnik s tem e-poštnim naslovom ne obstaja.";
        }

        $stmt->close();
        $link->close();
    } else {
        $error_message = "Prosimo, izpolnite vsa polja.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src='main.js'></script>
    <title>Spletna ucilnica - Prijava</title>
    <style>
    </style>
</head>
<body>
    
<div class="form-container">
    <h2 class="form-title">Prijava</h2>

    <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
    <form action="login.php" method="POST">
        <label class="form-label" for="email">E-poštni naslov</label>
        <input type="email" id="email" name="email" class="form-input" required>

        <label class="form-label" for="password">Geslo</label>
        <input type="password" id="password" name="password" class="form-input" required>

        <button type="submit" class="form-button">Prijava</button>
    </form>
    <p class="form-link"><a href="register.php">Nimate računa? Registrirajte se tukaj.</a></p>
</div>

</body>
</html>