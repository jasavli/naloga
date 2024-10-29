<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Spletna učilnica - Predmeti</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
</head>
<body>

<header>
    <ul id="headerList">
        <li><img id="logoPic" src="Slike/book.png"></li>
        <li id="headerText">SPLETNA UČILNICA</li>
        <li><button id="logoButton"></button></li>
    </ul>
</header>
<script>
    function Redirect(){
            window.open("Spletna ucilnica - Administrator.php", "_self");
        }
     function Redirect1(){
            window.open("Spletna ucilnica - Administrator - 1.php", "_self");
        }
        function Redirect2(){
            window.open("Spletna ucilnica - Administrator - 2.php", "_self");
        }
        function Redirect3(){
            window.open("Spletna ucilnica - Administrator - 3.php", "_self");
        }
</script>
<div class="outerDiv">
    <div class="navigationDiv">
        <ul id="navigationList">
            <li onclick="Redirect()" >UČITELJI</li>
            <li onclick="Redirect1()">UČENCI</li>
            <li onclick="Redirect3()" style="background-color:grey;">PREDMETI</li>
            <li onclick="Redirect2()">RAZREDI</li>
        </ul>
    </div>
    
    <div id="subjectGrid" class="optionGrid">
        <!-- Subject names will be dynamically populated here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjects = <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "spletnaucilnica";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch subject names
            $sql = "SELECT ime_predmeta FROM predmet ORDER BY ime_predmeta"; // Assuming 'predmet' table has a 'naziv' column
            $result = $conn->query($sql);

            $subjects = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $subjects[] = $row['ime_predmeta']; // Adjust according to your database structure
                }
            }

            // Close connection
            $conn->close();

            // Convert PHP array to JSON for JavaScript
            echo json_encode($subjects);
        ?>;

        const grid = document.getElementById('subjectGrid');
        grid.innerHTML = ''; // Clear existing grid items

        subjects.forEach(subject => {
            const gridItem = document.createElement('div');
            gridItem.className = 'gridItem';
            gridItem.textContent = subject;
            grid.appendChild(gridItem);
        });
    });
</script>

</body>
</html>
