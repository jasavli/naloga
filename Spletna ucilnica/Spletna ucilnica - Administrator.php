<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Spletna učilnica</title>
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
            <li onclick="Redirect()" style="background-color:grey;">UČITELJI</li>
            <li onclick="Redirect1()">UČENCI</li>
            <li onclick="Redirect3()">PREDMETI</li>
            <li onclick="Redirect2()">RAZREDI</li>
        </ul>
    </div>
    
    <div id="teacherGrid" class="optionGrid">
        <!-- Teacher names will be dynamically populated here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const teachers = <?php
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

            // Fetch teacher names
            $sql = "SELECT ime, priimek FROM ucitelj ORDER BY ime, priimek";
            $result = $conn->query($sql);

            $teachers = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $teachers[] = $row['ime'] . ' ' . $row['priimek'];
                }
            }

            // Close connection
            $conn->close();

            // Convert PHP array to JSON for JavaScript
            echo json_encode($teachers);
        ?>;

        const grid = document.getElementById('teacherGrid');
        teachers.forEach(teacher => {
            const gridItem = document.createElement('div');
            gridItem.className = 'gridItem';
            gridItem.textContent = teacher;
            grid.appendChild(gridItem);
        });
    });

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

</body>
</html>
