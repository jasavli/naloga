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
$has_access = false;
if ($vloga == 'učitelj') {
    $stmt = $conn->prepare("SELECT * FROM ucitelji_predmeti_razredi WHERE ID_ucitelja = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $predmet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $has_access = true;
    }
} elseif ($vloga == 'učenec') {
    $stmt = $conn->prepare("SELECT * FROM ucenci_predmeti WHERE ID_ucenca = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $predmet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $has_access = true;
    }
}

if (!$has_access) {
    // Uporabnik nima dostopa do tega predmeta
    header("Location: dashboard.php");
    exit();
}

// Pridobimo podatke o predmetu
$stmt = $conn->prepare("SELECT * FROM predmeti WHERE ID_predmeta = ?");
$stmt->bind_param("i", $predmet_id);
$stmt->execute();
$predmet = $stmt->get_result()->fetch_assoc();

// Obdelava nalaganja gradiva (če je učitelj)
if ($vloga == 'učitelj' && isset($_POST['upload_material'])) {
    $naslov_gradiva = $conn->real_escape_string($_POST['naslov_gradiva']);
    $opis_gradiva = $conn->real_escape_string($_POST['opis_gradiva']);
    $datoteka_gradiva = $_FILES['datoteka_gradiva']['name'];
    $target_dir = "uploads/materials/";

    // Preverimo in ustvarimo mapo, če ne obstaja
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($datoteka_gradiva);

    if (move_uploaded_file($_FILES['datoteka_gradiva']['tmp_name'], $target_file)) {
        // Vstavimo gradivo v bazo
        $stmt = $conn->prepare("INSERT INTO gradiva (ID_predmeta, naslov_gradiva, opis, pot_do_datoteke) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $predmet_id, $naslov_gradiva, $opis_gradiva, $target_file);

        if ($stmt->execute()) {
            $success_material = "Gradivo je bilo uspešno naloženo.";
        } else {
            $error_material = "Napaka pri shranjevanju gradiva v bazo: " . $conn->error;
        }
    } else {
        $error_material = "Napaka pri nalaganju datoteke.";
    }
}

// Obdelava dodajanja nove naloge (če je učitelj)
if ($vloga == 'učitelj' && isset($_POST['add_assignment'])) {
    $naslov_naloge = $conn->real_escape_string($_POST['naslov_naloge']);
    $opis_naloge = $conn->real_escape_string($_POST['opis_naloge']);
    $rok_oddaje = $_POST['rok_oddaje'];
    $datoteka_naloge = $_FILES['datoteka_naloge']['name'];
    $target_dir = "uploads/assignments/";

    // Preverimo in ustvarimo mapo, če ne obstaja
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($datoteka_naloge);
    $uploadOk = true;

    // Premakni datoteko na strežnik
    if ($datoteka_naloge && move_uploaded_file($_FILES['datoteka_naloge']['tmp_name'], $target_file)) {
        // Vstavimo nalogo v bazo z lokacijo datoteke
        $stmt = $conn->prepare("INSERT INTO naloge_predmet (ID_predmeta, naslov_naloge, opis, rok_oddaje, pot_do_datoteke) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $predmet_id, $naslov_naloge, $opis_naloge, $rok_oddaje, $target_file);

        if ($stmt->execute()) {
            $success_assignment = "Naloga je bila uspešno dodana.";
        } else {
            $error_assignment = "Napaka pri shranjevanju naloge v bazo: " . $conn->error;
        }
    } else {
        $error_assignment = "Napaka pri nalaganju datoteke.";
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Dodatni slogi za zavihke */
        .tabs {
            overflow: hidden;
            background-color: #f1f1f1;
        }

        .tabs button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        .tabs button:hover {
            background-color: #ddd;
        }

        .tabs button.active {
            background-color: #ccc;
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
        }
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
                    <li><a href="dashboard.php">Nadzorna plošča</a></li>
                    <li><a href="my_profile.php">Moj profil</a></li>
                <?php elseif ($vloga == 'učenec'): ?>
                    <li><a href="dashboard.php">Nadzorna plošča</a></li>
                    <li><a href="my_assignments.php">Moje naloge</a></li>
                    <li><a href="my_profile.php">Moj profil</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Odjava</a></li>
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

                <?php if ($vloga == 'učitelj'): ?>
                    <h4>Naloži novo gradivo</h4>
                    <?php
                    if (isset($success_material)) echo "<p style='color:green;'>$success_material</p>";
                    if (isset($error_material)) echo "<p style='color:red;'>$error_material</p>";
                    ?>
                    <form action="subject.php?id=<?php echo $predmet_id; ?>" method="post" enctype="multipart/form-data" class="form-container">
                        <label>Naslov gradiva:</label>
                        <input type="text" name="naslov_gradiva" required><br>
                        <label>Opis (neobvezno):</label>
                        <textarea name="opis_gradiva"></textarea><br>
                        <label>Izberite datoteko:</label>
                        <input type="file" name="datoteka_gradiva" required><br>
                        <button type="submit" name="upload_material">Naloži gradivo</button>
                    </form>
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

                <?php if ($vloga == 'učitelj'): ?>
                    <h4>Dodaj novo nalogo</h4>
                    <?php
                    if (isset($success_assignment)) echo "<p style='color:green;'>$success_assignment</p>";
                    if (isset($error_assignment)) echo "<p style='color:red;'>$error_assignment</p>";
                    ?>
                    <form action="subject.php?id=<?php echo $predmet_id; ?>" method="post" enctype="multipart/form-data" class="form-container">
                        <label>Naslov naloge:</label>
                        <input type="text" name="naslov_naloge" required><br>
                        <label>Opis:</label>
                        <textarea name="opis_naloge"></textarea><br>
                        <label>Rok oddaje:</label>
                        <input type="datetime-local" name="rok_oddaje" required><br>
                        <label>Izberite datoteko:</label>
                        <input type="file" name="datoteka_naloge" required><br>
                        <button type="submit" name="add_assignment">Dodaj nalogo</button>
                    </form>
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
