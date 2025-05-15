<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                        echo "<p style = color:red;>Contraseña incorrecta.</p>";
                    }
                } else {
                    echo "<p style = color:red;>El usuario no existe</p>";
                }
            }

            ?>
        </div>
    </div>
</body>
<style>
    .general {
        margin: 8% auto;
        background: #2B2B2B;
        padding: 40px 50px;
        display: flex;
        flex-direction: column;
        border: solid 1px #414548;
        border-radius: 12px;
        width: 320px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .general:hover {
        transform: translateY(-2px);
        box-shadow: 0px 6px 24px rgba(0, 0, 0, 0.25);
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #414548;
        border-radius: 6px;
        background-color: #1E1E1E;
        color: white;
        margin: 8px 0 15px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: #3A7BFF;
    }

    button {
        background-color: #3A7BFF;
        color: white;
        border: none;
        width: 100%;
        padding: 12px 25px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 20px;
        font-weight: bold;
        font-size: 15px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
        background-color: #2A6BEE;
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }

    #pregunta {
        font-size: 13px;
        color: #A0A0A0;
        margin-top: 5px;
        text-decoration: none;
        display: inline-block;
        transition: color 0.3s ease;
    }

    #pregunta:hover {
        color: #3A7BFF;
    }

    h2 {
        margin-bottom: 5px;
        font-size: 28px;
        font-weight: 600;
    }

    .registro {
        font-size: 14px;
        color: #A0A0A0;
        margin-bottom: 25px;
    }

    a {
        color: #3A7BFF;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #2A6BEE;
    }

    p {
        margin: 0;
        font-size: 14px;
        color: #E0E0E0;
    }
</style>

</html>