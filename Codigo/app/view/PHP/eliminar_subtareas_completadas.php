<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'SubtareaController.php');
require_once(MODEL . 'Subtarea.php');

session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tarea'])) {
    $tareaId = (int) $_POST['id_tarea'];
    $subtareaController = new SubtareaController();
    $subtareas = $subtareaController->obtenerSubtareasPorTarea($tareaId);

    foreach ($subtareas as $subtarea) {
        if ($subtarea->getCompletado()) {
            $subtareaController->eliminarSubtarea($subtarea->getIdSubtarea());
        }
    }

    header("Location: detalleTarea.php?id=" . $tareaId . "&mensaje=Subtareas completadas eliminadas.");
    exit();
} else {
    header("Location: detalleTarea.php?id=0&error=Datos inválidos.");
    exit();
}
