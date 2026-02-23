<?php
session_start();

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

// Recuperamos el ID del usuario de la sesión
$usuario_id = $_SESSION['usuario_id'];

// Paginación Básica
$tareas_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $tareas_por_pagina;

// Contamos las tareas del usuario logeado
$sql_total = "SELECT COUNT(*) as total FROM entregable_tareas WHERE usuario_id = ?";
$stmt_total = $conexion->prepare($sql_total);
$stmt_total->bind_param("i", $usuario_id);
$stmt_total->execute();
$resultado_total = $stmt_total->get_result();
$fila_total = $resultado_total->fetch_assoc();
$total_paginas = ceil($fila_total['total'] / $tareas_por_pagina);
$stmt_total->close();

// Traemos las tareas del usuario logeado;
$sql = "SELECT * FROM entregable_tareas WHERE usuario_id = ? LIMIT ? OFFSET ?";
$stmt_tareas = $conexion->prepare($sql);
$stmt_tareas->bind_param("iii", $usuario_id, $tareas_por_pagina, $offset);
$stmt_tareas->execute();
$resultado = $stmt_tareas->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/webp" href="/img/logo-holu-favicon.webp">
    <script src="script.js" defer></script>
</head>

<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo-container">
                <img src="/img/logo-holu.webp" alt="holu logo" class="brand-logo">
            </div>

            <div class="user-menu">
                <span class="user-greeting">Hola, <strong>
                        <?php echo htmlspecialchars($_SESSION['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>
                    </strong></span>
                <a href="logout.php" class="btn btn-danger btn-sm">Salir</a>
            </div>
        </nav>
    </header>

    <main class="container">

        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['mensaje_exito'];
                unset($_SESSION['mensaje_exito']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['mensaje_error'];
                unset($_SESSION['mensaje_error']); ?>
            </div>
        <?php endif; ?>

        <?php if ($resultado->num_rows > 0): ?>
            <div class="acciones-header">
                <h1 class="titulo-seccion">Tablero de Tareas</h1>
                <a href="formulario.php" class="btn btn-primary">Nueva Tarea</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nº Tarea</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $numero_fila = $offset + 1; ?>
                        <?php while ($tarea = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php echo $numero_fila; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($tarea['titulo'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td>
                                    <div class="text-truncate">
                                        <?php echo htmlspecialchars($tarea['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <button class="btn-link btn-ver-mas"
                                        data-titulo="<?php echo htmlspecialchars($tarea['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-desc="<?php echo htmlspecialchars($tarea['descripcion'], ENT_QUOTES, 'UTF-8'); ?>">
                                        Ver más
                                    </button>
                                </td>
                                <td>
                                    <?php echo $tarea['fecha']; ?>
                                </td>
                                <td>
                                    <span class="badge-estado badge-<?php echo $tarea['estado']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $tarea['estado'])); ?>
                                    </span>
                                </td>
                                <td class="acciones">
                                    <a href="formulario.php?id=<?php echo $tarea['id']; ?>" class="btn btn-warning">Editar</a>
                                    <a href="procesar.php?accion=eliminar&id=<?php echo $tarea['id']; ?>" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar esta tarea?');">Eliminar</a>
                                </td>
                            </tr>
                            <?php $numero_fila++; ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_paginas > 1): ?>
                <div class="paginacion">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <a href="index.php?pagina=<?php echo $i; ?>" class="<?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <h3 class="empty-state-title">No hay tareas registradas.</h3>
                <p class="empty-state-text">¡Tu tablero está impecable! Empieza a organizar tu día creando tu primera tarea.
                </p>
                <a href="formulario.php" class="btn btn-primary empty-state-btn">Crear mi primera tarea</a>
            </div>
        <?php endif; ?>

        <div class="danger-zone">
            <p class="danger-zone-text">¿Ya no necesitas nuestro gestor?</p>
            <button id="btnBorrarCuenta" class="btn btn-danger btn-delete-account">
                Eliminar mi cuenta
            </button>
        </div>

    </main>

    <div class="modal-overlay" id="descModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitleText">Detalle</h3>
                <button class="modal-close" aria-label="Cerrar">&times;</button>
            </div>
            <div class="modal-body" id="modalBodyText"></div>
        </div>
    </div>

    <?php if (isset($_SESSION['mostrar_bienvenida'])): ?>
        <div class="modal-overlay active" id="welcomeModal">
            <div class="modal-content text-center">
                <h2 class="modal-title-spaced">¡Bienvenido,
                    <?php echo htmlspecialchars($_SESSION['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>!
                </h2>
                <p class="modal-text">Al gestor de tareas de</p>
                <img src="/img/logo-holu.webp" alt="holu logo" class="modal-logo empty-state-img">
                <button class="btn btn-primary btn-full" id="btnCerrarWelcome">¡Empezar a organizar!</button>
            </div>
        </div>
        <?php unset($_SESSION['mostrar_bienvenida']); ?>
    <?php endif; ?>
</body>

</html>