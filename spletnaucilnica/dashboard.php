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
$app_name = "Moja Šola";

// Pridobimo ime in priimek uporabnika za prikaz v naslovni vrstici
$stmt = $conn->prepare("SELECT ime, priimek FROM uporabniki WHERE ID_uporabnika = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$ime = $user['ime'];
$priimek = $user['priimek'];

// Pridobimo predmete, ki jih uporabnik obiskuje ali poučuje
if ($vloga == 'učitelj') {
    $stmt = $conn->prepare("SELECT p.ID_predmeta, p.ime_predmeta FROM predmeti p INNER JOIN ucitelji_predmeti up ON p.ID_predmeta = up.ID_predmeta WHERE up.ID_ucitelja = ?");
} elseif ($vloga == 'učenec') {
    // Pridobimo razrede, v katere je učenec vpisan
    $stmt = $conn->prepare("SELECT r.ID_razreda, r.ime_razreda FROM razredi r INNER JOIN ucenci_razredi ur ON r.ID_razreda = ur.ID_razreda WHERE ur.ID_ucenca = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $razredi = $stmt->get_result();

    // Pridobimo predmete učenca prek razredov
    $stmt = $conn->prepare("SELECT DISTINCT p.ID_predmeta, p.ime_predmeta FROM predmeti p INNER JOIN predmeti_razredi pr ON p.ID_predmeta = pr.ID_predmeta INNER JOIN ucenci_razredi ur ON pr.ID_razreda = ur.ID_razreda WHERE ur.ID_ucenca = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $predmeti = $stmt->get_result();
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$predmeti = $stmt->get_result();

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
                <?php if ($vloga == 'administrator'): ?>
                    <li><a href="manage_subjects.php">Upravljanje predmetov</a></li>
                    <li><a href="manage_teachers.php">Upravljanje učiteljev</a></li>
                    <li><a href="manage_students.php">Upravljanje učencev</a></li>
                <?php elseif ($vloga == 'učitelj'): ?>
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
            <h3>Pozdravljeni, <?php echo htmlspecialchars($ime . ' ' . $priimek); ?>!</h3>
            <h4>Moji predmeti:</h4>
            <div class="subjects-grid">
                <?php while ($row = $predmeti->fetch_assoc()): ?>
                    <div class="subject-card">
                        <h4><?php echo htmlspecialchars($row['ime_predmeta']); ?></h4>
                        <a href="subject.php?id=<?php echo $row['ID_predmeta']; ?>">Vstopi v predmet</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>
