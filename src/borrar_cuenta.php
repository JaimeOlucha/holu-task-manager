<?php
session_start();

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

$usuario_id = $_SESSION['usuario_id'];

// Preparamos y ejecutamos el borrado
$statement_sql = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
$statement_sql->bind_param("i", $usuario_id);

if ($statement_sql->execute()) {
    $statement_sql->close();

    // Destrucción total de sesión (Variables, Cookie y Servidor)
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();

    header("Location: login.php?deleted=true");
    exit();
} else {
    $statement_sql->close();
    $_SESSION['mensaje_error'] = "No se pudo eliminar la cuenta.";
    header("Location: index.php");
    exit();
}
?>