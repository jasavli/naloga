<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    // Če uporabnik ni prijavljen, ga preusmerimo na prijavno stran
    header("Location: login.php");
    exit();
}
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
            <li><img id="logoPic"src="Slike/book.png"></li>
            <li id="headerText">SPLETNA UČILNICA</li>
            <li><img style="height: 80px;margin: 10% 0 0 0;"src="Slike/user.png"></li>
        </ul>
</header>

<div class="outerDiv">
    <div class="navigationDiv">
        <ul id="navigationList">
            <li style="background-color:grey;">PREDMETI</li>
            <li onclick = "OpenNew()">ZA PREGLED</li>
        </ul>
    </div>

    <div class="optionGrid">
        <div class="gridItem"></div>
        <div class="gridItem"></div>
        <div class="gridItem"></div>
        <div class="gridItem"></div>
        <div class="gridItem"></div>
        <div class="gridItem"></div>
        <div class="gridItem"></div>
        <div class="gridItem"></div>
    </div>
</div>

<!-- Prikaz prijavljenega uporabnika -->
<div class="user-info">
    <p>Prijavljeni ste kot: <?php echo $_SESSION['user_name']; ?></p>
    <a href="logout.php">Odjava</a> <!-- Odjava uporabnika -->
</div>

<script>
    function OpenNew(){
        window.open("Spletna%20uclinica%20-%20Ucenci%20-%202.php", "_self");
    }
</script>
</body>
</html>
