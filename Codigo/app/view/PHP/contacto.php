<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $asunto = htmlspecialchars($_POST["asunto"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    // Aquí podrías guardar en base de datos o enviar un correo
    $confirmacion = "Gracias por contactarnos, $nombre. Te responderemos pronto.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <?php include "../Generales/header.php" ?>
    <style>
        .contact-form {

            background: white;
            padding: 25px 50px;
            border-radius: 10px;
            max-width: 600px;
            margin: 3% auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
            border: 1px solid #ccc;
            border-radius: 6px;
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
        .mensaje-confirmacion {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="contact-form">

        <h2>Contáctanos</h2>

        <?php if (isset($_GET['enviado'])): ?>

            <p class="mensaje-confirmacion">¡Envio exitoso! Gracias por contactarnos. Te responderemos pronto.</p>
        
        <?php else: ?>
            
            <p class="mensaje-fallo">Lo sentimos, algo ha fallado. Por favor, inténtalo de nuevo más tarde.</p>

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