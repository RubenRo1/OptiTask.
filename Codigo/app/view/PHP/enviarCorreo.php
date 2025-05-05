<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('C:\xampp\htdocs\OptiTask\phpmailer/PHPMailer.php');
require_once('C:\xampp\htdocs\OptiTask\phpmailer/Exception.php');
require_once('C:\xampp\htdocs\OptiTask\phpmailer/SMTP.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger los datos del formulario
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $asunto = htmlspecialchars($_POST["asunto"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rubenrodriguezcatrufo@gmail.com'; // Tu correo Gmail
        $mail->Password = 'tpim yynb ylmr mzds'; // La clave de aplicación que generaste
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente (tu correo)
        $mail->setFrom('rubenrodriguezcatrufo@gmail.com', 'OptiTask'); // Aquí usas tu correo

        // Destinatario (tu correo donde recibirás los mensajes)
        $mail->addAddress('rubenrodriguezcatrufo@gmail.com'); // Tu correo para recibir el mensaje

        // Contenido del mensaje
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = "<strong>Nombre:</strong> $nombre<br><strong>Email:</strong> $email<br><strong>Mensaje:</strong><br>$mensaje";

        // Enviar correo
        if ($mail->send()) {
            
            header("Location: ../PHP/contacto.php?enviado=1"); // Redirigir a la página de contacto con un mensaje de confirmación
        } else {
            header("Location: ../PHP/contacto.php?enviado=0"); // Redirigir a la página de contacto con un mensaje de confirmación
            echo 'Hubo un error al enviar el mensaje: ' . $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        header("Location: ../PHP/contacto.php?enviado=0"); // Redirigir a la página de contacto con un mensaje de confirmación
        echo "Error en el envío del mensaje: {$mail->ErrorInfo}";
    }
}
?>
