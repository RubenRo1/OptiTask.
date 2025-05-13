<?php

// Incluir archivos necesarios
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'SubtareaController.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(MODEL . 'Subtarea.php');
require_once(MODEL . 'Tarea.php');

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php"); // Redirigir si no está logueado
    exit();
}

// Verificar si se recibió la descripción y el ID de la tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descripcion']) && isset($_POST['id_tarea']) && !empty($_POST['descripcion']) && !empty($_POST['id_tarea'])) {
    // Obtener datos del formulario
    $descripcion = trim($_POST['descripcion']);
    $tareaId = (int) $_POST['id_tarea'];

    // Validar que la tarea exista
    $tareaController = new TareaController();
    $tarea = $tareaController->getTareaById($tareaId);

    if ($tarea) {
        // Crear la nueva subtarea
        $subtareaController = new SubtareaController();
        $subtarea = $subtareaController->crearSubtarea($descripcion, $tareaId);

        // Redirigir de vuelta a la página de tarea con un mensaje de éxito
        header("Location: detalleTarea.php?id=" . $tareaId . "&mensaje=Subtarea agregada exitosamente.");
        exit();
    } else {
        // Si no existe la tarea, redirigir con un mensaje de error
        header("Location: detalleTarea.php?id=" . $tareaId . "&error=Tarea no encontrada.");
        exit();
    }
} else {
    // Redirigir si no se ha enviado el formulario correctamente
    header("Location: detalleTarea.php?id=" . $_POST['id_tarea'] . "&error=Datos inválidos.");
    exit();
}

?>
