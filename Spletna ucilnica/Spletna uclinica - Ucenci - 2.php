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
            function toggleDetails(element) {
                var details = element.nextElementSibling;
                details.classList.toggle("open");
}

        </script>
        <div class="assignmentSection">
        <h2>Seznam nalog</h2>
        <ul class="assignmentList">
            <li class="assignmentItem">
                <div class="assignmentTitle" onclick="toggleDetails(this)">Matematika: Reši enačbe</div>
                <div class="assignmentDetails">
                    <p>Opis: Reši 10 enačb različnih težavnosti, ki vključujejo kvadratne enačbe in logaritme.</p>
                    <p>Rok: 30. september 2024</p>
                </div>
            </li>
            <li class="assignmentItem">
                <div class="assignmentTitle" onclick="toggleDetails(this)">Slovenščina: Napiši esej</div>
                <div class="assignmentDetails">
                    <p>Opis: Napiši esej na temo "Pomembnost slovenskega jezika v sodobni družbi".</p>
                    <p>Rok: 1. oktober 2024</p>
                </div>
            </li>
            <li class="assignmentItem">
                <div class="assignmentTitle" onclick="toggleDetails(this)">Zgodovina: Pripravi predstavitev</div>
                <div class="assignmentDetails">
                    <p>Opis: Pripravi 10-minutno predstavitev o vzrokih prve svetovne vojne.</p>
                    <p>Rok: 28. september 2024</p>
                </div>
            </li>
            <li class="assignmentItem">
                <div class="assignmentTitle" onclick="toggleDetails(this)">Kemija: Eksperimentiraj</div>
                <div class="assignmentDetails">
                    <p>Opis: Opravi eksperiment, kjer preizkusiš kislost različnih gospodinjskih snovi.</p>
                    <p>Rok: 2. oktober 2024</p>
                </div>
            </li>
        </ul>
    </div>
    </div>
</body>
</html>