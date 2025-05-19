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
    <link rel="stylesheet" href="../CSS/contacto.css">
</head>

<body>
    <?php include "../Generales/header.php" ?>
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