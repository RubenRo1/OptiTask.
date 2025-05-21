<?php

require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');

session_start();

$usuarioController = new UsuarioController();

if (isset($_SESSION['nombre_usuario'])) {
    header("Location: inicio.php"); // al logearse redirige a inicio
    exit();
}
// Procesar formulario login
if (isset($_POST['nombre_usuario'], $_POST['contraseña'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];

    $usuario = $usuarioController->getUserByName($nombre_usuario);

    if ($usuario == true) {
        if ($contraseña == $usuario->getContraseña()) {
            $_SESSION['nombre_usuario'] = $usuario->getNombre();
            header("Location: inicio.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El usuario no existe";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body>

    <?php include "../Generales/header.php"; ?>

    <div class="content">
        <div class="general">
            <h2>Iniciar sesión</h2>
            <p class="registro">¿Es tu primera vez? <a href="../PHP/registro.php">Regístrate</a></p>

            <form action="login.php" method="POST">
                <p>Usuario * </p>
                <input type="text" name="nombre_usuario" placeholder="Usuario" required>

                <p>Contraseña *</p>
                <input type="password" name="contraseña" placeholder="Contraseña" required>
                <button type="submit">Iniciar sesión</button>
            </form>

            <?php
            if (!empty($error)) {
                echo "<p style='color:red;'>$error</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>