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

// Check if user has access to this subject
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
    header("Location: dashboard.php");
    exit();
}

// Fetch subject data
$stmt = $conn->prepare("SELECT * FROM predmeti WHERE ID_predmeta = ?");
$stmt->bind_param("i", $predmet_id);
$stmt->execute();
$predmet = $stmt->get_result()->fetch_assoc();

// Delete material if requested by the teacher
if ($vloga == 'učitelj' && isset($_GET['delete_material'])) {
    $material_id = intval($_GET['delete_material']);
    $stmt = $conn->prepare("DELETE FROM gradiva WHERE ID_gradiva = ? AND ID_ucitelja = ?");
    $stmt->bind_param("ii", $material_id, $user_id);
    if ($stmt->execute()) {
        $success_material = "Gradivo je bilo uspešno izbrisano.";
    } else {
        $error_material = "Napaka pri brisanju gradiva: " . $conn->error;
    }
}

// Delete assignment if requested by the teacher
if ($vloga == 'učitelj' && isset($_GET['delete_assignment'])) {
    $assignment_id = intval($_GET['delete_assignment']);
    $stmt = $conn->prepare("DELETE FROM naloge_predmet WHERE ID_naloge_predmet = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $assignment_id, $predmet_id);
    if ($stmt->execute()) {
        $success_assignment = "Naloga je bila uspešno izbrisana.";
    } else {
        $error_assignment = "Napaka pri brisanju naloge: " . $conn->error;
    }
}

// Handle material upload
if ($vloga == 'učitelj' && isset($_POST['upload_material'])) {
    $naslov_gradiva = $conn->real_escape_string($_POST['naslov_gradiva']);
    $datoteka_gradiva = $_FILES['datoteka_gradiva']['name'];
    $target_dir = "uploads/materials/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($datoteka_gradiva);

    if (move_uploaded_file($_FILES['datoteka_gradiva']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO gradiva (ID_predmeta, ID_ucitelja, naslov_gradiva, pot_do_datoteke) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $predmet_id, $user_id, $naslov_gradiva, $target_file);
        if ($stmt->execute()) {
            $success_material = "Gradivo je bilo uspešno naloženo.";
        } else {
            $error_material = "Napaka pri shranjevanju gradiva v bazo: " . $conn->error;
        }
    } else {
        $error_material = "Napaka pri nalaganju datoteke.";
    }
}

// Handle assignment addition
if ($vloga == 'učitelj' && isset($_POST['add_assignment'])) {
    $naslov_naloge = $conn->real_escape_string($_POST['naslov_naloge']);
    $opis_naloge = $conn->real_escape_string($_POST['opis_naloge']);
    $rok_oddaje = $_POST['rok_oddaje'];
    $datoteka_naloge = $_FILES['datoteka_naloge']['name'];
    $target_dir = "uploads/assignments/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($datoteka_naloge);

    if ($datoteka_naloge && move_uploaded_file($_FILES['datoteka_naloge']['tmp_name'], $target_file)) {
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

// Student leaves subject
if ($vloga == 'učenec' && isset($_POST['leave_subject'])) {
    $stmt = $conn->prepare("DELETE FROM ucenci_predmeti WHERE ID_ucenca = ? AND ID_predmeta = ?");
    $stmt->bind_param("ii", $user_id, $predmet_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?message=left_subject");
        exit();
    } else {
        $error_leave = "Napaka pri zapuščanju predmeta: " . $conn->error;
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Enhanced styling */
        .tabs {
            display: flex;
            background-color: #eee;
        }
        .tabs button {
            background-color: #007acc;
            color: white;
            border: none;
            padding: 14px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 10px;
            border-radius: 4px;
        }
        .tabs button:hover {
            background-color: #005f99;
        }
        .tabs button.active {
            background-color: #005f99;
        }
        .tabcontent {
            display: none;
            padding: 20px;
            border-top: none;
        }
        .form-container button {
            background-color: #007acc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .form-container button:hover {
            background-color: #005f99;
        }
    </style>
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

    <!-- Main Content with Sidebar -->
    <div class="main-content">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <?php if ($vloga == 'učitelj'): ?>
                    <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                    <li><a href="my_profile.php" class="<?= ($current_page == 'my_profile.php') ? 'active' : '' ?>">Moj profil</a></li>
                    <li><a href="view_submissions.php" class="<?= ($current_page == 'view_submissions.php') ? 'active' : '' ?>">Oddane naloge</a></li>
                <?php elseif ($vloga == 'učenec'): ?>
                    <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                    <li><a href="my_profile.php" class="<?= ($current_page == 'my_profile.php') ? 'active' : '' ?>">Moj profil</a></li>
                    <li><a href="my_assignments.php" class="<?= ($current_page == 'my_assignments.php') ? 'active' : '' ?>">Moje naloge</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="content">
            <h3><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($predmet['opis_predmeta'])); ?></p>

            <!-- Tabs for Materials and Assignments -->
            <div class="tabs">
                <button class="tablinks" onclick="openTab(event, 'Gradiva')" id="defaultOpen">Gradiva</button>
                <button class="tablinks" onclick="openTab(event, 'Naloge')">Naloge</button>
            </div>

            <!-- Materials Section -->
            <div id="Gradiva" class="tabcontent">
                <h4>Gradiva</h4>
                <?php
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
                                <?php if ($vloga == 'učitelj'): ?>
                                    <a href="subject.php?id=<?php echo $predmet_id; ?>&delete_material=<?php echo $gradivo['ID_gradiva']; ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to gradivo?');" style="color:red;">Izbriši</a>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Ni gradiv za ta predmet.</p>
                <?php endif; ?>

                <?php if ($vloga == 'učitelj'): ?>
                    <h4>Naloži novo gradivo</h4>
                    <?php if (isset($success_material)) echo "<p style='color:green;'>$success_material</p>"; ?>
                    <?php if (isset($error_material)) echo "<p style='color:red;'>$error_material</p>"; ?>
                    <form action="subject.php?id=<?php echo $predmet_id; ?>" method="post" enctype="multipart/form-data" class="form-container">
                        <label>Naslov gradiva:</label>
                        <input type="text" name="naslov_gradiva" required><br>
                        <label>Izberite datoteko:</label>
                        <input type="file" name="datoteka_gradiva" required><br>
                        <button type="submit" name="upload_material">Naloži gradivo</button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Assignments Section -->
            <div id="Naloge" class="tabcontent">
                <h4>Naloge</h4>
                <?php
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
                                <?php if ($vloga == 'učitelj'): ?>
                                    <a href="subject.php?id=<?php echo $predmet_id; ?>&delete_assignment=<?php echo $naloga['ID_naloge_predmet']; ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to nalogo?');" style="color:red;">Izbriši</a>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Ni nalog za ta predmet.</p>
                <?php endif; ?>

                <?php if ($vloga == 'učitelj'): ?>
                    <h4>Dodaj novo nalogo</h4>
                    <?php if (isset($success_assignment)) echo "<p style='color:green;'>$success_assignment</p>"; ?>
                    <?php if (isset($error_assignment)) echo "<p style='color:red;'>$error_assignment</p>"; ?>
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
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        document.getElementById("defaultOpen").click();
    </script>
</body>
</html>
