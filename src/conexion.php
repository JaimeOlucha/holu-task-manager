<?php
// conexion.php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Busca la clave en el servidor. Si no existe, usa la de XAMPP local.
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$database = getenv('DB_NAME') ?: "entregable_tareas_bd";

try {
    $conexion = new mysqli($host, $user, $password, $database);
    $conexion->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    die("Error crítico de sistema. Por favor, inténtelo más tarde.");
}
?>