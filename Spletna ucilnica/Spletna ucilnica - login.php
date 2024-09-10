
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
    .login-form {
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

.login-button {
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

.login-button:hover {
    background-color: #ff7700;
}

.register-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #e35e20;
}

.register-link a{
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #e35e20;
    text-decoration: none;
}

.register-link a:hover {
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
    <div class="login-form">

        <form action="login.php" method="POST">
            <h2>Prijavi se v svoj račun</h2>
            <label for="email">Elektronski naslov</label>
            <input type="email" id="email" name="email" class="form-control" required>

            <label for="password">Geslo</label>
            <input type="password" id="password" name="password" class="form-control" required>

            <button type="submit" class="login-button">PRIJAVA</button>
            
            <p class="register-link"><a href="Spletna ucilnica - register.php">Registriraj se</a></p>
        </form>
    </div>
</body>
</html>