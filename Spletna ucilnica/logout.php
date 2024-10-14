<?php
session_start();

// Uničimo vse sejne spremenljivke
session_unset();

// Uničimo sejo
session_destroy();

// Preusmerimo uporabnika na stran za prijavo
header("Location: login.php");
exit();
?>
