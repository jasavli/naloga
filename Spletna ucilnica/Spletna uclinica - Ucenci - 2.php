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
                <li onclick = "OpenNew()">PREDMETI</li>
                <li style="background-color:grey;">ZA PREGLED</li>
            </ul>
        </div>
        
        <script>
            function OpenNew(){
                window.open("Spletna%20uclinica%20-%20Ucenci.php","_self");
            }


        </script>
        <ul class="allWorks">
            <li>Hello</li>
        </ul>
    </div>
</body>
</html>