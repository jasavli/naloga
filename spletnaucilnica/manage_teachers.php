<?php
// manage_teachers.php
session_start();
include('config.php');

// Preverimo, ali je uporabnik prijavljen in ali je administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrator') {
    header("Location: index.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Dodajanje novega učitelja
if ($action == 'add' && isset($_POST['add_teacher'])) {
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
        // Vstavimo učitelja v bazo
        $stmt = $conn->prepare("INSERT INTO uporabniki (uporabnisko_ime, geslo, ime, priimek, email, vloga) VALUES (?, ?, ?, ?, ?, 'učitelj')");
        $stmt->bind_param("sssss", $uporabnisko_ime, $geslo, $ime, $priimek, $email);
        if ($stmt->execute()) {
            $ID_ucitelja = $stmt->insert_id;
            // Dodelimo predmete učitelju
            foreach ($predmeti as $ID_predmeta) {
                $stmt = $conn->prepare("INSERT INTO ucitelji_predmeti (ID_ucitelja, ID_predmeta) VALUES (?, ?)");
                $stmt->bind_param("ii", $ID_ucitelja, $ID_predmeta);
                $stmt->execute();
            }
            $success = "Učitelj uspešno dodan.";
        } else {
            $error = "Napaka pri dodajanju učitelja: " . $conn->error;
        }
    }
}

// Urejanje učitelja
if ($action == 'edit' && isset($_POST['edit_teacher'])) {
    $ID_ucitelja = intval($_POST['ID_ucitelja']);
    $uporabnisko_ime = $conn->real_escape_string($_POST['uporabnisko_ime']);
    $ime = $conn->real_escape_string($_POST['ime']);
    $priimek = $conn->real_escape_string($_POST['priimek']);
    $email = $conn->real_escape_string($_POST['email']);
    $predmeti = isset($_POST['predmeti']) ? $_POST['predmeti'] : [];

    // Posodobimo podatke o učitelju
    $stmt = $conn->prepare("UPDATE uporabniki SET uporabnisko_ime = ?, ime = ?, priimek = ?, email = ? WHERE ID_uporabnika = ?");
    $stmt->bind_param("ssssi", $uporabnisko_ime, $ime, $priimek, $email, $ID_ucitelja);
    if ($stmt->execute()) {
        // Posodobimo dodeljene predmete
        // Najprej izbrišemo obstoječe dodelitve
        $stmt = $conn->prepare("DELETE FROM ucitelji_predmeti WHERE ID_ucitelja = ?");
        $stmt->bind_param("i", $ID_ucitelja);
        $stmt->execute();
        // Nato dodamo nove dodelitve
        foreach ($predmeti as $ID_predmeta) {
            $stmt = $conn->prepare("INSERT INTO ucitelji_predmeti (ID_ucitelja, ID_predmeta) VALUES (?, ?)");
            $stmt->bind_param("ii", $ID_ucitelja, $ID_predmeta);
            $stmt->execute();
        }
        $success = "Učitelj uspešno posodobljen.";
    } else {
        $error = "Napaka pri posodabljanju učitelja: " . $conn->error;
    }
}

// Brisanje učitelja
if ($action == 'delete' && isset($_GET['id'])) {
    $ID_ucitelja = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM uporabniki WHERE ID_uporabnika = ?");
    $stmt->bind_param("i", $ID_ucitelja);
    if ($stmt->execute()) {
        $success = "Učitelj uspešno izbrisan.";
    } else {
        $error = "Napaka pri brisanju učitelja: " . $conn->error;
    }
}

// Pridobimo seznam učiteljev
$stmt = $conn->prepare("SELECT * FROM uporabniki WHERE vloga = 'učitelj'");
$stmt->execute();
$ucitelji = $stmt->get_result();

