<?php
// dashboard.php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$vloga = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Pridobimo ime aplikacije
$app_name = "Spletna učilnica";

// Pridobimo ime in priimek uporabnika
$stmt = $conn->prepare("SELECT ime, priimek FROM uporabniki WHERE ID_uporabnika = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$ime = $user['ime'];
$priimek = $user['priimek'];

// Pridobimo predmete, ki jih uporabnik obiskuje ali poučuje
$predmeti = array();

if ($vloga == 'učitelj') {
    // Pridobimo predmete, ki jih učitelj poučuje
    $stmt = $conn->prepare("SELECT p.ID_predmeta, p.ime_predmeta, p.vpisni_kljuc
                            FROM predmeti p
                            INNER JOIN ucitelji_predmeti up ON p.ID_predmeta = up.ID_predmeta
                            WHERE up.ID_ucitelja = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $predmeti[] = $row;
    }
} elseif ($vloga == 'učenec') {
    // Pridobimo predmete, v katere je učenec vpisan
    $stmt = $conn->prepare("SELECT p.ID_predmeta, p.ime_predmeta
                            FROM predmeti p
                            INNER JOIN ucenci_predmeti up ON p.ID_predmeta = up.ID_predmeta
                            WHERE up.ID_ucenca = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $predmeti[] = $row;
    }
}

if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}
if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}
$current_page = basename($_SERVER['PHP_SELF']); // Pridobi trenutno stran
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Nadzorna plošča</title>
    <link rel="stylesheet" href="style.css">
    <style>
    /* Stil za modalna okna */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        position: relative;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        right: 15px;
        top: 10px;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Dodatni stili */
    .subjects-grid {
        display: flex;
        flex-wrap: wrap;
    }

    .subject-card {
        border: 1px solid #ccc;
        padding: 15px;
        margin: 10px;
        width: calc(25% - 40px);
        box-sizing: border-box;
    }

    .subject-card h4 {
        margin-top: 0;
    }

    .button {
        background-color: #007acc;
        color: #fff;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
    }

    .button:hover {
        background-color: #005f99;
    }
    </style>
