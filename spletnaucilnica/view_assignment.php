<?php
// view_assignment.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik prijavljen in ali je učenec
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'učenec' || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$naloga_id = intval($_GET['id']);

// Preverimo, ali ima učenec dostop do te naloge
$stmt = $conn->prepare("SELECT n.*, np.naslov_naloge AS naslov_predmeta_naloge, np.opis, np.rok_oddaje, p.ime_predmeta
    FROM naloge n
    INNER JOIN naloge_predmet np ON n.ID_naloge_predmet = np.ID_naloge_predmet
    INNER JOIN predmeti p ON np.ID_predmeta = p.ID_predmeta
    WHERE n.ID_naloge = ? AND n.ID_ucenca = ?");
$stmt->bind_param("ii", $naloga_id, $user_id);
$stmt->execute();
$naloga = $stmt->get_result()->fetch_assoc();

if (!$naloga) {
    echo "Nimate dostopa do te naloge.";
    exit();
}

// Pridobimo komentarje
$stmt = $conn->prepare("SELECT kn.*, u.ime, u.priimek FROM komentarji_naloge kn INNER JOIN uporabniki u ON kn.ID_avtorja = u.ID_uporabnika WHERE kn.ID_naloge = ? ORDER BY kn.datum_komentarja ASC");
$stmt->bind_param("i", $naloga_id);
$stmt->execute();
$komentarji = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($naloga['naslov_predmeta_naloge']); ?></title>
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
            <h3><?php echo htmlspecialchars($naloga['naslov_predmeta_naloge']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($naloga['opis'])); ?></p>
            <p><strong>Rok oddaje:</strong> <?php echo date('d.m.Y H:i', strtotime($naloga['rok_oddaje'])); ?></p>
            <p><strong>Datum oddaje:</strong> <?php echo date('d.m.Y H:i', strtotime($naloga['datum_oddaje'])); ?></p>
            <p><a href="<?php echo htmlspecialchars($naloga['pot_do_datoteke']); ?>" target="_blank">Prenesi oddano nalogo</a></p>

            <h4>Komentarji:</h4>
            <ul>
                <?php while ($komentar = $komentarji->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($komentar['ime'] . ' ' . $komentar['priimek']); ?>:</strong>
                        <?php echo htmlspecialchars($komentar['vsebina']); ?>
                        <span>(<?php echo date('d.m.Y H:i', strtotime($komentar['datum_komentarja'])); ?>)</span>
                    </li>
                <?php endwhile; ?>
            </ul>

            <!-- Obrazec za dodajanje komentarja -->
            <form action="add_comment_assignment.php" method="post">
                <input type="hidden" name="naloga_id" value="<?php echo $naloga_id; ?>">
                <textarea name="vsebina" required placeholder="Vaš komentar..."></textarea><br>
                <button type="submit">Dodaj komentar</button>
            </form>
        </div>
    </div>
</body>
</html>
