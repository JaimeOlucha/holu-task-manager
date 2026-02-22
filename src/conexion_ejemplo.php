<?php

// RENOMBRAR EL ARCHIVO A 'conexion.php' PARA QUE FUNCIONE

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";      
$password = "";   
$database = "entregable_tareas_bd";

try {
    // Creamos la conexión
    $conexion = new mysqli($host, $user, $password, $database);
    
    // utf8mb4 es el estándar real (soporta todos los caracteres y emojis, utf8 no)
    $conexion->set_charset("utf8mb4");
    
} catch (mysqli_sql_exception $e) {
    // Si falla, registramos el error interno pero no se lo enseñamos al usuario por seguridad
    error_log($e->getMessage());
    die("Error crítico de sistema. Por favor, inténtelo más tarde.");
}
?>