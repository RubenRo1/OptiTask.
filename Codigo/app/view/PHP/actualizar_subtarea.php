<?php

// Incluir archivos necesarios
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'SubtareaController.php');
require_once(MODEL . 'Subtarea.php');

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php"); // Redirigir si no está logueado
    exit();
}

// Verificar si se recibió el ID de la subtarea y el estado del checkbox
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_subtarea'])) {
    $subtareaId = (int) $_POST['id_subtarea'];
    $completado = isset($_POST['completado']) ? 1 : 0;  // Si está marcado, completado = 1, sino 0

    // Verificar si la subtarea existe
    $subtareaController = new SubtareaController();
    $subtarea = $subtareaController->obtenerSubtareaPorId($subtareaId);

    if ($subtarea) {
        // Actualizar el estado de completado
        $subtareaController->actualizarSubtarea($subtareaId, $subtarea->getDescripcion(), $completado);

        // Redirigir de vuelta a la página de tarea
        header("Location: detalleTarea.php?id=" . $subtarea->getIdTarea());
        exit();
    } else {
        // Si no existe la subtarea, redirigir con un mensaje de error
        header("Location: detalleTarea.php?id=" . $_POST['id_tarea'] . "&error=Subtarea no encontrada.");
        exit();
    }
} else {
    // Redirigir si no se ha enviado el formulario correctamente
    header("Location: detalleTarea.php?id=" . $_POST['id_tarea'] . "&error=Datos inválidos.");
    exit();
}
