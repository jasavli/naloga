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
    <header>
        <ul id="headerList">
            <li><img id="logoPic"src="Slike/book.png"></li>
            <li id="headerText">SPLETNA UČILNICA</li>
            <li><button id="logoButton"><img style="height: 80px;margin: 10% 0 0 0;"src="Slike/user.png"></button></li>
        </ul>
    </header>
    <div class="optionsDiv">
        <form>
            <br>
            <label for="ime">Ime:</label><br>
            <input type="text" id="ime" name="ime"><br><br>
            
            <label for="priimek">Priimek:</label><br>
            <input type="text" id="priimek" name="priimek"><br><br>
            
            <label for="mail">Mail:</label><br>
            <input type="text" id="mail" name="mail"><br><br>

            <label for="telefon">Telefonska številka:</label><br>
            <input type="text" id="telefon" name="telefon"><br><br>

            <label for="geslo">Geslo:</label><br>
            <input type="password" id="geslo" name="geslo"><br><br>
            
            <input type="submit" value="Potrdi">
        </form>
    </div>
    
</body>
</html>