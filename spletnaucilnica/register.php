<?php
// register.php
include('config.php');

if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $ime = $conn->real_escape_string($_POST['ime']);
    $priimek = $conn->real_escape_string($_POST['priimek']);
    $email = $conn->real_escape_string($_POST['email']);
    $razred_id = intval($_POST['razred']);

    // Hashiramo geslo
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preverimo, ali uporabniško ime ali email že obstajata
    $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE uporabnisko_ime = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Vstavimo novega uporabnika
        $stmt = $conn->prepare("INSERT INTO uporabniki (uporabnisko_ime, geslo, ime, priimek, email, vloga) VALUES (?, ?, ?, ?, ?, 'učenec')");
        $stmt->bind_param("sssss", $username, $hashed_password, $ime, $priimek, $email);

        if ($stmt->execute()) {
            // Pridobimo ID novovpisanega učenca
            $ucenec_id = $stmt->insert_id;
    
            // Vstavimo v tabelo ucenci_razredi
            $stmt = $conn->prepare("INSERT INTO ucenci_razredi (ID_ucenca, ID_razreda) VALUES (?, ?)");
            $stmt->bind_param("ii", $ucenec_id, $razred_id);
            $stmt->execute();
    
            header("Location: index.php");
            exit();
        } else {
            $error = "Napaka pri registraciji: " . $conn->error;
        }
    } else {
        $error = "Uporabniško ime ali e-pošta že obstajata.";
    }
}

// Pridobimo seznam razredov
$result = $conn->query("SELECT ID_razreda, ime_razreda FROM razredi");
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Zgornja naslovna vrstica -->
    <div class="header">
        <div class="logo">
            <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <img src="logo.png" alt="Logo">
                <h2>Spletna učilnica</h2>
            </a>
        </div>
    </div>

    <!-- Vsebina -->
    <div class="centered-container">
        <h2>Registracija</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="register.php" method="post" class="form-container">
            <label>Ime:</label>
            <input type="text" name="ime" required><br>
            <label>Priimek:</label>
            <input type="text" name="priimek" required><br>
            <label>E-pošta:</label>
            <input type="email" name="email" required><br>
            <label>Izberite razred:</label>
            <select name="razred" required>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['ID_razreda']; ?>"><?php echo htmlspecialchars($row['ime_razreda']); ?></option>
                <?php endwhile; ?>
            </select><br>
            <label>Uporabniško ime:</label>
            <input type="text" name="username" required><br>
            <label>Geslo:</label>
            <input type="password" name="password" required><br>
            <button type="submit" name="register">Registracija</button>
        </form>
        <p>Že imate račun? <a href="index.php">Prijavite se tukaj</a>.</p>
    </div>
</body>
</html>
