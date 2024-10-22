<?php
session_start();
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Povezava z bazo
        $link = new mysqli("localhost", "root", "", "SolaNaDaljavo");

        if ($link->connect_error) {
            die("Povezava ni uspela: " . $link->connect_error);
        }

        // Preveri uporabnika v tabeli Uporabniki
        $query = "SELECT ID, Ime, Priimek, Geslo, Vloga FROM Uporabniki WHERE Email = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $ime, $priimek, $hashedPassword, $vloga);
        $stmt->fetch();

        // Debug sporočila
        if (!$id) {
            $error_message = "Uporabnik s tem e-poštnim naslovom ne obstaja.";
            error_log("Uporabnik ni najden za email: $email"); // Debug
        } else {
            error_log("Uporabnik je bil najden: $ime $priimek, ID: $id, Vloga: $vloga"); // Debug

            // Preverimo geslo
            if (password_verify($password, $hashedPassword)) {
                // Nastavimo sejo
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $ime . ' ' . $priimek;
                $_SESSION['user_type'] = $vloga;
                $_SESSION['logged_in'] = true;

                // Preusmeritev glede na tip uporabnika
                if ($vloga === 'učenec') {
                    header("Location: Spletna_ucilnica_Ucenci.php");
                } else if ($vloga === 'ucitelj') {
                    header("Location: Spletna_ucilnica_Ucitelji.php");
                } else if ($vloga === 'administrator') {
                    header("Location: Spletna_ucilnica_Administrator.php");
                }
                exit();
            } else {
                $error_message = "Napačno geslo.";
                error_log("Napačno geslo za uporabnika: $email"); // Debug
            }
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
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna_ucilnica_CSS.css'>
    <script src='main.js'></script>
    <title>Spletna ucilnica - Prijava</title>
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