</head>
<body>
    <!-- Zgornja naslovna vrstica -->
    <div class="header">
        <div class="logo">
        <a href="dashboard.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
            <img src="logo2.png" alt="Logo">
            <h2>Spletna učilnica</h2>
        </a>
        </div>
        <a href="logout.php" class="logout">Odjava</a>
    </div>

    <!-- Glavni vsebinski del -->
    <div class="main-content">
        <!-- Levi stranski meni -->
        <div class="sidebar">
            <ul>
                <?php if ($vloga == 'administrator'): ?>
                    <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                    <li><a href="manage_subjects.php" class="<?= ($current_page == 'manage_subjects.php') ? 'active' : '' ?>">Upravljanje predmetov</a></li>
                    <li><a href="manage_teachers.php" class="<?= ($current_page == 'manage_teachers.php') ? 'active' : '' ?>">Upravljanje učiteljev</a></li>
                    <li><a href="manage_students.php" class="<?= ($current_page == 'manage_students.php') ? 'active' : '' ?>">Upravljanje učencev</a></li>
                    <li><a href="manage_classes.php" class="<?= ($current_page == 'manage_classes.php') ? 'active' : '' ?>">Upravljanje razredov</a></li>
                <?php elseif ($vloga == 'učitelj'): ?>
                    <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                    <li><a href="my_profile.php" class="<?= ($current_page == 'my_profile.php') ? 'active' : '' ?>">Moj profil</a></li>
                    <li><a href="view_submissions.php" class="<?= ($current_page == 'view_submissions.php') ? 'active' : '' ?>">Oddane naloge</a></li>
                <?php elseif ($vloga == 'učenec'): ?>
                    <li><a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">Nadzorna plošča</a></li>
                    <li><a href="my_profile.php" class="<?= ($current_page == 'my_profile.php') ? 'active' : '' ?>">Moj profil</a></li>
                    <li><a href="my_assignments.php" class="<?= ($current_page == 'my_assignments.php') ? 'active' : '' ?>">Moje naloge</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Vsebina -->
        <div class="content">
            <h3>Pozdravljeni, <?php echo htmlspecialchars($ime . ' ' . $priimek); ?>!</h3>

            <?php
            if (isset($success)) {
                echo "<p style='color:green;'>$success</p>";
            }
            if (isset($error)) {
                echo "<p style='color:red;'>$error</p>";
            }
            ?>
            
            <?php if ($vloga == 'učenec'): ?>
                <h4>Moji predmeti:
                    <button onclick="document.getElementById('subjectList').style.display='block'">+</button>
                </h4>

                <!-- Modal za seznam predmetov -->
                <div id="subjectList" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="document.getElementById('subjectList').style.display='none'">&times;</span>
                        <h3>Pridruži se predmetu</h3>
                        <?php
                        // Pridobimo vse predmete, v katere učenec še ni vpisan
                        $stmt = $conn->prepare("SELECT * FROM predmeti WHERE ID_predmeta NOT IN (SELECT ID_predmeta FROM ucenci_predmeti WHERE ID_ucenca = ?)");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $available_subjects = $stmt->get_result();
                        ?>
                        <?php if ($available_subjects->num_rows > 0): ?>
                            <ul>
                                <?php while ($subject = $available_subjects->fetch_assoc()): ?>
                                    <li>
                                        <a href="#" onclick="showEnrollmentForm(<?php echo $subject['ID_predmeta']; ?>, '<?php echo htmlspecialchars(addslashes($subject['ime_predmeta'])); ?>')">
                                            <?php echo htmlspecialchars($subject['ime_predmeta']); ?>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p>Ni predmetov, ki bi se jim lahko pridružili.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Modal za vpisni obrazec -->
                <div id="enrollmentForm" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="document.getElementById('enrollmentForm').style.display='none'">&times;</span>
                        <h3 id="enrollmentSubjectName">Vpiši se v predmet</h3>
                        <form action="enroll_subject.php" method="post" class="form-container">
                            <input type="hidden" name="subject_id" id="subject_id">
                            <label>Vpisni ključ:</label>
                            <input type="text" name="vpisni_kljuc"><br>
                            <button type="submit" name="enroll">Vpiši se</button>
                        </form>
                    </div>
                </div>
            <?php elseif ($vloga == 'učitelj'): ?>
                <h4>Moji predmeti:</h4>
            <?php endif; ?>

            <?php if ($vloga == 'učitelj' || $vloga == 'učenec'): ?>
                <div class="subjects-grid">
                    <?php if (!empty($predmeti)): ?>
                        <?php foreach ($predmeti as $predmet): ?>
                            <div class="subject-card">
                                <h4><?php echo htmlspecialchars($predmet['ime_predmeta']); ?></h4>
                                <a href="subject.php?id=<?php echo $predmet['ID_predmeta']; ?>">Vstopi v predmet</a>
                                <?php if ($vloga == 'učitelj'): ?>
                                    <p>Vpisni ključ: <?php echo htmlspecialchars($predmet['vpisni_kljuc']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Trenutno nimate predmetov.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript funkcije -->
    <script>
    function showEnrollmentForm(subjectId, subjectName) {
        document.getElementById('subject_id').value = subjectId;
        document.getElementById('enrollmentSubjectName').innerText = "Vpiši se v predmet: " + subjectName;
        document.getElementById('enrollmentForm').style.display = 'block';
        document.getElementById('subjectList').style.display = 'none';
    }

    // Zapremo modal, ko kliknemo zunaj njega
    window.onclick = function(event) {
        var modal1 = document.getElementById('subjectList');
        var modal2 = document.getElementById('enrollmentForm');
        if (event.target == modal1) {
            modal1.style.display = "none";
        }
        if (event.target == modal2) {
            modal2.style.display = "none";
        }
    }
    </script>
</body>
</html>
