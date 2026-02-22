<?php
session_start();

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
$usuario_id = $_SESSION['usuario_id'];

switch ($accion) {
    case 'crear':
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $estado = $_POST['estado'] ?? 'pendiente';

        $stmt = $conexion->prepare("INSERT INTO entregable_tareas (titulo, descripcion, fecha, estado, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $titulo, $descripcion, $fecha, $estado, $usuario_id);

        if ($stmt->execute()) {
            $_SESSION['mensaje_exito'] = "¡Tarea creada correctamente!";
        } else {
            $_SESSION['mensaje_error'] = "ERROR al crear la tarea.";
        }
        $stmt->close();
        break;

    case 'editar':
        $id = $_POST['id'] ?? 0;
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $estado = $_POST['estado'] ?? 'pendiente';

        $stmt = $conexion->prepare("UPDATE entregable_tareas SET titulo = ?, descripcion = ?, fecha = ?, estado = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ssssii", $titulo, $descripcion, $fecha, $estado, $id, $usuario_id);

        if ($stmt->execute()) {
            $_SESSION['mensaje_exito'] = "¡Tarea actualizada correctamente!";
        } else {
            $_SESSION['mensaje_error'] = "ERROR al actualizar la tarea.";
        }
        $stmt->close();
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;

        $stmt = $conexion->prepare("DELETE FROM entregable_tareas WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $usuario_id);

        if ($stmt->execute()) {
            $_SESSION['mensaje_exito'] = "¡Tarea eliminada correctamente!";
        } else {
            $_SESSION['mensaje_error'] = "Error al eliminar la tarea.";
        }
        $stmt->close();
        break;

    default:
        $_SESSION['mensaje_error'] = "Acción no válida.";
        break;
}

// Un solo punto de salida para todo el archivo
header("Location: index.php");
exit();
?>