<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spletna učilnica - Učitelji</title>
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

// Define $teacher_id (assuming it's stored in a session or fetched elsewhere)
$teacher_id = 1;  // Replace with the actual teacher ID
?>
<header>
    <ul id="headerList">
        <li><img id="logoPic" src="Slike/book.png"></li>
        <li id="headerText">SPLETNA UČILNICA</li>
        <li><img style="height: 80px;margin: 10% 0 0 0;" src="Slike/user.png"></li>
    </ul>
</header>

<div class="outerDiv">
    <div class="navigationDiv">
        <ul id="navigationList">
            <li onclick="Redirect()">ZA PREGLED</li>
            <li style="background-color:grey;">PREDMETI</li>
            <li onclick="Redirect()">NASTAVITVE</li>
        </ul>
    </div>

    <script>
        function Redirect(){
            window.open("Spletna ucilnica - Ucitelji.php", "_self");
        }





        var selectedItemText = "PREDMETI";
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

        function loadSubjectTasks(subjectId) {
            $('#subjectIdInput').val(subjectId); // Set subject ID in hidden input for task creation
            $.ajax({
                url: 'get_tasks.php',
                type: 'POST',
                data: { subject_id: subjectId },
                success: function(response) {
                    $('#tasksContainer').html(response);
                }
            });
        }
    </script>

    <div id="mainContent">
        <!-- Izbor predmeta -->
        <form id="subjectSelectForm">
            <label for="subjectSelect">Izberi predmet:</label>
            <select id="subjectSelect" name="subjectSelect" onchange="loadSubjectTasks(this.value)">
                <option value="">Izberi predmet</option>
                <?php
                // Fetch subjects taught by the teacher
                $sql = "SELECT id_predmeta, ime_predmeta FROM predmet WHERE id_ucitelja = $teacher_id";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id_predmeta']}'>{$row['ime_predmeta']}</option>";
                    }
                }
                ?>
            </select>
        </form>

        <!-- Prikaz nalog za izbran predmet -->
        <div id="tasksContainer">
            <!-- Naloge se bodo naložile tukaj preko AJAX-a -->
        </div>

        <!-- Obrazec za dodajanje nove naloge -->
        <div id="addTaskForm">
            <h3>Dodaj novo nalogo</h3>
            <form id="taskForm" action="add_task.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="subject_id" id="subjectIdInput">
                <label for="title">Naslov naloge:</label>
                <input type="text" id="title" name="title" required>

                <label for="description">Opis naloge:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="deadline">Rok oddaje:</label>
                <input type="datetime-local" id="deadline" name="deadline" required>

                <label for="file">Dodaj datoteko (neobvezno):</label>
                <input type="file" id="file" name="file">

                <button type="submit">Dodaj nalogo</button>
            </form>
        </div>
    </div>
</div>

<?php
// Check if the form is submitted
if ($_SERVER['add_task.php'] === 'POST') {
    // Get form data
    $subject_id = $_POST['subject_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $file = $_FILES['file']['name'];

    // File upload handling (if provided)
    if (!empty($file)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file);
        
        // Attempt to move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // File uploaded successfully
        } else {
            echo "Napaka pri nalaganju datoteke.";
        }
    } else {
        $file = null;
    }

    // Insert new task into the database
    $sql = "INSERT INTO gradiva (naslov_gradiva, navodilo, datoteke, rok_oddaje, id_ucitelja, id_predmeta) 
            VALUES ('$title', '$description', '$file', '$deadline', $teacher_id, $subject_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Nova naloga je bila uspešno dodana.";
        exit();
    } else {
        echo "Napaka: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

</body>
</html>
