<?php
// upload_materials.php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'učitelj') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['upload'])) {
    $naslov = $conn->real_escape_string($_POST['naslov']);
    $predmet_id = intval($_POST['predmet']);
    $datoteka = $_FILES['datoteka']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($datoteka);

    // Preverimo in ustvarimo mapo, če ne obstaja
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Preverimo dovoljene oblike datotek
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = array('pdf', 'doc', 'docx', 'ppt', 'pptx');

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['datoteka']['tmp_name'], $target_file)) {
            // Vstavimo zapis v bazo podatkov
            $stmt = $conn->prepare("INSERT INTO gradiva (ID_predmeta, ID_ucitelja, naslov_gradiva, pot_do_datoteke) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $predmet_id, $_SESSION['user_id'], $naslov, $target_file);

            if ($stmt->execute()) {
                $success = "Gradivo je bilo uspešno naloženo.";
            } else {
                $error = "Napaka pri shranjevanju gradiva v bazo: " . $conn->error;
            }
        } else {
            $error = "Napaka pri nalaganju datoteke.";
        }
    } else {
        $error = "Dovoljene so samo naslednje oblike datotek: PDF, DOC, DOCX, PPT, PPTX.";
    }
}

// Pridobimo predmete, ki jih učitelj poučuje
$stmt = $conn->prepare("SELECT p.ID_predmeta, p.ime_predmeta FROM predmeti p INNER JOIN ucitelji_predmeti up ON p.ID_predmeta = up.ID_predmeta WHERE up.ID_ucitelja = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$predmeti = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Nalaganje gradiv</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Zgornja naslovna vrstica -->
    <div class="header">
        <div class="logo">
        <a href="dashboard.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
            <img src="logo2.png" alt="Logo">
            <h2>Spletna učilnica</h2>
        </a>
        </div>
        <a href="logout.php" class="logout">Odjava</a>
    </div>

    <!-- Glavni vsebinski del -->
    <div class="main-content">
        <!-- Levi stranski meni -->
        <div class="sidebar">
            <ul>
                <li><a href="view_submissions.php">Oddane naloge</a></li>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Nalaganje gradiv</h3>
            <!-- Vaša obstoječa vsebina obrazca -->
        </div>
    </div>
    <?php
    if (isset($success)) echo "<p style='color:green;'>$success</p>";
    if (isset($error)) echo "<p style='color:red;'>$error</p>";
    ?>
    <form action="upload_materials.php" method="post" enctype="multipart/form-data">
        <label>Naslov gradiva:</label>
        <input type="text" name="naslov" required><br>
        <label>Izberite predmet:</label>
        <select name="predmet" required>
            <?php while ($row = $predmeti->fetch_assoc()): ?>
                <option value="<?php echo $row['ID_predmeta']; ?>"><?php echo htmlspecialchars($row['ime_predmeta']); ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Izberite datoteko:</label>
        <input type="file" name="datoteka" required><br>
        <button type="submit" name="upload">Naloži gradivo</button>
    </form>
    <p><a href="dashboard.php">Nazaj na nadzorno ploščo</a></p>
</body>
</html>
