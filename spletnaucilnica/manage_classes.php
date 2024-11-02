<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: index.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$class_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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

if ($action == 'edit' && isset($_POST['edit_class']) && $class_id > 0) {
    $ime_razreda = $conn->real_escape_string($_POST['ime_razreda']);
    
    $stmt = $conn->prepare("UPDATE razredi SET ime_razreda = ? WHERE ID_razreda = ?");
    $stmt->bind_param("si", $ime_razreda, $class_id);
    if ($stmt->execute()) {
        $success = "Razred uspešno posodobljen.";
        $action = 'list'; 
    } else {
        $error = "Napaka pri posodabljanju razreda: " . $conn->error;
    }
}

if ($action == 'delete' && $class_id > 0) {
    $stmt = $conn->prepare("DELETE FROM razredi WHERE ID_razreda = ?");
    $stmt->bind_param("i", $class_id);
    if ($stmt->execute()) {
        $success = "Razred uspešno izbrisan.";
        $action = 'list';  
    } else {
        $error = "Napaka pri brisanju razreda: " . $conn->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM razredi");
$stmt->execute();
$razredi = $stmt->get_result();

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje razredov</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <a href="dashboard.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <img src="logo2.png" alt="Logo">
                <h2>Spletna učilnica</h2>
            </a>
        </div>
        <a href="logout.php" class="logout">Odjava</a>
    </div>

    <div class="main-content">
        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                <li><a href="manage_subjects.php" class="<?= ($current_page == 'manage_subjects.php') ? 'active' : '' ?>">Upravljanje predmetov</a></li>
                <li><a href="manage_teachers.php" class="<?= ($current_page == 'manage_teachers.php') ? 'active' : '' ?>">Upravljanje učiteljev</a></li>
                <li><a href="manage_students.php" class="<?= ($current_page == 'manage_students.php') ? 'active' : '' ?>">Upravljanje učencev</a></li>
                <li><a href="manage_classes.php" class="<?= ($current_page == 'manage_classes.php') ? 'active' : '' ?>">Upravljanje razredov</a></li>
            </ul>
        </div>

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
                        <th>Akcije</th>
                    </tr>
                    <?php while ($razred = $razredi->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $razred['ID_razreda']; ?></td>
                            <td><?php echo htmlspecialchars($razred['ime_razreda']); ?></td>
                            <td>
                                <a href="manage_classes.php?action=edit&id=<?php echo $razred['ID_razreda']; ?>">Uredi</a> |
                                <a href="manage_classes.php?action=delete&id=<?php echo $razred['ID_razreda']; ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati ta razred?');">Izbriši</a>
                            </td>
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
            <?php elseif ($action == 'edit' && $class_id > 0): ?>
                <?php
                $stmt = $conn->prepare("SELECT ime_razreda FROM razredi WHERE ID_razreda = ?");
                $stmt->bind_param("i", $class_id);
                $stmt->execute();
                $razred = $stmt->get_result()->fetch_assoc();
                ?>
                <h4>Uredi razred</h4>
                <form action="manage_classes.php?action=edit&id=<?php echo $class_id; ?>" method="post">
                    <label>Ime razreda:</label>
                    <input type="text" name="ime_razreda" value="<?php echo htmlspecialchars($razred['ime_razreda']); ?>" required>
                    <button type="submit" name="edit_class">Shrani spremembe</button>
                    <a href="manage_classes.php" class="button">Prekliči</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
