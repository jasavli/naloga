<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Spletna učilnica</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src='main.js'></script>
</head>
<body>
<?php
function openDatabaseConnection(){
    $link = new mysqli("localhost", "root", "", "SpletnaUcilnica");
    $link->query("SET NAMES 'utf8'");
    return $link;
}
function closeDatabaseConnection($link){
    mysqli_close($link);
}
$link = openDatabaseConnection();
session_start();
$mail = $_SESSION['mail'];
$sql = "SELECT ime, priimek, mail, geslo, tel_st, kabinet FROM Uporabnik WHERE mail = '$mail'";
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)) {
        $ime = $row["ime"];
        $priimek = $row["priimek"];
        $mail = $row["mail"];
        $geslo = $row["geslo"];
        $tel_st = $row["tel_st"];
        $kabinet = $row["kabinet"];
    }
    echo "<script>
    window.onload = function() {
    document.getElementById('logoButton').innerHTML = '$ime' . ' ' . '$priimek'};
    </script>";
}





closeDatabaseConnection($link)

?>



    <header>
        <ul id="headerList">
            <li><img id="logoPic"src="Slike/book.png"></li>
            <li id="headerText">SPLETNA UČILNICA</li>
            <li><button id="logoButton"></button></li>
        </ul>
    </header>

    <div class="outerDiv">
        <div class="navigationDiv">
            <ul id="navigationList">
                <li onclick="SelectedItem(this)" style="background-color:grey;">UČITELJI</li>
                <li onclick="SelectedItem(this)">UČENCI</li>
                <li onclick="SelectedItem(this)">PREDMETI</li>
                <li onclick="SelectedItem(this)">RAZREDI</li>
            </ul>
        </div>
        
        <script>
            var selectedItemText = "UČITELJI";
            function SelectedItem(element){
                UnselectElements(element);
                if(element.innerText !== selectedItemText){
                    selectedItemText = element.innerText;
                    element.style.backgroundColor = "grey";
                }
            }
        
            function UnselectElements(selectedElement){
                const nav = document.getElementById("navigationList").children;
                for (let child of nav) {
                    if (child !== selectedElement) {
                        child.style.backgroundColor = "lightgrey";
                    }
                }
            }
        </script>
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
</body>
</html>