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
        <div class="assignmentWrapper">
        <h1>Ime naloge</h1>
        <div class="taskDetails">
            <p><strong>Predmet:</strong> Matematika</p>
            <p><strong>Naslov naloge:</strong> Reši enačbe</p>
            <p><strong>Opis:</strong> Reši 10 enačb, ki vključujejo linearne in kvadratne enačbe. Uporabi pravilne metode za reševanje in preveri rezultate.</p>
            <p><strong>Rok za oddajo:</strong> 30. september 2024</p>
        </div>

        <div class="submissionFormWrapper">
            <h3>Oddaj nalogo</h3>
            <form action="upload_assignment.php" method="post" enctype="multipart/form-data">
                <label for="fileInput">Izberi datoteko za oddajo:</label>
                <input type="file" id="fileInput" name="fileInput" required>

                <input type="submit" value="Oddaj nalogo">
            </form>
        </div>
    </div>
    </div>
</body>
</html>