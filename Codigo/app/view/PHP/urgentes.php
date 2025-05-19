<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(MODEL . 'Usuario.php');
require_once(MODEL . 'Tarea.php');

session_start();

if ((isset($_SESSION['nombre_usuario']))) {



    $tareaController = new TareaController();
    $usuarioController = new UsuarioController();
    $nombre_usuario = $_SESSION['nombre_usuario'];

    $usuario = $usuarioController->getUserByName($nombre_usuario);

    $id_usuario = $usuario->getIdUsuario();

    $tareas = $tareaController->getTareasByUser($id_usuario);  

} else {

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas Urgentes</title>
    <link rel="stylesheet" href="../CSS/urgentes.css">
</head>

<body>
    <?php
    include "../Generales/header.php";
    include "../Generales/sidebar.php";
    ?>

    <!-- Mostrar la id de las tareas del usuario -->
    <div class="content">
        <div class="general1">
            <h1>Tareas Urgentes</h1>
            <?php
            // Obtener todas las tareas urgentes (incluyendo las del día actual)
            $tareas_urgentes = $tareaController->getTareasUrgentes($id_usuario, 3);

            if (empty($tareas_urgentes)) {
                echo "<p>No hay tareas urgentes pendientes. ¡Buen trabajo!</p>";
            } else {
                foreach ($tareas_urgentes as $t) {
                    echo '<div class="tarea-urgente" onclick="window.location.href=\'detalleTarea.php?id=' . $t->getIdTarea() . '\'">';
                    echo '<strong>' . htmlspecialchars($t->getTitulo()) . '</strong>';
                    echo '<div class="prioridad">Prioridad: <span class="prioridad_' . htmlspecialchars($t->getPrioridad()) . '">' . htmlspecialchars($t->getPrioridad()) . '</span></div>';
                    echo '<div class="countdown" data-fechalimite="' . htmlspecialchars($t->getFechaLimite()) . '"></div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdownElements = document.querySelectorAll(".countdown");

            countdownElements.forEach(element => {
                const fechaLimiteTexto = element.getAttribute("data-fechalimite").replace(" ", "T");
                const fechaLimite = new Date(fechaLimiteTexto).getTime();

                function actualizarCountdown() {
                    const ahora = new Date().getTime();
                    const tiempoRestante = fechaLimite - ahora;

                    if (tiempoRestante <= 0) {
                        element.innerHTML = "¡Tarea expirada!";
                        element.style.color = "red";
                        element.style.fontWeight = "bold";
                        return;
                    }

                    const dias = Math.floor(tiempoRestante / (1000 * 60 * 60 * 24));
                    const horas = Math.floor((tiempoRestante % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
                    const segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);

                    element.innerHTML = `Tiempo restante: ${dias}d ${horas}h ${minutos}m ${segundos}s`;

                    if (dias < 1) {
                        element.style.color = "#FF6B6B"; // Rojo si es el mismo día
                    } else if (dias < 3) {
                        element.style.color = "#FFA500"; // Naranja para 1-2 días
                    } else {
                        element.style.color = "#4CAF50"; // Verde para 3+ días
                    }
                }

                const intervalo = setInterval(actualizarCountdown, 1000);
                actualizarCountdown();
            });
        });
    </script>

</body>

</html>