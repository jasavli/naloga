<?php
// assignment.php
session_start();
include('config.php');

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'učenec' || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$naloga_predmet_id = intval($_GET['id']);

// Check if the student has access to this assignment
// Get the classes the student is enrolled in
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

// Get the subject associated with the assignment
$stmt = $conn->prepare("SELECT ID_predmeta FROM naloge_predmet WHERE ID_naloge_predmet = ?");
$stmt->bind_param("i", $naloga_predmet_id);
$stmt->execute();
$result = $stmt->get_result();
$naloga_predmet = $result->fetch_assoc();

if (!$naloga_predmet) {
    echo "Naloga ne obstaja.";
    exit();
}

$predmet_id = $naloga_predmet['ID_predmeta'];

// Check if the student attends this subject through their classes
$razredi_placeholders = implode(',', array_fill(0, count($razredi), '?'));
$sql = "SELECT COUNT(*) as cnt FROM predmeti_razredi WHERE ID_predmeta = ? AND ID_razreda IN ($razredi_placeholders)";
$stmt = $conn->prepare($sql);
$params = array_merge([$predmet_id], $razredi);
$types = 'i' . str_repeat('i', count($razredi));
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['cnt'] == 0) {
    echo "Nimate dostopa do te naloge.";
    exit();
}

// Get assignment details
$stmt = $conn->prepare("SELECT np.*, p.ime_predmeta FROM naloge_predmet np INNER JOIN predmeti p ON np.ID_predmeta = p.ID_predmeta WHERE np.ID_naloge_predmet = ?");
$stmt->bind_param("i", $naloga_predmet_id);
$stmt->execute();
$naloga = $stmt->get_result()->fetch_assoc();

// Check if the student has already submitted this assignment
$stmt = $conn->prepare("SELECT * FROM naloge WHERE ID_naloge_predmet = ? AND ID_ucenca = ?");
$stmt->bind_param("ii", $naloga_predmet_id, $user_id);
$stmt->execute();
$oddana_naloga = $stmt->get_result()->fetch_assoc();

// Handle assignment submission
if (isset($_POST['submit_assignment'])) {
    $komentar = $conn->real_escape_string($_POST['komentar']);
    $datoteka = $_FILES['datoteka']['name'];

    // Get student's name
    $stmt = $conn->prepare("SELECT ime, priimek FROM uporabniki WHERE ID_uporabnika = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    $ime = $user['ime'];
    $priimek = $user['priimek'];

    // Create new filename
    $file_extension = pathinfo($datoteka, PATHINFO_EXTENSION);
    $new_filename = $priimek . " " . $ime . " – " . $naloga['naslov_naloge'] . "." . $file_extension;
    $target_dir = "uploads/assignments/";

    // Ensure the directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($new_filename);

    // Check allowed file types
    $fileType = strtolower($file_extension);
    $allowedTypes = array('pdf', 'doc', 'docx', 'txt');

    if (in_array($fileType, $allowedTypes)) {
        // Check if file already exists (for resubmission)
        if (file_exists($target_file)) {
            // Ask for confirmation to overwrite
            if (!isset($_POST['overwrite_confirm'])) {
                $overwrite_prompt = true;
            } else {
                if (move_uploaded_file($_FILES['datoteka']['tmp_name'], $target_file)) {
                    // Update or insert assignment submission
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

                    // Save comment if provided
                    if (!empty($komentar)) {
                        $stmt = $conn->prepare("INSERT INTO komentarji_naloge (ID_naloge, ID_avtorja, vsebina) VALUES (?, ?, ?)");
                        $stmt->bind_param("iis", $naloga_id, $user_id, $komentar);
                        $stmt->execute();
                    }
                    $success = "Naloga je bila uspešno oddana.";
                    // Refresh the page to prevent resubmission on refresh
                    header("Location: assignment.php?id=$naloga_predmet_id&success=1");
                    exit();
                } else {
                    $error = "Napaka pri nalaganju datoteke.";
                }
            }
        } else {
            if (move_uploaded_file($_FILES['datoteka']['tmp_name'], $target_file)) {
                // Insert assignment submission
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

                // Save comment if provided
                if (!empty($komentar)) {
                    $stmt = $conn->prepare("INSERT INTO komentarji_naloge (ID_naloge, ID_avtorja, vsebina) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $naloga_id, $user_id, $komentar);
                    $stmt->execute();
                }
                $success = "Naloga je bila uspešno oddana.";
                // Refresh the page to prevent resubmission on refresh
                header("Location: assignment.php?id=$naloga_predmet_id&success=1");
                exit();
            } else {
                $error = "Napaka pri nalaganju datoteke.";
            }
        }
    } else {
        $error = "Dovoljene so samo naslednje oblike datotek: PDF, DOC, DOCX, TXT.";
    }
}

// Get comments for the assignment
$stmt = $conn->prepare("SELECT kn.*, u.ime, u.priimek FROM komentarji_naloge kn INNER JOIN uporabniki u ON kn.ID_avtorja = u.ID_uporabnika WHERE kn.ID_naloge_predmet = ? ORDER BY kn.datum_komentarja ASC");
$stmt->bind_param("i", $naloga_predmet_id);
$stmt->execute();
$komentarji = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($naloga['naslov_naloge']); ?></title>
    <link rel="stylesheet" href="style.css">
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
                <li><a href="subject.php?id=<?php echo $predmet_id; ?>">Nazaj na predmet</a></li>
                <li><a href="my_profile.php">Moj profil</a></li>
                <li><a href="my_assignments.php">Naloge</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h3><?php echo htmlspecialchars($naloga['naslov_naloge']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($naloga['opis'])); ?></p>
            <p><strong>Rok oddaje:</strong> <?php echo date('d.m.Y H:i', strtotime($naloga['rok_oddaje'])); ?></p>

            <?php
            if (isset($_GET['success'])) {
                echo "<p style='color:green;'>Naloga je bila uspešno oddana.</p>";
            }
            if (isset($error)) {
                echo "<p style='color:red;'>$error</p>";
            }
            ?>

            <!-- Assignment submission -->
            <h4>Oddaja naloge:</h4>
            <?php if (isset($overwrite_prompt) && $overwrite_prompt): ?>
                <p>Datoteka z istim imenom že obstaja. Ali želite prepisati prejšnjo oddajo?</p>
                <form action="assignment.php?id=<?php echo $naloga_predmet_id; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="overwrite_confirm" value="1">
                    <input type="hidden" name="komentar" value="<?php echo htmlspecialchars($komentar); ?>">
                    <input type="file" name="datoteka" required><br>
                    <button type="submit" name="submit_assignment">Da, prepiši prejšnjo oddajo</button>
                </form>
            <?php else: ?>
                <form action="assignment.php?id=<?php echo $naloga_predmet_id; ?>" method="post" enctype="multipart/form-data">
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
            <form action="add_comment_assignment.php" method="post">
                <input type="hidden" name="naloga_predmet_id" value="<?php echo $naloga_predmet_id; ?>">
                <textarea name="vsebina" required placeholder="Vaš komentar..."></textarea><br>
                <button type="submit">Dodaj komentar</button>
            </form>
        </div>
    </div>
</body>
</html>
