
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
    <title>Spletna ucilnica - Prijava</title>
    <style>
        body {
    font-family: Montserrat;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    padding: 0;
    background: #fff;
  }

    .registration-form {
    background-color: #fff;
    width: 300px;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}

.form-control {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.register-button {
    width: 100%;
    padding: 10px;
    background-color: #e35e20;
    border: none;
    border-radius: 4px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    display: block;
    line-height: normal;
}

.register-button:hover {
    background-color: #ff7700;
}

.login-link, .register-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #e35e20;
}

.login-link a, .register-link a {
    color: #e35e20;
    text-decoration: none;
}

.login-link a:hover, .register-link a:hover {
    text-decoration: underline;
}

.error-message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
    </style>
</head>
<body>
<div class="registration-form">
    <form action="register.php" method="POST">
        <h2>Registracija</h2>
        <label for="firstname">Ime</label>
        <input type="text" id="firstname" name="firstname" class="form-control" required>

        <label for="lastname">Priimek</label>
        <input type="text" id="lastname" name="lastname" class="form-control" required>

        <label for="username">Uporabniško ime</label>
        <input type="text" id="username" name="username" class="form-control" required>

        <label for="email">E-poštni naslov</label>
        <input type="email" id="email" name="email" class="form-control" required>

        <label for="password">Geslo</label>
        <input type="password" id="password" name="password" class="form-control" required>

        <label for="confirm_password">Potrdi geslo</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>

        <button type="submit" class="register-button">POTRDI</button>
        
        <p class="login-link"><a href="Spletna ucilnica - login.php">Že imaš račun?</a></p>
    </form>
</div>
</body>
</html>