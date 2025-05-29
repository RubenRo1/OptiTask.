<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');

session_start();
$usuarioController = new UsuarioController();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['formCreate']) && $_POST['formCreate'] == 'crearUsuario') {
    if (isset($_POST["correo"], $_POST["contraseña"], $_POST["repetir_contraseña"], $_POST["nombre_usuario"])) {

        $nombreUsuario = htmlspecialchars($_POST["nombre_usuario"]);
        $correo = htmlspecialchars($_POST["correo"]);
        $contraseña = $_POST["contraseña"];
        $repetirContraseña = $_POST["repetir_contraseña"];

        // Validaciones
        if ($contraseña !== $repetirContraseña) {
            $error = "Las contraseñas no coinciden";
        } elseif (strlen($contraseña) < 8) {
            $error = "La contraseña debe tener al menos 8 caracteres";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = "Correo electrónico no válido";
        } elseif ($usuarioController->emailExiste($correo)) {
            $error = "El correo ya está registrado";
        } elseif (strlen($nombreUsuario) < 3) {
            $error = "El nombre de usuario debe tener al menos 3 caracteres";
        } elseif (strlen($nombreUsuario) > 20) {
            $error = "El nombre de usuario no puede tener más de 20 caracteres";
        } else {
            if ($usuarioController->crearUsuario($nombreUsuario, $correo, $contraseña)) {
                $_SESSION['nombre_usuario'] = $nombreUsuario;
                header("Location: inicio.php");
                exit();
            } else {
                $error = "El nombre de usuario ya existe";
            }
        }
    } else {
        $error = "Completa todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../CSS/registro.css">k
</head>

<body>
    <?php include "../Generales/header.php"; ?>
    <div class="content">
        <div class="container">
            <h2>Registro</h2>

            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="registro.php" method="POST">
                <input type="hidden" name="formCreate" value="crearUsuario">

                <div class="form-group">
                    <p>Correo:</p>
                    <input type="email" name="correo" required>
                </div>

                <div class="form-group">
                    <p>Nombre de usuario:</p>
                    <input type="text" name="nombre_usuario" required>
                </div>

                <div class="form-group">
                    <p>Contraseña:</p>
                    <input type="password" name="contraseña" required>
                </div>

                <div class="form-group">
                    <p>Repetir Contraseña:</p>
                    <input type="password" name="repetir_contraseña" required>
                </div>

                <input type="submit" value="Crear Cuenta">
            </form>

            <p id="pregunta">¿Ya tienes cuenta?</p>
            <a class="enlace" href="../PHP/login.php">Iniciar Sesión</a>
        </div>
    </div>
</body>

</html>