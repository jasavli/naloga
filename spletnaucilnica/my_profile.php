<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Pridobimo ime, priimek, uporabniško ime in email uporabnika
function fetchUserProfile($conn, $user_id) {
    $stmt = $conn->prepare("SELECT uporabnisko_ime, ime, priimek, email FROM uporabniki WHERE ID_uporabnika = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Pridobimo začetne podatke o uporabniku
$user = fetchUserProfile($conn, $user_id);

if (isset($_POST['update_profile'])) {
    $uporabnisko_ime = $conn->real_escape_string($_POST['uporabnisko_ime']);
    $ime = $conn->real_escape_string($_POST['ime']);
    $priimek = $conn->real_escape_string($_POST['priimek']);
    $email = $conn->real_escape_string($_POST['email']);
    $geslo = $_POST['geslo'];
    $novo_geslo = $_POST['novo_geslo'];
    $potrdi_geslo = $_POST['potrdi_geslo'];

    if (!empty($geslo) && !empty($novo_geslo) && !empty($potrdi_geslo)) {
        $stmt = $conn->prepare("SELECT geslo FROM uporabniki WHERE ID_uporabnika = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        if (password_verify($geslo, $user_data['geslo'])) {
            if ($novo_geslo == $potrdi_geslo) {
                $hashed_password = password_hash($novo_geslo, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE uporabniki SET uporabnisko_ime = ?, ime = ?, priimek = ?, email = ?, geslo = ? WHERE ID_uporabnika = ?");
                $stmt->bind_param("sssssi", $uporabnisko_ime, $ime, $priimek, $email, $hashed_password, $user_id);
            } else {
                $error = "Novo geslo in potrditev gesla se ne ujemata.";
            }
        } else {
            $error = "Napačno trenutno geslo.";
        }
    } else {
        $stmt = $conn->prepare("UPDATE uporabniki SET uporabnisko_ime = ?, ime = ?, priimek = ?, email = ? WHERE ID_uporabnika = ?");
        $stmt->bind_param("ssssi", $uporabnisko_ime, $ime, $priimek, $email, $user_id);
    }

    if (!isset($error) && $stmt->execute()) {
        $success = "Profil je bil uspešno posodobljen.";
        // Fetch updated user data after successful update
        $user = fetchUserProfile($conn, $user_id);
    } elseif (!isset($error)) {
        $error = "Napaka pri posodabljanju profila: " . $conn->error;
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Moj profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="logo">
        <a href="dashboard.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
            <img src="logo2.png" alt="Logo">
            <h2>Spletna učilnica</h2>
        </a>
        </div>
        <a href="logout.php" class="logout">Odjava</a>
    </div>

    <div class="main-content">
        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                <li><a href="my_profile.php" class="<?= ($current_page == 'my_profile.php') ? 'active' : '' ?>">Moj profil</a></li>
                <?php if ($role == 'učitelj'): ?>
                    <li><a href="view_submissions.php" class="<?= ($current_page == 'view_submissions.php') ? 'active' : '' ?>">Oddane naloge</a></li>
                <?php elseif ($role == 'učenec'): ?>
                    <li><a href="my_assignments.php" class="<?= ($current_page == 'my_assignments.php') ? 'active' : '' ?>">Moje naloge</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="content">
            <h3>Moj profil</h3>
            <?php
            if (isset($success)) echo "<p style='color:green;'>$success</p>";
            if (isset($error)) echo "<p style='color:red;'>$error</p>";
            ?>
            <form action="my_profile.php" method="post">
                <label>Uporabniško ime:</label>
                <input type="text" name="uporabnisko_ime" value="<?php echo htmlspecialchars($user['uporabnisko_ime']); ?>" required><br>
                <label>Ime:</label>
                <input type="text" name="ime" value="<?php echo htmlspecialchars($user['ime']); ?>" required><br>
                <label>Priimek:</label>
                <input type="text" name="priimek" value="<?php echo htmlspecialchars($user['priimek']); ?>" required><br>
                <label>E-pošta:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

                <h4>Spremeni geslo (neobvezno):</h4>
                <label>Trenutno geslo:</label>
                <input type="password" name="geslo"><br>
                <label>Novo geslo:</label>
                <input type="password" name="novo_geslo"><br>
                <label>Potrdi novo geslo:</label>
                <input type="password" name="potrdi_geslo"><br>

                <button type="submit" name="update_profile">Posodobi profil</button>
            </form>
        </div>
    </div>
</body>
</html>
