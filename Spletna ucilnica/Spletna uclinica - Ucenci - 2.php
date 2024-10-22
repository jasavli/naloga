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

// Pridobimo gradiva za pregled, kjer je prijavljen uporabnik učitelj, vključno z datotekami
$id_ucitelja = $_SESSION['user_id'];
$query = "SELECT naslov_gradiva, navodilo, datoteke, rok_oddaje FROM gradiva WHERE id_ucitelja = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $id_ucitelja);
$stmt->execute();
$result = $stmt->get_result();

$gradiva = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gradiva[] = $row;
    }
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
    <title>Spletna učilnica - Naloge za pregled</title>
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src='main.js'></script>
</head>
<body>
<header>
    <ul id="headerList">
        <li><img id="logoPic" src="Slike/book.png"></li>
        <li id="headerText">SPLETNA UČILNICA</li>
        <li class="user-info">
            <!-- Prikaz imena in priimka prijavljenega uporabnika -->
            <?php echo $_SESSION['user_name']; ?>
            <!-- Povezava za odjavo -->
            <a href="logout.php" style="background-color: #f44336; color: white; padding: 5px 10px; border-radius: 5px;">Odjava</a>
        </li>
    </ul>
</header>

<div class="outerDiv">
    <div class="navigationDiv">
        <ul id="navigationList">
            <li onclick="window.location.href='Spletna%20uclinica%20-%20Ucenci.php'">PREDMETI</li>
            <li style="background-color: grey;">ZA PREGLED</li>
        </ul>
    </div>

    <div class="assignmentSection">
        <h2>Naloge za pregled</h2>
        <ul class="assignmentList">
            <?php if (empty($gradiva)): ?>
                <li class="assignmentItem">Trenutno ni nalog za pregled.</li>
            <?php else: ?>
                <?php foreach ($gradiva as $gradiva): ?>
                    <li class="assignmentItem">
                        <div class="assignmentTitle" onclick="toggleDetails(this)">
                            <?php echo htmlspecialchars($gradiva['naslov_gradiva']); ?>
                        </div>
                        <div class="assignmentDetails">
                            <p><?php echo htmlspecialchars($gradiva['navodilo']); ?></p>
                            <p>Rok oddaje: <?php echo htmlspecialchars($gradiva['rok_oddaje']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<script>
    function toggleDetails(element) {
        var details = element.nextElementSibling;
        details.classList.toggle("open");
    }
</script>

</body>
</html>
