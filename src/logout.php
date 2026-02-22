<?php
session_start();

// Vaciamos las variables
$_SESSION = array();

// Destruimos la cookie de sesión en el navegador (Seguridad estricta)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruimos la sesión en el servidor
session_destroy();

header("Location: login.php");
exit();
?>