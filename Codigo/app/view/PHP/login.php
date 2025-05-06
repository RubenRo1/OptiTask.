<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    require_once(__DIR__ . '/../../../rutas.php');
    require_once(CONTROLLER . 'UsuarioController.php');
    require_once(MODEL . 'Usuario.php');

    session_start();

    if (isset($_SESSION['nombre_usuario'])) {
        header("Location: inicio.php"); // al logearse redirige a inicio
        exit();
    }
    include "../Generales/header.php";
    ?>


    <div class="content">
        <h2>Inicie Sesión</h2>

        <form action="login.php" method="POST">
            <p>Nombre de usuario: </p>

            <input type="text" name="nombre_usuario" required>

            <p>Contraseña:</p>

            <input type="password" name="contraseña" required>

            <input type="submit" value="Iniciar sesión">


        </form>


        <p id="pregunta">¿No tienes cuenta?</p>
        <a href="../PHP/registro.php">Registrarse</a>
        <a href="../PHP/inicio.php">Volver al Inicio</a>
        <?php

        $usuarioController = new UsuarioController();

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
                    echo "<p>Contraseña incorrecta.</p>";
                }
            } else {
                echo "<p>El usuario no existe</p>";
            }
        }

        ?>

    </div>
</body>

</html>