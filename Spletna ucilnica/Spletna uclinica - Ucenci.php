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
    <style>
        /* Stil za prikaz imena uporabnika in gumb za odjavo */
        .user-info {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 18px;
        }

        .user-info a {
            margin-left: 10px;
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .user-info a:hover {
            background-color: #d32f2f;
        }
    </style>
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
            <a href="logout.php">Odjava</a>
        </li>
    </ul>
</header>

<div class="outerDiv">
    <div class="navigationDiv">
        <ul id="navigationList">
            <li style="background-color:grey;">PREDMETI</li>
            <li onclick="OpenNew()">ZA PREGLED</li>
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

<script>
    function OpenNew(){
        window.open("Spletna%20uclinica%20-%20Ucenci%20-%202.php", "_self");
    }
</script>
</body>
</html>
