<?php
// dashboard.php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$vloga = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Pridobimo ime aplikacije
$app_name = "Spletna učilnica";

// Pridobimo ime in priimek uporabnika za prikaz v naslovni vrstici
$stmt = $conn->prepare("SELECT ime, priimek FROM uporabniki WHERE ID_uporabnika = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$ime = $user['ime'];
$priimek = $user['priimek'];

// Pridobimo predmete, ki jih uporabnik obiskuje ali poučuje
$predmeti = array();

if ($vloga == 'učitelj') {
    // Pridobimo predmete, ki jih učitelj poučuje
    $stmt = $conn->prepare("SELECT DISTINCT p.ID_predmeta, p.ime_predmeta, p.vpisni_kljuc
                            FROM predmeti p
                            INNER JOIN ucitelji_predmeti_razredi upr ON p.ID_predmeta = upr.ID_predmeta
                            WHERE upr.ID_ucitelja = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $predmeti[] = $row;
    }
} elseif ($vloga == 'učenec') {
    // Pridobimo predmete, v katere je učenec vpisan
    $stmt = $conn->prepare("SELECT p.ID_predmeta, p.ime_predmeta
                            FROM predmeti p
                            INNER JOIN ucenci_predmeti up ON p.ID_predmeta = up.ID_predmeta
                            WHERE up.ID_ucenca = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $predmeti[] = $row;
    }
}

if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}
if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Nadzorna plošča</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Zgornja naslovna vrstica -->
    <div class="header">
        <div class="logo">
        <a href="dashboard.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
            <img src="logo2.png" alt="Logo">
            <h2><?php echo $app_name; ?></h2>
        </a>
        </div>
        <a href="logout.php" class="logout">Odjava</a>
    </div>

    <!-- Glavni vsebinski del -->
    <div class="main-content">
        <!-- Levi stranski meni -->
        <div class="sidebar">
            <ul>
                <?php if ($vloga == 'administrator'): ?>
                    <li><a href="manage_subjects.php">Upravljanje predmetov</a></li>
                    <li><a href="manage_teachers.php">Upravljanje učiteljev</a></li>
                    <li><a href="manage_students.php">Upravljanje učencev</a></li>
                <?php elseif ($vloga == 'učitelj'): ?>
                    <li><a href="my_profile.php">Moj profil</a></li>
                    <li><a href="upload_materials.php">Nalaganje gradiv</a></li>
                    <li><a href="view_submissions.php">Oddane naloge</a></li>
                <?php elseif ($vloga == 'učenec'): ?>
                    <li><a href="my_profile.php">Moj profil</a></li>
                    <li><a href="my_assignments.php">Moje naloge</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Odjava</a></li>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Pozdravljeni, <?php echo htmlspecialchars($ime . ' ' . $priimek); ?>!</h3>

            <?php
            if (isset($success)) {
                echo "<p style='color:green;'>$success</p>";
            }
            if (isset($error)) {
                echo "<p style='color:red;'>$error</p>";
            }
            ?>

            <h4>Moji predmeti:
                <?php if ($vloga == 'učenec'): ?>
                    <button onclick="document.getElementById('enrollmentForm').style.display='block'">+</button>
                <?php endif; ?>
            </h4>

            <?php if ($vloga == 'učenec'): ?>
                <div id="enrollmentForm" style="display:none;">
                    <form action="enroll_subject.php" method="post" class="form-container">
                        <label>Vpisni ključ predmeta:</label>
                        <input type="text" name="vpisni_kljuc" required><br>
                        <button type="submit" name="enroll">Vpiši se</button>
                        <button type="button" onclick="document.getElementById('enrollmentForm').style.display='none'">Prekliči</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="subjects-grid">
                <?php if (!empty($predmeti)): ?>
                    <?php foreach ($predmeti as $predmet): ?>
                        <div class="subject-card">
                            <h4><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></h4>
                            <a href="subject.php?id=<?php echo $predmet['ID_predmeta']; ?>">Vstopi v predmet</a>
                            <?php if ($vloga == 'učitelj'): ?>
                                <p>Vpisni ključ: <?php echo htmlspecialchars($predmet['vpisni_kljuc']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Trenutno nimate predmetov.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
