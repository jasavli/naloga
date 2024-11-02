<?php
// upload_materials.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik prijavljen in ali je učitelj
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'učitelj') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pridobimo predmete, ki jih učitelj poučuje
$stmt = $conn->prepare("SELECT DISTINCT p.ID_predmeta, p.ime_predmeta FROM predmeti p INNER JOIN ucitelji_predmeti_razredi upr ON p.ID_predmeta = upr.ID_predmeta WHERE upr.ID_ucitelja = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$predmeti = $stmt->get_result();

// Če je obrazec za nalaganje oddan
if (isset($_POST['upload_material'])) {
    $naslov = $conn->real_escape_string($_POST['naslov']);
    $opis = $conn->real_escape_string($_POST['opis']);
    $predmet_id = intval($_POST['predmet']);

    // Preverimo, ali učitelj poučuje izbrani predmet
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM ucitelji_predmeti_razredi WHERE ID_ucitelja = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $predmet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['cnt'] > 0) {
        $datoteka = $_FILES['datoteka']['name'];
        $target_dir = "uploads/materials/";

        // Preverimo in ustvarimo mapo, če ne obstaja
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($datoteka);

        if (move_uploaded_file($_FILES['datoteka']['tmp_name'], $target_file)) {
            // Vstavimo gradivo v bazo
            $stmt = $conn->prepare("INSERT INTO gradiva (ID_predmeta, naslov_gradiva, opis, pot_do_datoteke) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $predmet_id, $naslov, $opis, $target_file);

            if ($stmt->execute()) {
                $success = "Gradivo je bilo uspešno naloženo.";
            } else {
                $error = "Napaka pri shranjevanju gradiva v bazo: " . $conn->error;
            }
        } else {
            $error = "Napaka pri nalaganju datoteke.";
        }
    } else {
        $error = "Nimate dovoljenja za nalaganje gradiv za ta predmet.";
    }
}

// Če je zahteva za brisanje gradiva
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $gradivo_id = intval($_GET['id']);

    // Preverimo, ali gradivo pripada predmetu, ki ga učitelj poučuje
    $stmt = $conn->prepare("SELECT g.pot_do_datoteke FROM gradiva g INNER JOIN ucitelji_predmeti_razredi upr ON g.ID_predmeta = upr.ID_predmeta WHERE g.ID_gradiva = ? AND upr.ID_ucitelja = ?");
    $stmt->bind_param("ii", $gradivo_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gradivo = $result->fetch_assoc();

    if ($gradivo) {
        // Izbrišemo datoteko
        if (file_exists($gradivo['pot_do_datoteke'])) {
            unlink($gradivo['pot_do_datoteke']);
        }

        // Izbrišemo zapis iz baze
        $stmt = $conn->prepare("DELETE FROM gradiva WHERE ID_gradiva = ?");
        $stmt->bind_param("i", $gradivo_id);
        if ($stmt->execute()) {
            $success = "Gradivo je bilo uspešno izbrisano.";
        } else {
            $error = "Napaka pri brisanju gradiva iz baze: " . $conn->error;
        }
    } else {
        $error = "Nimate dovoljenja za brisanje tega gradiva.";
    }
}

// Pridobimo vsa gradiva učitelja
$stmt = $conn->prepare("SELECT g.*, p.ime_predmeta FROM gradiva g INNER JOIN predmeti p ON g.ID_predmeta = p.ID_predmeta INNER JOIN ucitelji_predmeti_razredi upr ON p.ID_predmeta = upr.ID_predmeta WHERE upr.ID_ucitelja = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$gradiva = $stmt->get_result();

$current_page = basename($_SERVER['PHP_SELF']); // Pridobi trenutno stran

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
                <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                <li><a href="my_profile.php" class="<?= ($current_page == 'my_profile.php') ? 'active' : '' ?>">Moj profil</a></li>
                <li><a href="upload_materials.php" class="<?= ($current_page == 'upload_materials.php') ? 'active' : '' ?>">Nalaganje gradiv</a></li>
                <li><a href="view_submissions.php" class="<?= ($current_page == 'view_submissions.php') ? 'active' : '' ?>">Oddane naloge</a></li>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Nalaganje gradiv</h3>
            <?php
            if (isset($success)) echo "<p style='color:green;'>$success</p>";
            if (isset($error)) echo "<p style='color:red;'>$error</p>";
            ?>
            <form action="upload_materials.php" method="post" enctype="multipart/form-data" class="form-container">
                <label>Naslov gradiva:</label>
                <input type="text" name="naslov" required><br>
                <label>Opis (neobvezno):</label>
                <textarea name="opis"></textarea><br>
                <label>Izberite predmet:</label>
                <select name="predmet" required>
                    <?php while ($row = $predmeti->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_predmeta']; ?>"><?php echo htmlspecialchars($row['ime_predmeta']); ?></option>
                    <?php endwhile; ?>
                </select><br>
                <label>Izberite datoteko:</label>
                <input type="file" name="datoteka" required><br>
                <button type="submit" name="upload_material">Naloži gradivo</button>
            </form>

            <h3>Moja gradiva</h3>
            <?php if ($gradiva->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Predmet</th>
                        <th>Naslov</th>
                        <th>Datum objave</th>
                        <th>Akcije</th>
                    </tr>
                    <?php while ($gradivo = $gradiva->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($gradivo['ime_predmeta']); ?></td>
                            <td><?php echo htmlspecialchars($gradivo['naslov_gradiva']); ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($gradivo['datum_objave'])); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($gradivo['pot_do_datoteke']); ?>" target="_blank">Prenesi</a> |
                                <a href="upload_materials.php?delete=1&id=<?php echo $gradivo['ID_gradiva']; ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to gradivo?')">Izbriši</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Nimate naloženih gradiv.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
