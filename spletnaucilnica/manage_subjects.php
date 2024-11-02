<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: index.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'add' && isset($_POST['add_subject'])) {
    $ime_predmeta = $conn->real_escape_string($_POST['ime_predmeta']);
    $opis_predmeta = $conn->real_escape_string($_POST['opis_predmeta']);
    $vpisni_kljuc = $conn->real_escape_string($_POST['vpisni_kljuc']);

    $stmt = $conn->prepare("INSERT INTO predmeti (ime_predmeta, opis_predmeta, vpisni_kljuc) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ime_predmeta, $opis_predmeta, $vpisni_kljuc);
    if ($stmt->execute()) {
        $success = "Predmet uspešno dodan.";
    } else {
        $error = "Napaka pri dodajanju predmeta: " . $conn->error;
    }
}

if ($action == 'edit' && isset($_POST['edit_subject'])) {
    $ID_predmeta = intval($_POST['ID_predmeta']);
    $ime_predmeta = $conn->real_escape_string($_POST['ime_predmeta']);
    $opis_predmeta = $conn->real_escape_string($_POST['opis_predmeta']);
    $vpisni_kljuc = $conn->real_escape_string($_POST['vpisni_kljuc']);

    $stmt = $conn->prepare("UPDATE predmeti SET ime_predmeta = ?, opis_predmeta = ?, vpisni_kljuc = ? WHERE ID_predmeta = ?");
    $stmt->bind_param("sssi", $ime_predmeta, $opis_predmeta, $vpisni_kljuc, $ID_predmeta);
    if ($stmt->execute()) {
        $success = "Predmet uspešno posodobljen.";
    } else {
        $error = "Napaka pri posodabljanju predmeta: " . $conn->error;
    }
}

if ($action == 'delete' && isset($_GET['id'])) {
    $ID_predmeta = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM predmeti WHERE ID_predmeta = ?");
    $stmt->bind_param("i", $ID_predmeta);
    if ($stmt->execute()) {
        $success = "Predmet uspešno izbrisan.";
    } else {
        $error = "Napaka pri brisanju predmeta: " . $conn->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM predmeti");
$stmt->execute();
$predmeti = $stmt->get_result();

$current_page = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje predmetov</title>
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
            <h3>Upravljanje predmetov</h3>
            <?php
            if (isset($success)) echo "<p style='color:green;'>$success</p>";
            if (isset($error)) echo "<p style='color:red;'>$error</p>";
            ?>

            <?php if ($action == 'list'): ?>
                <a href="manage_subjects.php?action=add" class="button">Dodaj predmet</a>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Ime predmeta</th>
                        <th>Opis</th>
                        <th>Vpisni ključ</th>
                        <th>Akcije</th>
                    </tr>
                    <?php while ($predmet = $predmeti->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $predmet['ID_predmeta']; ?></td>
                            <td><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></td>
                            <td><?php echo htmlspecialchars($predmet['opis_predmeta']); ?></td>
                            <td><?php echo htmlspecialchars($predmet['vpisni_kljuc']); ?></td>
                            <td>
                                <a href="manage_subjects.php?action=edit&id=<?php echo $predmet['ID_predmeta']; ?>">Uredi</a> |
                                <a href="manage_subjects.php?action=delete&id=<?php echo $predmet['ID_predmeta']; ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati ta predmet?')">Izbriši</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php elseif ($action == 'add'): ?>
                <h4>Dodaj predmet</h4>
                <form action="manage_subjects.php?action=add" method="post" class="form-container">
                    <label>Ime predmeta:</label>
                    <input type="text" name="ime_predmeta" required><br>
                    <label>Opis predmeta:</label>
                    <textarea name="opis_predmeta"></textarea><br>
                    <label>Vpisni ključ:</label>
                    <input type="text" name="vpisni_kljuc" required><br>
                    <button type="submit" name="add_subject">Dodaj predmet</button>
                    <a href="manage_subjects.php" class="button">Prekliči</a>
                </form>
            <?php elseif ($action == 'edit' && isset($_GET['id'])): ?>
                <?php
                $ID_predmeta = intval($_GET['id']);
                $stmt = $conn->prepare("SELECT * FROM predmeti WHERE ID_predmeta = ?");
                $stmt->bind_param("i", $ID_predmeta);
                $stmt->execute();
                $predmet = $stmt->get_result()->fetch_assoc();
                ?>
                <h4>Uredi predmet</h4>
                <form action="manage_subjects.php?action=edit" method="post" class="form-container">
                    <input type="hidden" name="ID_predmeta" value="<?php echo $ID_predmeta; ?>">
                    <label>Ime predmeta:</label>
                    <input type="text" name="ime_predmeta" value="<?php echo htmlspecialchars($predmet['ime_predmeta']); ?>" required><br>
                    <label>Opis predmeta:</label>
                    <textarea name="opis_predmeta"><?php echo htmlspecialchars($predmet['opis_predmeta']); ?></textarea><br>
                    <label>Vpisni ključ:</label>
                    <input type="text" name="vpisni_kljuc" value="<?php echo htmlspecialchars($predmet['vpisni_kljuc']); ?>" required><br>
                    <button type="submit" name="edit_subject">Posodobi predmet</button>
                    <a href="manage_subjects.php" class="button">Prekliči</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
