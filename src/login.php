<?php
session_start();

if (isset($_SESSION['usuario_logueado'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    // EARLY RETURN 1: Campos vacíos
    if (empty($usuario) || empty($password)) {
        $_SESSION['mensaje_error'] = "Por favor, introduce usuario y contraseña.";
        header("Location: login.php");
        exit();
    }

    $stmt = $conexion->prepare("SELECT id, usuario, password_hash FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();

    // EARLY RETURN 2: Usuario no existe
    if ($resultado->num_rows !== 1) {
        $_SESSION['mensaje_error'] = "Usuario o contraseña incorrectos.";
        header("Location: login.php");
        exit();
    }

    $fila = $resultado->fetch_assoc();

    // EARLY RETURN 3: Contraseña incorrecta
    if (!password_verify($password, $fila['password_hash'])) {
        $_SESSION['mensaje_error'] = "Usuario o contraseña incorrectos.";
        header("Location: login.php");
        exit();
    }

    // FLUJO FELIZ: Todo correcto
    $_SESSION['usuario_logueado'] = true;
    $_SESSION['nombre_usuario'] = $fila['usuario'];
    $_SESSION['usuario_id'] = $fila['id'];
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestión de Tareas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/webp" href="../img/logo-holu-favicon.webp">
    <script src="script.js" defer></script>
</head>

<body>
    <div id="preloader">
        <div class="panel panel-left">
            <img src="../img/logo-holu-open.svg" alt="<" class="bracket-img bracket-img-open">
        </div>

        <div class="panel panel-right">
            <img src="../img/logo-holu-close.svg" alt="/>" class="bracket-img bracket-img-close">
        </div>
    </div>
    <div class="auth-layout">
        <div class="auth-brand">
            <span>Gestor de Tareas de</span>
            <img src="../img/logo-holu.svg" alt="holu logo">
        </div>

        <div class="login-card">
            <h2>Iniciar Sesión</h2>

            <?php if (isset($_SESSION['mensaje_error'])): ?>
                <div class="alert alert-error">
                    <?php
                    echo $_SESSION['mensaje_error'];
                    unset($_SESSION['mensaje_error']);
                    ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>

            <div class="enlace-pie">
                <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] === 'true'): ?>
        <div class="modal-overlay active" id="goodbyeModal">
            <div class="modal-content text-center">
                <h2 class="modal-title-spaced">¡Hasta pronto!</h2>
                <p class="modal-text-spaced">
                    Gracias por usar el gestor de tareas de <img src="../img/logo-holu.svg" alt="holu logo"
                        class="modal-logo empty-state-img"> ¡Vuelve cuando
                    quieras!</p>
                <button class="btn btn-primary btn-full" id="btnCerrarGoodbye">Cerrar</button>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>