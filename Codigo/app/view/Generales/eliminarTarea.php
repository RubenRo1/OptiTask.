<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'TareaController.php');

// Verificar si el id de la tarea fue enviado
if (isset($_POST['id_tarea'])) {
    $idTarea = $_POST['id_tarea'];

    // Instanciar el controlador
    $tareaController = new TareaController();
    
    // Eliminar la tarea
    $tareaController->eliminarTarea($idTarea);

} 
?>
