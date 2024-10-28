<?php
// subject.php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$vloga = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$predmet_id = intval($_GET['id']);

// Preverimo, ali ima uporabnik dostop do tega predmeta
if ($vloga == 'učitelj') {
    $stmt = $conn->prepare("SELECT * FROM ucitelji_predmeti_razredi WHERE ID_ucitelja = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $predmet_id);
} elseif ($vloga == 'učenec') {
    $stmt = $conn->prepare("SELECT * FROM predmeti_razredi pr INNER JOIN ucenci_razredi ur ON pr.ID_razreda = ur.ID_razreda WHERE ur.ID_ucenca = ? AND pr.ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $predmet_id);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Uporabnik nima dostopa do tega predmeta
    header("Location: dashboard.php");
    exit();
}

// Pridobimo podatke o predmetu
$stmt = $conn->prepare("SELECT * FROM predmeti WHERE ID_predmeta = ?");
$stmt->bind_param("i", $predmet_id);
$stmt->execute();
$predmet = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Dodatni slogi za zavihke */
    </style>
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
                <?php if ($vloga == 'učitelj'): ?>
                    <li><a href="upload_materials.php">Nalaganje gradiv</a></li>
                    <li><a href="view_submissions.php">Oddane naloge</a></li>
                <?php elseif ($vloga == 'učenec'): ?>
                    <li><a href="my_profile.php">Moj profil</a></li>
                    <li><a href="my_assignments.php">Moje naloge</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($predmet['opis_predmeta'])); ?></p>

            <!-- Zavihki -->
            <div class="tabs">
                <button class="tablinks" onclick="openTab(event, 'Gradiva')" id="defaultOpen">Gradiva</button>
                <button class="tablinks" onclick="openTab(event, 'Naloge')">Naloge</button>
            </div>

            <!-- Vsebina zavihkov -->
            <div id="Gradiva" class="tabcontent">
                <h4>Gradiva</h4>
                <?php
                // Pridobimo gradiva za ta predmet
                $stmt = $conn->prepare("SELECT * FROM gradiva WHERE ID_predmeta = ?");
                $stmt->bind_param("i", $predmet_id);
                $stmt->execute();
                $gradiva = $stmt->get_result();
                ?>

                <?php if ($gradiva->num_rows > 0): ?>
                    <ul>
                        <?php while ($gradivo = $gradiva->fetch_assoc()): ?>
                            <li>
                                <a href="<?php echo htmlspecialchars($gradivo['pot_do_datoteke']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($gradivo['naslov_gradiva']); ?>
                                </a>
                                <span>(Objavljeno: <?php echo date('d.m.Y', strtotime($gradivo['datum_objave'])); ?>)</span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Ni gradiv za ta predmet.</p>
                <?php endif; ?>
            </div>

            <div id="Naloge" class="tabcontent">
                <h4>Naloge</h4>
                <?php
                // Pridobimo naloge za ta predmet
                $stmt = $conn->prepare("SELECT * FROM naloge_predmet WHERE ID_predmeta = ?");
                $stmt->bind_param("i", $predmet_id);
                $stmt->execute();
                $naloge = $stmt->get_result();
                ?>

                <?php if ($naloge->num_rows > 0): ?>
                    <ul>
                        <?php while ($naloga = $naloge->fetch_assoc()): ?>
                            <li>
                                <a href="assignment.php?id=<?php echo $naloga['ID_naloge_predmet']; ?>">
                                    <?php echo htmlspecialchars($naloga['naslov_naloge']); ?>
                                </a>
                                <span>(Rok oddaje: <?php echo date('d.m.Y H:i', strtotime($naloga['rok_oddaje'])); ?>)</span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Ni nalog za ta predmet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;

            // Skrijemo vse zavihke
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Odstranimo aktivni razred iz vseh zavihkov
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Prikažemo izbrani zavihek in ga označimo kot aktivnega
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Privzeto odpremo prvi zavihek
        document.getElementById("defaultOpen").click();
    </script>
</body>
</html>
