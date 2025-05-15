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
                <a href="inicio.php" id="pregunta">¿Olvidaste tu contraseña?</a>
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
        padding: 25px 50px;
        display: flex;
        flex-direction: column;
        border: solid 1px #414548;
        border-radius: 10px;
        width: 15%;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        align-items: center;

    }

    input[type="text"], input[type="password"] {

        width: 100%;
        padding: 10px;
        border: 1px solid black;

        
    }
    
    button {

        background-color: #3A7BFF;
        color: white;
        border: none;
        width: 100%;
        padding: 10px 25px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 20px;
    
    }
    #pregunta{

        font-size: 13px;

    }

    h2 {

        margin-bottom: 0;
        font-size: 30px;
    }

    .registro {

        font-size: 14px;


    }

    a {
        color: inherit;
    }

    a:visited {
        color: inherit;
  
    }

    a:hover {
        
        color: #ccc;
    
       
    }
</style>

</html>