<?php
session_start(); // Začnemo sejo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Povezava z bazo
        $link = new mysqli("localhost", "root", "", "SolaNaDaljavo");

        if ($link->connect_error) {
            die("Povezava ni uspela: " . $link->connect_error);
        }

        // Preverimo, če uporabnik že obstaja
        $query = "SELECT * FROM Uporabniki WHERE Email = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Uporabnik s tem e-poštnim naslovom že obstaja.";
        } else {
            // Šifriramo geslo
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Vstavimo novega učenca v tabelo Uporabniki
            $query = "INSERT INTO Uporabniki (Ime, Priimek, Email, Geslo, Vloga) VALUES (?, ?, ?, ?, 'učenec')";
            $stmt = $link->prepare($query);
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Prijavimo uporabnika in nastavimo sejo
                $_SESSION['user_id'] = $stmt->insert_id; // ID novega uporabnika
                $_SESSION['user_name'] = $firstname . ' ' . $lastname;
                $_SESSION['user_type'] = 'učenec';
                $_SESSION['logged_in'] = true;
                
                // Preusmerimo na začetno stran za učence
                header("Location: Spletna_ucilnica_Ucenci.php");
                exit();
            } else {
                echo "Napaka pri registraciji: " . $stmt->error;
            }
        }

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
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
    <title>Spletna ucilnica - Registracija</title>

    <style>
        .form-container {
            width: 50%;
            margin: 0 auto;
            background-color: #f0f0f0;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            font-family: 'Lucida Sans', Geneva, Verdana, sans-serif;
        }

        .form-title {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-label {
            font-size: 18px;
            margin: 10px 0 5px 0;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 2px solid #59C5FF;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-size: 18px;
        }

        .form-button {
            width: 100%;
            background-color: #59C5FF;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
        }

        .form-button:hover {
            background-color: #74EEF4;
        }

        .form-link {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }

        .form-link a {
            color: #59C5FF;
            text-decoration: none;
        }

        .form-link a:hover {
            text-decoration: underline;
        }
    </style>
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

        <label class="form-label" for="password">Geslo</label>
        <input type="password" id="password" name="password" class="form-input" required>

        <button type="submit" class="form-button">Registracija</button>
    </form>
    <p class="form-link"><a href="login.php">Že imate račun? Prijavite se tukaj.</a></p>
</div>

</body>
</html>
