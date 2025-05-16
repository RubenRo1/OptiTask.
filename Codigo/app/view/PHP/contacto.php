<?php

require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $asunto = htmlspecialchars($_POST["asunto"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    // Aquí podrías guardar en base de datos o enviar un correo
    $confirmacion = "Gracias por contactarnos, $nombre. Te responderemos pronto.";
}
if (!isset($_SESSION['nombre_usuario'])) {

    $usuario = null;
    $nombre_usuario = null;
} else {

    $usuarioController = new UsuarioController();
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $usuario = $usuarioController->getUserByName($nombre_usuario);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <?php include "../Generales/header.php" ?>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
            /* overflow-x: hidden; */
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
           
        }

        .contact-form {

            background-color: #1E1E1E;
            padding: 25px 50px;
            border: solid 1px #414548;
            border-radius: 10px;
            max-width: 700px;
            margin: 8% auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 20px;
            color: #EEE;
            font-family: 'Comfortaa', sans-serif;

        }



        .contact-form h2 {
            margin-bottom: 20px;
            text-align: center;

        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 6px;
            background-color: #2B2B2B;
            resize: none;

        }

        .contact-form button {
            background-color: #3A7BFF;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
        }

        .contact-form button:hover {
            background-color: #005ce6;
        }

        .mensaje-confirmacion {
            color: green;
            font-weight: bold;
        }

        .mensaje-fallo {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="contact-form">

        <!-- Posible añadido -->
        <!-- <p>
            Contact Support

            Need assistance? Submit a request below and we will get to work!

            If you think the problem is a bug and you don't find it in system status or known issues, raise it here and we will collect full details to report it to development.

            If you are an Atlassian certified partner, please confirm you are logged in using the account associated with your business email address (your company domain, NOT Atlassian domain, your customer’s domain, or personal email domain).
        </p>  -->

        <h2>Contáctanos</h2>

        <?php if (isset($_GET['estado'])): ?>

            <?php if ($_GET['estado'] === 'ok'): ?>

                <p class="mensaje-confirmacion">¡Envío exitoso! Gracias por contactarnos. Te responderemos pronto.</p>

            <?php elseif ($_GET['estado'] === 'error'): ?>

                <p class="mensaje-fallo">Lo sentimos, algo ha fallado. Por favor, inténtalo de nuevo más tarde.</p>
            <?php endif; ?>

        <?php endif; ?>


        <form method="POST" action="enviarCorreo.php">

            <input type="text" name="nombre" placeholder="Nombre..." required>
            <input type="email" name="email" placeholder="Email..." required>
            <input type="text" name="asunto" placeholder="Asunto..." required>
            <textarea name="mensaje" placeholder="Mensaje..." rows="5" required></textarea>
            <button type="submit">Enviar</button>

        </form>

    </div>

</body>

</html>