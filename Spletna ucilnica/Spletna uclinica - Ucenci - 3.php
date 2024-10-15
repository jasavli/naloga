<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    // Če uporabnik ni prijavljen, ga preusmerimo na prijavno stran
    header("Location: login.php");
    exit();
}

// Povezava z bazo podatkov
$link = new mysqli("localhost", "root", "", "SpletnaUcilnica");

if ($link->connect_error) {
    die("Povezava ni uspela: " . $link->connect_error);
}

// Pridobimo ID prijavljenega učenca
$id_ucenca = $_SESSION['user_id'];

// Pridobimo gradivo za tega učenca
$query = "SELECT g.naslov_gradiva, g.navodilo, g.rok_oddaje, p.ime_predmeta, g.datoteke 
          FROM gradiva g
          JOIN predmet p ON p.id_predmeta = g.id_ucenca
          WHERE g.id_ucenca = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $id_ucenca);
$stmt->execute();
$result = $stmt->get_result();

// Shranimo podatke o nalogah
$gradiva = [];
if ($result->num_rows > 0) {
    $gradiva = $result->fetch_assoc();  // Ker gre verjetno za eno nalogo na stran
}

$stmt->close();
$link->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spletna učilnica</title>
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src='main.js'></script>
</head>
<body>
<header>
    <ul id="headerList">
        <li><img id="logoPic" src="Slike/book.png"></li>
        <li id="headerText">SPLETNA UČILNICA</li>
        <li>
            <!-- Prikaz prijavljenega učenca -->
            <?php echo $_SESSION['user_name']; ?>
            <a href="logout.php" style="background-color: #f44336; color: white; padding: 5px 10px; border-radius: 5px;">Odjava</a>
        </li>
    </ul>
</header>

<div class="outerDiv">
    <div class="navigationDiv">
        <ul id="navigationList">
            <li onclick="window.location.href='Spletna%20uclinica%20-%20Ucenci.php'">PREDMETI</li>
            <li style="background-color:grey;">ZA PREGLED</li>
        </ul>
    </div>

    <div class="assignmentWrapper">
        <!-- Prikaz naloge -->
        <?php if (!empty($gradiva)): ?>
        <h1><?php echo htmlspecialchars($gradiva['naslov_gradiva']); ?></h1>
        <div class="taskDetails">
            <p><strong>Predmet:</strong> <?php echo htmlspecialchars($gradiva['ime_predmeta']); ?></p>
            <p><strong>Naslov naloge:</strong> <?php echo htmlspecialchars($gradiva['naslov_gradiva']); ?></p>
            <p><strong>Opis:</strong> <?php echo htmlspecialchars($gradiva['navodilo']); ?></p>
            <p><strong>Rok za oddajo:</strong> <?php echo htmlspecialchars($gradiva['rok_oddaje']); ?></p>

            <!-- Prikaz datotek -->
            <?php if (!empty($gradiva['datoteke'])): ?>
                <p><a href="uploads/<?php echo htmlspecialchars($gradiva['datoteke']); ?>" download>Prenesi datoteko</a></p>
            <?php else: ?>
                <p>Ni priloženih datotek.</p>
            <?php endif; ?>
        </div>

        <div class="submissionFormWrapper">
            <h3>Oddaj nalogo</h3>
            <form action="upload_assignment.php" method="post" enctype="multipart/form-data">
                <label for="fileInput">Izberi datoteko za oddajo:</label>
                <input type="file" id="fileInput" name="fileInput" required>

                <input type="submit" value="Oddaj nalogo">
            </form>
        </div>
        <?php else: ?>
            <p>Ni nalog za ta predmet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
