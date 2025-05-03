<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {

    $usuario = null;
    echo "No estás autenticado. Inicia sesión para continuar.";
    exit;
} else {

    $usuarioController = new UsuarioController();
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $usuario = $usuarioController->getUserByName($nombre_usuario);
}


if (isset($_POST['cerrar_sesion'])) {
    $contraseña_actual = $_POST['contraseña_actual'];
    cerrarSesion();
}

if (isset($_POST['modificar_cuenta'])) {
   
    header("Location: cuenta.php");
    exit();
}



function cerrarSesion()
{
    session_destroy();
    header("Location: login.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>

<body>

    <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h1>

    <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario->getEmail()); ?></p>
    <p><strong>Fecha de registro:</strong> <?php echo htmlspecialchars($usuario->getFechaRegistro()); ?></p>


    <!-- Enlace para cerrar sesión -->
    <form method="POST" action="">
        <button type="submit" name="cerrar_sesion">Cerrar Sesión</button>
        <button type="submit" name="modificar_cuenta">Cuenta</button>
    </form>

</body>

</html>