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

    $tareas = $tareaController->getTareasByUser($id_usuario);  // Método que obtiene las tareas de la base de datos
    // $tareasCompartidas = $compartidasController->obtenerCompartidasPorUsuarioDestino($id_usuario);

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas Urgentes</title>
</head>

<body>
    <?php 
    include "../Generales/header.php" 
    ?>

    <!-- Mostrar la id de las tareas del usuario -->
    <div class="content">
        <h1>Tareas Urgentes</h1>
        <?php
        // Prueba directa de getTareasUrgentes
        $tareas_urgentes = $tareaController->getTareasUrgentes($id_usuario, 3); // Cambia el 3 por el número de días que consideres urgente

        echo "<h2>Prueba: Tareas Urgentes</h2>";
        if (empty($tareas_urgentes)) {
            echo "<p>No hay tareas urgentes.</p>";
        } else {
            foreach ($tareas_urgentes as $t) {
                echo "<div>";
                echo "<strong>" . htmlspecialchars($t->getTitulo()) . "</strong><br>";
                echo "Prioridad: " . htmlspecialchars($t->getPrioridad()) . "<br>";
                echo "Fecha límite: " . htmlspecialchars($t->getFechaLimite()) . "<br>";
                echo "</div><hr>";
            }
        }


        ?>
</body>

</html>