
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src='main.js'></script>
    <title>Spletna ucilnica - Prijava</title>
    <style>
    </style>
</head>
<body>
<div class="form-container">
    <h2 class="form-title">Prijava</h2>
    <form action="login.php" method="POST">
        <label class="form-label" for="email">E-poštni naslov</label>
        <input type="email" id="email" name="email" class="form-input" required>

        <label class="form-label" for="password">Geslo</label>
        <input type="password" id="password" name="password" class="form-input" required>

        <button type="submit" class="form-button">Prijava</button>
    </form>
    <p class="form-link"><a href="register.php">Nimate računa? Registrirajte se tukaj.</a></p>
</div>

</body>
</html>