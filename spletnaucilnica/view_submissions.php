<?php
// view_submissions.php
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

// Če je izbran predmet
if (isset($_GET['predmet'])) {
    $predmet_id = intval($_GET['predmet']);

    // Preverimo, ali učitelj poučuje izbrani predmet
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM ucitelji_predmeti_razredi WHERE ID_ucitelja = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $predmet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['cnt'] > 0) {
        // Pridobimo oddane naloge za izbrani predmet
        $stmt = $conn->prepare("SELECT n.ID_naloge, n.datum_oddaje, n.pot_do_datoteke, u.ime, u.priimek, np.naslov_naloge
            FROM naloge n
            INNER JOIN uporabniki u ON n.ID_ucenca = u.ID_uporabnika
            INNER JOIN naloge_predmet np ON n.ID_naloge_predmet = np.ID_naloge_predmet
            WHERE n.ID_predmeta = ?
            ORDER BY n.datum_oddaje DESC");
        $stmt->bind_param("i", $predmet_id);
        $stmt->execute();
        $naloge = $stmt->get_result();
    } else {
        $error = "Nimate dovoljenja za ogled oddanih nalog za ta predmet.";
    }
}

$current_page = basename($_SERVER['PHP_SELF']); // Pridobi trenutno stran
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Oddane naloge</title>
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
                <li><a href="view_submissions.php" class="<?= ($current_page == 'view_submissions.php') ? 'active' : '' ?>">Oddane naloge</a></li>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Oddane naloge</h3>
            <?php
            if (isset($error)) echo "<p style='color:red;'>$error</p>";
            ?>

            <form action="view_submissions.php" method="get">
                <label>Izberite predmet:</label>
                <select name="predmet" onchange="this.form.submit()">
                    <option value="">-- Izberite predmet --</option>
                    <?php while ($row = $predmeti->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_predmeta']; ?>" <?php if (isset($predmet_id) && $predmet_id == $row['ID_predmeta']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['ime_predmeta']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <?php if (isset($naloge)): ?>
                <?php if ($naloge->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Učenec</th>
                            <th>Naslov naloge</th>
                            <th>Datum oddaje</th>
                            <th>Akcije</th>
                        </tr>
                        <?php while ($naloga = $naloge->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($naloga['ime'] . ' ' . $naloga['priimek']); ?></td>
                                <td><?php echo htmlspecialchars($naloga['naslov_naloge']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($naloga['datum_oddaje'])); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($naloga['pot_do_datoteke']); ?>" target="_blank">Prenesi</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>Ni oddanih nalog za izbrani predmet.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
