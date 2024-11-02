<?php
// manage_classes.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: index.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Dodajanje novega razreda
if ($action == 'add' && isset($_POST['add_class'])) {
    $ime_razreda = $conn->real_escape_string($_POST['ime_razreda']);
    
    $stmt = $conn->prepare("INSERT INTO razredi (ime_razreda) VALUES (?)");
    $stmt->bind_param("s", $ime_razreda);
    if ($stmt->execute()) {
        $success = "Razred uspešno dodan.";
    } else {
        $error = "Napaka pri dodajanju razreda: " . $conn->error;
    }
}

// Pridobivanje vseh razredov za seznam
$stmt = $conn->prepare("SELECT * FROM razredi");
$stmt->execute();
$razredi = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje razredov</title>
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
                <li><a href="dashboard.php">Nadzorna plošča</a></li>
                <li><a href="manage_subjects.php">Upravljanje predmetov</a></li>
                <li><a href="manage_teachers.php">Upravljanje učiteljev</a></li>
                <li><a href="manage_students.php">Upravljanje učencev</a></li>
                <li><a href="manage_classes.php" class="active">Upravljanje razredov</a></li>
                <li><a href="logout.php">Odjava</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h3>Upravljanje razredov</h3>
            
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <?php if ($action == 'list'): ?>
                <a href="manage_classes.php?action=add" class="button">Dodaj razred</a>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Ime razreda</th>
                    </tr>
                    <?php while ($razred = $razredi->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $razred['ID_razreda']; ?></td>
                            <td><?php echo htmlspecialchars($razred['ime_razreda']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php elseif ($action == 'add'): ?>
                <h4>Dodaj nov razred</h4>
                <form action="manage_classes.php?action=add" method="post">
                    <label>Ime razreda:</label>
                    <input type="text" name="ime_razreda" required>
                    <button type="submit" name="add_class">Dodaj razred</button>
                    <a href="manage_classes.php" class="button">Prekliči</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