// Pridobimo seznam predmetov
$stmt = $conn->prepare("SELECT * FROM predmeti");
$stmt->execute();
$predmeti = $stmt->get_result();
$all_predmeti = [];
while ($row = $predmeti->fetch_assoc()) {
    $all_predmeti[] = $row;
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje učiteljev</title>
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
                <li><a href="dashboard.php">Nadzorna plošča</a></li>
                <li><a href="manage_subjects.php">Upravljanje predmetov</a></li>
                <li><a href="manage_teachers.php" class="active">Upravljanje učiteljev</a></li>
                <li><a href="manage_students.php">Upravljanje učencev</a></li>
                <li><a href="logout.php">Odjava</a></li>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Upravljanje učiteljev</h3>
            <?php
            if (isset($success)) echo "<p style='color:green;'>$success</p>";
            if (isset($error)) echo "<p style='color:red;'>$error</p>";
            ?>

            <?php if ($action == 'list'): ?>
                <a href="manage_teachers.php?action=add" class="button">Dodaj učitelja</a>
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
                    <?php while ($ucitelj = $ucitelji->fetch_assoc()): ?>
                        <?php
                        // Pridobimo predmete, ki jih učitelj poučuje
                        $stmt = $conn->prepare("SELECT p.ime_predmeta FROM predmeti p INNER JOIN ucitelji_predmeti up ON p.ID_predmeta = up.ID_predmeta WHERE up.ID_ucitelja = ?");
                        $stmt->bind_param("i", $ucitelj['ID_uporabnika']);
                        $stmt->execute();
                        $predmeti_ucitelja = $stmt->get_result();
                        $predmeti_list = [];
                        while ($predmet = $predmeti_ucitelja->fetch_assoc()) {
                            $predmeti_list[] = $predmet['ime_predmeta'];
                        }
                        ?>
                        <tr>
                            <td><?php echo $ucitelj['ID_uporabnika']; ?></td>
                            <td><?php echo htmlspecialchars($ucitelj['uporabnisko_ime']); ?></td>
                            <td><?php echo htmlspecialchars($ucitelj['ime']); ?></td>
                            <td><?php echo htmlspecialchars($ucitelj['priimek']); ?></td>
                            <td><?php echo htmlspecialchars($ucitelj['email']); ?></td>
                            <td><?php echo implode(', ', $predmeti_list); ?></td>
                            <td>
                                <a href="manage_teachers.php?action=edit&id=<?php echo $ucitelj['ID_uporabnika']; ?>">Uredi</a> |
                                <a href="manage_teachers.php?action=delete&id=<?php echo $ucitelj['ID_uporabnika']; ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati tega učitelja?')">Izbriši</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php elseif ($action == 'add'): ?>
                <h4>Dodaj učitelja</h4>
                <form action="manage_teachers.php?action=add" method="post" class="form-container">
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
                    <button type="submit" name="add_teacher">Dodaj učitelja</button>
                    <a href="manage_teachers.php" class="button">Prekliči</a>
                </form>
            <?php elseif ($action == 'edit' && isset($_GET['id'])): ?>
                <?php
                $ID_ucitelja = intval($_GET['id']);
                $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE ID_uporabnika = ?");
                $stmt->bind_param("i", $ID_ucitelja);
                $stmt->execute();
                $ucitelj = $stmt->get_result()->fetch_assoc();

                // Pridobimo predmete, ki jih učitelj poučuje
                $stmt = $conn->prepare("SELECT ID_predmeta FROM ucitelji_predmeti WHERE ID_ucitelja = ?");
                $stmt->bind_param("i", $ID_ucitelja);
                $stmt->execute();
                $result = $stmt->get_result();
                $predmeti_ucitelja = [];
                while ($row = $result->fetch_assoc()) {
                    $predmeti_ucitelja[] = $row['ID_predmeta'];
                }
                ?>
                <h4>Uredi učitelja</h4>
                <form action="manage_teachers.php?action=edit" method="post" class="form-container">
                    <input type="hidden" name="ID_ucitelja" value="<?php echo $ID_ucitelja; ?>">
                    <label>Uporabniško ime:</label>
                    <input type="text" name="uporabnisko_ime" value="<?php echo htmlspecialchars($ucitelj['uporabnisko_ime']); ?>" required><br>
                    <label>Ime:</label>
                    <input type="text" name="ime" value="<?php echo htmlspecialchars($ucitelj['ime']); ?>" required><br>
                    <label>Priimek:</label>
                    <input type="text" name="priimek" value="<?php echo htmlspecialchars($ucitelj['priimek']); ?>" required><br>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($ucitelj['email']); ?>" required><br>
                    <label>Predmeti:</label>
                    <?php foreach ($all_predmeti as $predmet): ?>
                        <div>
                            <input type="checkbox" name="predmeti[]" value="<?php echo $predmet['ID_predmeta']; ?>" <?php if (in_array($predmet['ID_predmeta'], $predmeti_ucitelja)) echo 'checked'; ?>>
                            <?php echo htmlspecialchars($predmet['ime_predmeta']); ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="edit_teacher">Posodobi učitelja</button>
                    <a href="manage_teachers.php" class="button">Prekliči</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
