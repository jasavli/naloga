<?php
// manage_students.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik prijavljen in ali je administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: index.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Dodajanje novega učenca
if ($action == 'add' && isset($_POST['add_student'])) {
    $uporabnisko_ime = $conn->real_escape_string($_POST['uporabnisko_ime']);
    $geslo = password_hash($_POST['geslo'], PASSWORD_DEFAULT);
    $ime = $conn->real_escape_string($_POST['ime']);
    $priimek = $conn->real_escape_string($_POST['priimek']);
    $email = $conn->real_escape_string($_POST['email']);
    $predmeti = isset($_POST['predmeti']) ? $_POST['predmeti'] : [];

    // Preverimo, ali uporabniško ime ali email že obstaja
    $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE uporabnisko_ime = ? OR email = ?");
    $stmt->bind_param("ss", $uporabnisko_ime, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $error = "Uporabniško ime ali email že obstaja.";
    } else {
        // Vstavimo učenca v bazo
        $stmt = $conn->prepare("INSERT INTO uporabniki (uporabnisko_ime, geslo, ime, priimek, email, vloga) VALUES (?, ?, ?, ?, ?, 'učenec')");
        $stmt->bind_param("sssss", $uporabnisko_ime, $geslo, $ime, $priimek, $email);
        if ($stmt->execute()) {
            $ID_ucenca = $stmt->insert_id;
            // Dodelimo predmete učencu
            foreach ($predmeti as $ID_predmeta) {
                $stmt = $conn->prepare("INSERT INTO ucenci_predmeti (ID_ucenca, ID_predmeta) VALUES (?, ?)");
                $stmt->bind_param("ii", $ID_ucenca, $ID_predmeta);
                $stmt->execute();
            }
            $success = "Učenec uspešno dodan.";
        } else {
            $error = "Napaka pri dodajanju učenca: " . $conn->error;
        }
    }
}

// Urejanje učenca
if ($action == 'edit' && isset($_POST['edit_student'])) {
    $ID_ucenca = intval($_POST['ID_ucenca']);
    $uporabnisko_ime = $conn->real_escape_string($_POST['uporabnisko_ime']);
    $ime = $conn->real_escape_string($_POST['ime']);
    $priimek = $conn->real_escape_string($_POST['priimek']);
    $email = $conn->real_escape_string($_POST['email']);
    $predmeti = isset($_POST['predmeti']) ? $_POST['predmeti'] : [];

    // Posodobimo podatke o učencu
    $stmt = $conn->prepare("UPDATE uporabniki SET uporabnisko_ime = ?, ime = ?, priimek = ?, email = ? WHERE ID_uporabnika = ?");
    $stmt->bind_param("ssssi", $uporabnisko_ime, $ime, $priimek, $email, $ID_ucenca);
    if ($stmt->execute()) {
        // Posodobimo dodeljene predmete
        // Najprej izbrišemo obstoječe dodelitve
        $stmt = $conn->prepare("DELETE FROM ucenci_predmeti WHERE ID_ucenca = ?");
        $stmt->bind_param("i", $ID_ucenca);
        $stmt->execute();
        // Nato dodamo nove dodelitve
        foreach ($predmeti as $ID_predmeta) {
            $stmt = $conn->prepare("INSERT INTO ucenci_predmeti (ID_ucenca, ID_predmeta) VALUES (?, ?)");
            $stmt->bind_param("ii", $ID_ucenca, $ID_predmeta);
            $stmt->execute();
        }
        $success = "Učenec uspešno posodobljen.";
    } else {
        $error = "Napaka pri posodabljanju učenca: " . $conn->error;
    }
}

// Brisanje učenca
if ($action == 'delete' && isset($_GET['id'])) {
    $ID_ucenca = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM uporabniki WHERE ID_uporabnika = ?");
    $stmt->bind_param("i", $ID_ucenca);
    if ($stmt->execute()) {
        $success = "Učenec uspešno izbrisan.";
    } else {
        $error = "Napaka pri brisanju učenca: " . $conn->error;
    }
}

// Pridobimo seznam učencev
$stmt = $conn->prepare("SELECT * FROM uporabniki WHERE vloga = 'učenec'");
$stmt->execute();
$ucenci = $stmt->get_result();

// Pridobimo seznam predmetov
$stmt = $conn->prepare("SELECT * FROM predmeti");
$stmt->execute();
$predmeti = $stmt->get_result();
$all_predmeti = [];
while ($row = $predmeti->fetch_assoc()) {
    $all_predmeti[] = $row;
}

