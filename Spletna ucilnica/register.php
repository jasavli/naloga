<?php
session_start(); // Začnemo sejo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['emso']) && !empty($_POST['tel']) && !empty($_POST['id_razreda'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $emso = $_POST['emso'];
        $tel = $_POST['tel'];
        $id_razreda = $_POST['id_razreda'];
        
        // Povezava s podatkovno bazo
        $link = new mysqli("localhost", "root", "", "SpletnaUcilnica");

        if ($link->connect_error) {
            die("Povezava ni uspela: " . $link->connect_error);
        }

        // Preverimo, če učenec s tem e-poštnim naslovom že obstaja
        $query = "SELECT * FROM Ucenec WHERE mail = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Uporabnik s tem e-poštnim naslovom že obstaja.";
        } else {
            // Šifriranje gesla
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Vstavimo učenca v bazo
            $query = "INSERT INTO Ucenec (ime, priimek, mail, tel_st, emso, id_razreda, geslo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $link->prepare($query);
            $stmt->bind_param("sssssss", $firstname, $lastname, $email, $tel, $emso, $id_razreda, $hashedPassword);

            if ($stmt->execute()) {
                // Prijavimo uporabnika in nastavimo sejo
                $_SESSION['user_id'] = $stmt->insert_id; // ID novega uporabnika
                $_SESSION['logged_in'] = true;
                
                // Preusmerimo na začetno stran
                header("Location: index.php");
                exit();
            } else {
                echo "Napaka pri registraciji: " . $stmt->error;
            }
        }

        // Zapremo povezavo
        $stmt->close();
        $link->close();
    } else {
        echo "Prosimo, izpolnite vsa polja.";
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
</head>
<body>
<div class="form-container">
    <h2 class="form-title">Registracija</h2>
    <form action="register.php" method="POST">
        <label class="form-label" for="firstname">Ime</label>
        <input type="text" id="firstname" name="firstname" class="form-input" required>

        <label class="form-label" for="lastname">Priimek</label>
        <input type="text" id="lastname" name="lastname" class="form-input" required>

        <label class="form-label" for="email">E-poštni naslov</label>
        <input type="email" id="email" name="email" class="form-input" required>

        <label class="form-label" for="tel">Telefonska številka</label>
        <input type="text" id="tel" name="tel" class="form-input" required>

        <label class="form-label" for="emso">EMŠO</label>
        <input type="text" id="emso" name="emso" class="form-input" required>

        <label class="form-label" for="id_razreda">Razred</label>
        <input type="text" id="id_razreda" name="id_razreda" class="form-input" required>

        <label class="form-label" for="password">Geslo</label>
        <input type="password" id="password" name="password" class="form-input" required>

        <button type="submit" class="form-button">Registracija</button>
    </form>
    <p class="form-link"><a href="login.php">Že imate račun? Prijavite se tukaj.</a></p>
</div>



<?php

function openDatabaseConnection(){
    $link = new mysqli("localhost", "root", "", "SpletnaUcilnica");
    $link->query("SET NAMES 'utf8'");
    return $link;
}
function closeDatabaseConnection($link){
    mysqli_close($link);
}
if(array_key_exists('buttonSignin', $_POST) && !(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['mail']) || empty($_POST['password']) ||empty($_POST['phone']))){
    $ime = $_POST['firstname'];
    $priimek = $_POST['lastname'];
    $mail = $_POST['mail'];
    $geslo = $_POST['password'];
    $link = openDatabaseConnection();
    $sql = "INSERT INTO uporabnik(ime, priimek, vrsta, mail, geslo) VALUES('$ime', '$priimek', 'uporabnik', '$mail','$geslo')";
    mysqli_query($link, $sql);
    closeDatabaseConnection($link);
    echo '<script>document.getElementById("alert").innerHTML = "";</script>';
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();

} 
elseif(array_key_exists('buttonSignin', $_POST) && (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['mail']) || empty($_POST['password']))){
    echo '<script>document.getElementById("alert").innerHTML = "Please enter all of the information";</script>';
}






?>

</body>
</html>