<?php
// assignment.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik prijavljen in ali ima pravilen dostop (učenec ali učitelj)
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$naloga_predmet_id = intval($_GET['id']);
$vloga = $_SESSION['role'];

// Preverimo, ali naloga obstaja in ali je pripisana predmetu
$stmt = $conn->prepare("SELECT np.*, p.ime_predmeta FROM naloge_predmet np INNER JOIN predmeti p ON np.ID_predmeta = p.ID_predmeta WHERE np.ID_naloge_predmet = ?");
$stmt->bind_param("i", $naloga_predmet_id);
$stmt->execute();
$naloga = $stmt->get_result()->fetch_assoc();

if (!$naloga) {
    echo "Naloga ne obstaja.";
    exit();
}

$predmet_id = $naloga['ID_predmeta'];
$rok_oddaje = strtotime($naloga['rok_oddaje']);
$datoteka_naloge = $naloga['pot_do_datoteke'];

// Preverimo, ali je učenec že oddal to nalogo
$stmt = $conn->prepare("SELECT * FROM naloge WHERE ID_naloge_predmet = ? AND ID_ucenca = ?");
$stmt->bind_param("ii", $naloga_predmet_id, $user_id);
$stmt->execute();
$oddana_naloga = $stmt->get_result()->fetch_assoc();

