<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spletna učilnica - Učitelji</title>
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src='main.js'></script>
</head>
<body>
<?php
// Database connection
$servername = "localhost";
$username = "root";  // Replace with your database username
$password = "";      // Replace with your database password
$dbname = "spletnaucilnica";  // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
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
                <li style="background-color:grey;">ZA PREGLED</li>
                <li onclick="Redirect()">PREDMETI</li>
                <li onclick="Redirect()">NASTAVITVE</li>
            </ul>
        </div>
        
        <script>
 function Redirect(){
            window.open("Spletna ucilnica - Ucitelji - 2.php", "_self");
        }



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
            function toggleDetails(element) {
    const details = element.querySelector('.assignmentDetails');
    if (details.classList.contains('open')) {
        details.classList.remove('open');
    } else {
        details.classList.add('open');
    }
}
function toggleDetails(element) {
    const details = element.querySelector('.assignmentDetails');
    details.classList.toggle('open');
}
        </script>
        <?php
$teacher_id = 1; // Example teacher ID

// Fetch subjects taught by the teacher
$sql = "SELECT ime_predmeta FROM predmet WHERE id_ucitelja = $teacher_id";
$result = $conn->query($sql);
$subjects = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $subjects[] = $row['ime_predmeta'];
    }
}
?>
        <div id="zaPregledContent" class="zaPregledContent">
        <h2>Predmeti, ki jih učim</h2>
        <ul id="subjectsList" class="subjectsList">
    <?php foreach($subjects as $subject): ?>
        <li><?php echo $subject; ?></li>
    <?php endforeach; ?>
</ul>
<?php
// Fetch tasks assigned by the teacher
$sql_tasks = "SELECT naslov_gradiva, navodilo, rok_oddaje FROM gradiva WHERE id_ucitelja = $teacher_id";
$result_tasks = $conn->query($sql_tasks);
$tasks = [];

if ($result_tasks->num_rows > 0) {
    while($row = $result_tasks->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>
        <h2>Dane Naloge</h2>
        <ul id="assignedTasksList" class="assignmentList">
    <?php foreach($tasks as $task): ?>
        <li class="assignmentItem" onclick="toggleDetails(this)">
            <span class="assignmentTitle"><?php echo $task['naslov_gradiva']; ?></span>
            <div class="assignmentDetails">
                <p>Podrobnosti naloge: <?php echo $task['navodilo']; ?></p>
                <p>Rok oddaje: <?php echo date('d. m. Y', strtotime($task['rok_oddaje'])); ?></p>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<?php
// Fetch recently submitted student tasks
$sql_submissions = "
    SELECT ucenec.ime, ucenec.priimek, gradiva.naslov_gradiva, oddanenaloge.datum_oddaje 
    FROM oddanenaloge 
    JOIN gradiva ON oddanenaloge.id_gradiva = gradiva.id_gradiva
    JOIN ucenec ON gradiva.id_ucenca = ucenec.id_ucenca
    WHERE gradiva.id_ucitelja = $teacher_id
    ORDER BY oddanenaloge.datum_oddaje DESC
    LIMIT 5";

$result_submissions = $conn->query($sql_submissions);
$submissions = [];

if ($result_submissions->num_rows > 0) {
    while($row = $result_submissions->fetch_assoc()) {
        $submissions[] = $row;
    }
}
?>
        <h2>Zadnje oddane naloge dijakov</h2>
        <ul id="submittedTasksList" class="assignmentList">
    <?php foreach($submissions as $submission): ?>
        <li class="assignmentItem" onclick="toggleDetails(this)">
            <span class="assignmentTitle"><?php echo $submission['ime'] . " " . $submission['priimek'] . " - " . $submission['naslov_gradiva']; ?></span>
            <div class="assignmentDetails">
                <p>Oddano: <?php echo date('d. m. Y', strtotime($submission['datum_oddaje'])); ?></p>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
    </div>
    </div>
</body>
</html>