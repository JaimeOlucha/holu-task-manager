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

    // EARLY RETURN 1: Vacíos
    if (empty($usuario) || empty($password)) {
        $_SESSION['mensaje_error'] = "Por favor, rellena todos los campos.";
        header("Location: registro.php");
        exit();
    }

    // Comprobamos si el usuario ya existe (usamos COUNT para mayor rendimiento)
    $stmt_check = $conexion->prepare("SELECT COUNT(id) FROM usuarios WHERE usuario = ?");
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $stmt_check->bind_result($existe);
    $stmt_check->fetch();
    $stmt_check->close();

    // EARLY RETURN 2: Usuario duplicado
    if ($existe > 0) {
        $_SESSION['mensaje_error'] = "El usuario ya está en uso. Elige otro...";
        header("Location: registro.php");
        exit();
    }

    // FLUJO FELIZ: Registrar
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt_sql = $conexion->prepare("INSERT INTO usuarios (usuario, password_hash) VALUES (?, ?)");
    $stmt_sql->bind_param("ss", $usuario, $password_hash);

    if ($stmt_sql->execute()) {
        $_SESSION['usuario_logueado'] = true;
        $_SESSION['nombre_usuario'] = $usuario;
        $_SESSION['usuario_id'] = $conexion->insert_id;
        $_SESSION['mostrar_bienvenida'] = true;

        $stmt_sql->close();
        header("Location: index.php");
        exit();
    } else {
        $stmt_sql->close();
        $_SESSION['mensaje_error'] = "ERROR al registrar el usuario.";
        header("Location: registro.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gestión de Tareas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/webp" href="img/logo-holu-favicon.webp">
    <script src="script.js" defer></script>
</head>

<body>
    <div id="preloader">
        <div class="panel panel-left">
            <img src="img/logo-holu-open.svg" alt="<" class="bracket-img bracket-img-open">
        </div>

        <div class="panel panel-right">
            <img src="img/logo-holu-close.svg" alt="/>" class="bracket-img bracket-img-close">
        </div>
    </div>

    <div class="auth-layout">
        <div class="auth-brand">
            <span>Gestor de Tareas de</span>
            <img src="img/logo-holu.svg" alt="holu logo">
        </div>

        <div class="login-card">
            <h2>Crear Cuenta</h2>

            <?php if (isset($_SESSION['mensaje_error'])): ?>
                    <div class="alert alert-error">
                        <?php
                        echo $_SESSION['mensaje_error'];
                        unset($_SESSION['mensaje_error']);
                        ?>
                    </div>
            <?php endif; ?>

            <form method="POST" action="registro.php">
                <div class="form-group">
                    <label for="usuario">Nuevo Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Registrarse</button>
            </form>

            <div class="enlace-pie">
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</body>

</html>