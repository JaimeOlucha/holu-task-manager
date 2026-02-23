<?php
session_start();

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';
$usuario_id = $_SESSION['usuario_id'];


// $_POST sin isset(). Al entrar por primera vez, $_POST['campo_falso'] no existe;
// Esto provocará un ERROR en pantalla;
// $vulnerabilidad = $_POST['campo_falso'];
// - - - - - - - - - - - -

// Valores por defecto para CREAR;
$id = $_GET['id'] ?? null;
$tarea = [
    'titulo' => '',
    'descripcion' => '',
    'fecha' => date('Y-m-d'),
    'estado' => 'pendiente'
];
$accion = 'crear';

// Si hay un ID en la URL, cambiamos a EDITAR;
if ($id) {
    $accion = 'editar';

    // Buscamos la tarea en la base de datos;
    $sql = "SELECT * FROM entregable_tareas WHERE id = $id AND usuario_id = $usuario_id";
    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $tarea = $resultado->fetch_assoc();
    } else {
        // Si el ID no existe, lo devolvemos al inicio;
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $id ? 'Editar' : 'Nueva'; ?> Tarea
    </title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/webp" href="/img/logo-holu-favicon.webp">
    <script src="script.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>
                <?php echo $id ? 'Editar Tarea' : 'Crear Nueva Tarea'; ?>
            </h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="accion" value="<?php echo $accion; ?>">
                <?php if ($id): ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo $tarea['titulo']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion"
                        rows="4"><?php echo $tarea['descripcion']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" value="<?php echo $tarea['fecha']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado">
                        <option value="pendiente" <?php echo ($tarea['estado'] == 'pendiente') ? 'selected' : ''; ?>>
                            Pendiente</option>
                        <option value="en_progreso" <?php echo ($tarea['estado'] == 'en_progreso') ? 'selected' : ''; ?>>
                            En Progreso</option>
                        <option value="completada" <?php echo ($tarea['estado'] == 'completada') ? 'selected' : ''; ?>>
                            Completada</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                    <a href="index.php" class="btn btn-warning">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>