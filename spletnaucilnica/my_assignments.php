<?php
// my_assignments.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik prijavljen in ali je učenec
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'učenec') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Pridobimo razrede, v katere je učenec vpisan
$stmt = $conn->prepare("SELECT ID_razreda FROM ucenci_razredi WHERE ID_ucenca = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$razredi = [];
while ($row = $result->fetch_assoc()) {
    $razredi[] = $row['ID_razreda'];
}

if (empty($razredi)) {
    echo "Niste vpisani v noben razred.";
    exit();
}

// Pridobimo predmete, ki jih učenec obiskuje prek razredov
$razredi_placeholders = implode(',', array_fill(0, count($razredi), '?'));

$sql = "SELECT DISTINCT p.ID_predmeta
        FROM predmeti p
        INNER JOIN predmeti_razredi pr ON p.ID_predmeta = pr.ID_predmeta
        WHERE pr.ID_razreda IN ($razredi_placeholders)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($razredi)), ...$razredi);
$stmt->execute();
$result = $stmt->get_result();

$predmeti = [];
while ($row = $result->fetch_assoc()) {
    $predmeti[] = $row['ID_predmeta'];
}

if (empty($predmeti)) {
    echo "Ni predmetov, ki bi jih obiskovali.";
    exit();
}

// Pridobimo naloge, ki jih mora učenec še oddati
$predmeti_placeholders = implode(',', array_fill(0, count($predmeti), '?'));

$sql = "SELECT np.ID_naloge_predmet, np.naslov_naloge, np.opis, np.rok_oddaje, p.ime_predmeta
        FROM naloge_predmet np
        INNER JOIN predmeti p ON np.ID_predmeta = p.ID_predmeta
        WHERE np.ID_predmeta IN ($predmeti_placeholders)
        AND np.ID_naloge_predmet NOT IN (
            SELECT n.ID_naloge_predmet FROM naloge n WHERE n.ID_ucenca = ?
        )
        ORDER BY np.rok_oddaje ASC";

$params = array_merge($predmeti, [$user_id]);
$types = str_repeat('i', count($predmeti)) . 'i';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$naloge_za_oddajo = $stmt->get_result();

// Pridobimo že oddane naloge
$sql = "SELECT n.ID_naloge, np.naslov_naloge AS naslov_predmeta_naloge, np.rok_oddaje, p.ime_predmeta, n.datum_oddaje
        FROM naloge n
        INNER JOIN naloge_predmet np ON n.ID_naloge_predmet = np.ID_naloge_predmet
        INNER JOIN predmeti p ON np.ID_predmeta = p.ID_predmeta
        WHERE n.ID_ucenca = ?
        ORDER BY n.datum_oddaje DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$oddane_naloge = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Moje naloge</title>
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
                <li><a href="my_profile.php">Moj profil</a></li>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Naloge za oddajo</h3>
            <?php if ($naloge_za_oddajo->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Predmet</th>
                        <th>Naslov naloge</th>
                        <th>Rok oddaje</th>
                        <th>Akcije</th>
                    </tr>
                    <?php while ($naloga = $naloge_za_oddajo->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($naloga['ime_predmeta']); ?></td>
                            <td><?php echo htmlspecialchars($naloga['naslov_naloge']); ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($naloga['rok_oddaje'])); ?></td>
                            <td><a href="assignment.php?id=<?php echo $naloga['ID_naloge_predmet']; ?>">Podrobnosti</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Trenutno nimate nalog za oddajo.</p>
            <?php endif; ?>

            <h3>Oddane naloge</h3>
            <?php if ($oddane_naloge->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Predmet</th>
                        <th>Naslov naloge</th>
                        <th>Datum oddaje</th>
                        <th>Akcije</th>
                    </tr>
                    <?php while ($naloga = $oddane_naloge->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($naloga['ime_predmeta']); ?></td>
                            <td><?php echo htmlspecialchars($naloga['naslov_predmeta_naloge']); ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($naloga['datum_oddaje'])); ?></td>
                            <td><a href="assignment.php?id=<?php echo $naloga['ID_naloge']; ?>">Ogled</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Trenutno nimate oddanih nalog.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