// Obdelava oddaje naloge (samo za učence in če rok ni potekel)
if ($vloga == 'učenec' && time() <= $rok_oddaje && isset($_POST['submit_assignment'])) {
    $komentar = $conn->real_escape_string($_POST['komentar']);
    $datoteka = $_FILES['datoteka']['name'];

    // Pridobimo ime in priimek učenca
    $stmt = $conn->prepare("SELECT ime, priimek FROM uporabniki WHERE ID_uporabnika = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    $ime = $user['ime'];
    $priimek = $user['priimek'];

    // Ustvarimo ime datoteke za oddano nalogo
    $file_extension = pathinfo($datoteka, PATHINFO_EXTENSION);
    $new_filename = $priimek . " " . $ime . " – " . $naloga['naslov_naloge'] . "." . $file_extension;
    $target_dir = "uploads/assignments/";

    // Ustvarimo mapo, če ne obstaja
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($new_filename);

    // Preverimo velikost datoteke (na primer, omejimo na 50 MB)
    if ($_FILES['datoteka']['size'] <= 50 * 1024 * 1024) { // 50 MB
        if (move_uploaded_file($_FILES['datoteka']['tmp_name'], $target_file)) {
            // Vstavimo ali posodobimo oddano nalogo
            if ($oddana_naloga) {
                $stmt = $conn->prepare("UPDATE naloge SET pot_do_datoteke = ?, datum_oddaje = NOW() WHERE ID_naloge = ?");
                $stmt->bind_param("si", $target_file, $oddana_naloga['ID_naloge']);
                $stmt->execute();
                $naloga_id = $oddana_naloga['ID_naloge'];
            } else {
                $stmt = $conn->prepare("INSERT INTO naloge (ID_naloge_predmet, ID_predmeta, ID_ucenca, naslov_naloge, pot_do_datoteke) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiss", $naloga_predmet_id, $predmet_id, $user_id, $naloga['naslov_naloge'], $target_file);
                $stmt->execute();
                $naloga_id = $stmt->insert_id;
            }

            // Shranimo komentar, če je podan
            if (!empty($komentar)) {
                $stmt = $conn->prepare("INSERT INTO komentarji_naloge (ID_naloge, ID_avtorja, vsebina) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $naloga_id, $user_id, $komentar);
                $stmt->execute();
            }
            header("Location: assignment.php?id=$naloga_predmet_id&success=1");
            exit();
        } else {
            $error = "Napaka pri nalaganju datoteke.";
        }
    } else {
        $error = "Datoteka je prevelika. Največja dovoljena velikost je 50 MB.";
    }
}

// Pridobimo oddane naloge vseh učencev (samo za učitelje)
if ($vloga == 'učitelj') {
    $stmt = $conn->prepare("SELECT u.ime, u.priimek, n.datum_oddaje, n.pot_do_datoteke FROM ucenci_predmeti up INNER JOIN uporabniki u ON up.ID_ucenca = u.ID_uporabnika LEFT JOIN naloge n ON n.ID_ucenca = u.ID_uporabnika AND n.ID_naloge_predmet = ? WHERE up.ID_predmeta = ?");
    $stmt->bind_param("ii", $naloga_predmet_id, $predmet_id);
    $stmt->execute();
    $oddaje = $stmt->get_result();
}

// Pridobimo komentarje za to nalogo
$stmt = $conn->prepare("SELECT kn.*, u.ime, u.priimek FROM komentarji_naloge kn INNER JOIN uporabniki u ON kn.ID_avtorja = u.ID_uporabnika WHERE kn.ID_naloge_predmet = ? ORDER BY kn.datum_komentarja ASC");
$stmt->bind_param("i", $naloga_predmet_id);
$stmt->execute();
$komentarji = $stmt->get_result();

$current_page = basename($_SERVER['PHP_SELF']); // Pridobi trenutno stran


?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($naloga['naslov_naloge']); ?></title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmSubmission(event) {
            if (<?php echo $oddana_naloga ? 'true' : 'false'; ?>) {
                // Prikažemo opozorilo le, če je naloga že oddana
                if (!confirm("Naloga je že oddana. Ali res želite ponovno oddati nalogo?")) {
                    event.preventDefault(); // Preprečimo oddajo obrazca
                }
            }
        }
    </script>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <a href="dashboard.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <img src="logo2.png" alt="Logo">
                <h2>Spletna učilnica</h2>
            </a>
        </div>
        <a href="logout.php" class="logout">Odjava</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                <li><a href="my_profile.php" class="<?= ($current_page == 'my_profile.php') ? 'active' : '' ?>">Moj profil</a></li>
                <?php if ($vloga == 'učitelj'): ?>
                    <li><a href="view_submissions.php" class="<?= ($current_page == 'view_submissions.php') ? 'active' : '' ?>">Oddane naloge</a></li>
                <?php elseif ($vloga == 'učenec'): ?>
                    <li><a href="my_assignments.php" class="<?= ($current_page == 'my_assignments.php') ? 'active' : '' ?>">Moje naloge</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h3><?php echo htmlspecialchars($naloga['naslov_naloge']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($naloga['opis'])); ?></p>
            <p><strong>Rok oddaje:</strong> <?php echo date('d.m.Y H:i', $rok_oddaje); ?></p>

            <?php if (!empty($datoteka_naloge)): ?>
                <p><strong>Datoteka za nalogo:</strong> <a href="<?php echo htmlspecialchars($datoteka_naloge); ?>" target="_blank">Prenesi nalogo</a></p>
            <?php endif; ?>

            <?php
            if (isset($_GET['success'])) {
                echo "<p style='color:green;'>Naloga je bila uspešno oddana.</p>";
            }
            if (isset($error)) {
                echo "<p style='color:red;'>$error</p>";
            }
            ?>

            <?php if ($vloga == 'učenec' && time() > $rok_oddaje): ?>
                <p style="color:red;">Rok za oddajo te naloge je potekel.</p>
            <?php elseif ($vloga == 'učenec'): ?>
                <!-- Assignment submission -->
                <h4>Oddaja naloge:</h4>
                <form action="assignment.php?id=<?php echo $naloga_predmet_id; ?>" method="post" enctype="multipart/form-data" onsubmit="confirmSubmission(event)">
                    <label>Izberite datoteko:</label>
                    <input type="file" name="datoteka" required><br>
                    <button type="submit" name="submit_assignment">Oddaj nalogo</button>
                </form>
            <?php endif; ?>

            <!-- Display existing submission if any -->
            <?php if ($oddana_naloga): ?>
                <h4>Vaša oddana naloga:</h4>
                <p><strong>Datum oddaje:</strong> <?php echo date('d.m.Y H:i', strtotime($oddana_naloga['datum_oddaje'])); ?></p>
                <p><a href="<?php echo htmlspecialchars($oddana_naloga['pot_do_datoteke']); ?>" target="_blank">Prenesi oddano nalogo</a></p>
            <?php endif; ?>

            <?php if ($vloga == 'učitelj'): ?>
                <!-- Pregled oddanih nalog za učitelje -->
                <h4>Pregled oddanih nalog:</h4>
                <table border="1">
                    <tr>
                        <th>Ime</th>
                        <th>Priimek</th>
                        <th>Datum oddaje</th>
                        <th>Datoteka</th>
                    </tr>
                    <?php while ($oddaja = $oddaje->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($oddaja['ime']); ?></td>
                            <td><?php echo htmlspecialchars($oddaja['priimek']); ?></td>
                            <td><?php echo $oddaja['datum_oddaje'] ? date('d.m.Y H:i', strtotime($oddaja['datum_oddaje'])) : 'Ni oddano'; ?></td>
                            <td>
                                <?php if ($oddaja['pot_do_datoteke']): ?>
                                    <a href="<?php echo htmlspecialchars($oddaja['pot_do_datoteke']); ?>" target="_blank">Prenesi datoteko</a>
                                <?php else: ?>
                                    Ni oddano
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>

            <!-- Comments section -->
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

            <!-- Comment form -->
            <form action="add_comment_assignment.php" method="post" onsubmit="trimTextarea()">
                <input type="hidden" name="naloga_predmet_id" value="<?php echo $naloga_predmet_id; ?>">
                <textarea name="vsebina" required placeholder="Vaš komentar..."></textarea><br>
                <button type="submit">Dodaj komentar</button>
            </form>

            <script>
            function trimTextarea() {
                const textarea = document.getElementById('opis_naloge');
                textarea.value = textarea.value.trimEnd(); // JavaScript trim
            }
            </script>
        </div>
    </div>
    
</body>
</html>