$current_page = basename($_SERVER['PHP_SELF']); // Pridobi trenutno stran
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje učencev</title>
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
                <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                <li><a href="manage_subjects.php" class="<?= ($current_page == 'manage_subjects.php') ? 'active' : '' ?>">Upravljanje predmetov</a></li>
                <li><a href="manage_teachers.php" class="<?= ($current_page == 'manage_teachers.php') ? 'active' : '' ?>">Upravljanje učiteljev</a></li>
                <li><a href="manage_students.php" class="<?= ($current_page == 'manage_students.php') ? 'active' : '' ?>">Upravljanje učencev</a></li>
                <li><a href="manage_classes.php" class="<?= ($current_page == 'manage_classes.php') ? 'active' : '' ?>">Upravljanje razredov</a></li>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Upravljanje učencev</h3>
            <?php
            if (isset($success)) echo "<p style='color:green;'>$success</p>";
            if (isset($error)) echo "<p style='color:red;'>$error</p>";
            ?>

            <?php if ($action == 'list'): ?>
                <a href="manage_students.php?action=add" class="button">Dodaj učenca</a>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Uporabniško ime</th>
                        <th>Ime</th>
                        <th>Priimek</th>
                        <th>Email</th>
                        <th>Predmeti</th>
                        <th>Akcije</th>
                    </tr>
                    <?php while ($ucenec = $ucenci->fetch_assoc()): ?>
                        <?php
                        // Pridobimo predmete, ki jih učenec obiskuje
                        $stmt = $conn->prepare("SELECT p.ime_predmeta FROM predmeti p INNER JOIN ucenci_predmeti up ON p.ID_predmeta = up.ID_predmeta WHERE up.ID_ucenca = ?");
                        $stmt->bind_param("i", $ucenec['ID_uporabnika']);
                        $stmt->execute();
                        $predmeti_ucenca = $stmt->get_result();
                        $predmeti_list = [];
                        while ($predmet = $predmeti_ucenca->fetch_assoc()) {
                            $predmeti_list[] = $predmet['ime_predmeta'];
                        }
                        ?>
                        <tr>
                            <td><?php echo $ucenec['ID_uporabnika']; ?></td>
                            <td><?php echo htmlspecialchars($ucenec['uporabnisko_ime']); ?></td>
                            <td><?php echo htmlspecialchars($ucenec['ime']); ?></td>
                            <td><?php echo htmlspecialchars($ucenec['priimek']); ?></td>
                            <td><?php echo htmlspecialchars($ucenec['email']); ?></td>
                            <td><?php echo implode(', ', $predmeti_list); ?></td>
                            <td>
                                <a href="manage_students.php?action=edit&id=<?php echo $ucenec['ID_uporabnika']; ?>">Uredi</a> |
                                <a href="manage_students.php?action=delete&id=<?php echo $ucenec['ID_uporabnika']; ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati tega učenca?')">Izbriši</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php elseif ($action == 'add'): ?>
                <h4>Dodaj učenca</h4>
                <form action="manage_students.php?action=add" method="post" class="form-container">
                    <label>Uporabniško ime:</label>
                    <input type="text" name="uporabnisko_ime" required><br>
                    <label>Geslo:</label>
                    <input type="password" name="geslo" required><br>
                    <label>Ime:</label>
                    <input type="text" name="ime" required><br>
                    <label>Priimek:</label>
                    <input type="text" name="priimek" required><br>
                    <label>Email:</label>
                    <input type="email" name="email" required><br>
                    <label>Predmeti:</label>
                    <?php foreach ($all_predmeti as $predmet): ?>
                        <div>
                            <input type="checkbox" name="predmeti[]" value="<?php echo $predmet['ID_predmeta']; ?>">
                            <?php echo htmlspecialchars($predmet['ime_predmeta']); ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="add_student">Dodaj učenca</button>
                    <a href="manage_students.php" class="button">Prekliči</a>
                </form>
            <?php elseif ($action == 'edit' && isset($_GET['id'])): ?>
                <?php
                $ID_ucenca = intval($_GET['id']);
                $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE ID_uporabnika = ?");
                $stmt->bind_param("i", $ID_ucenca);
                $stmt->execute();
                $ucenec = $stmt->get_result()->fetch_assoc();

                // Pridobimo predmete, ki jih učenec obiskuje
                $stmt = $conn->prepare("SELECT ID_predmeta FROM ucenci_predmeti WHERE ID_ucenca = ?");
                $stmt->bind_param("i", $ID_ucenca);
                $stmt->execute();
                $result = $stmt->get_result();
                $predmeti_ucenca = [];
                while ($row = $result->fetch_assoc()) {
                    $predmeti_ucenca[] = $row['ID_predmeta'];
                }
                ?>
                <h4>Uredi učenca</h4>
                <form action="manage_students.php?action=edit" method="post" class="form-container">
                    <input type="hidden" name="ID_ucenca" value="<?php echo $ID_ucenca; ?>">
                    <label>Uporabniško ime:</label>
                    <input type="text" name="uporabnisko_ime" value="<?php echo htmlspecialchars($ucenec['uporabnisko_ime']); ?>" required><br>
                    <label>Ime:</label>
                    <input type="text" name="ime" value="<?php echo htmlspecialchars($ucenec['ime']); ?>" required><br>
                    <label>Priimek:</label>
                    <input type="text" name="priimek" value="<?php echo htmlspecialchars($ucenec['priimek']); ?>" required><br>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($ucenec['email']); ?>" required><br>
                    <label>Predmeti:</label>
                    <?php foreach ($all_predmeti as $predmet): ?>
                        <div>
                            <input type="checkbox" name="predmeti[]" value="<?php echo $predmet['ID_predmeta']; ?>" <?php if (in_array($predmet['ID_predmeta'], $predmeti_ucenca)) echo 'checked'; ?>>
                            <?php echo htmlspecialchars($predmet['ime_predmeta']); ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="edit_student">Posodobi učenca</button>
                    <a href="manage_students.php" class="button">Prekliči</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
