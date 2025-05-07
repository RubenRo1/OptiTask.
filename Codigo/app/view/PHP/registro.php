<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
</head>

<body>

    <?php
    require_once(__DIR__ . '/../../../rutas.php');
    require_once(CONTROLLER . 'UsuarioController.php');
    require_once(MODEL . 'Usuario.php');

    session_start();
    $usuarioController = new UsuarioController();
    $usuarios = $usuarioController->getAllUsers();
    ?>
    <?php include "../Generales/header.php" ?>
    
    <div class="content">

        <h2>Registro</h2>

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
                <p>Repetir Contraseña: </p>
                <input type="password" name="repetir_contraseña" required>
            </div>

            <input type="submit" value="Crear Cuenta">
        </form>

        <p id="pregunta">¿Ya tienes cuenta?</p>
        <a href="../PHP/login.php">Iniciar Sesión</a>
        <a href="../PHP/inicio.php">Volver al Inicio</a>

        <?php
        // Crear usuario
        if (isset($_POST['formCreate']) && $_POST['formCreate'] == 'crearUsuario') {
            if (isset($_POST["correo"], $_POST["contraseña"], $_POST["repetir_contraseña"], $_POST["nombre_usuario"])) {

                $nombreUsuario = htmlspecialchars($_POST["nombre_usuario"]);
                $correo = htmlspecialchars($_POST["correo"]);
                $contraseña = $_POST["contraseña"];
                $repetirContraseña = $_POST["repetir_contraseña"];

                // Validaciones
                if ($contraseña !== $repetirContraseña) {
                    echo "<p>Las contraseñas no coinciden</p>";
                    exit();
                }

                if (strlen($contraseña) < 8) {
                    echo "<p>La contraseña debe tener al menos 8 caracteres</p>";
                    exit();
                }

                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    echo "<p>Correo electrónico no válido.</p>";
                    exit();
                }

                // Verificar si el correo ya existe
                if ($usuarioController->emailExiste($correo)) {
                    echo "<p>El correo ya está registrado.</p>";
                    exit();
                }

                // Crear el usuario si no existe
                if ($usuarioController->crearUsuario($nombreUsuario, $correo, $contraseña)) {
//Que lleve al inicio
                    header("Location: login.php");
                    exit();
                } else {
                    echo "<p>El nombre de usuario ya existe.</p>";
                    exit();
                }
            } else {
                echo "<p>Completa todos los campos.</p>";
                exit();
            }
        }
        ?>


    </div>
    <?php

    // $usuarioController = new UsuarioController();
    // $usuarios = $usuarioController->getAllUsers();



    ?>

</body>

</html>