<?php
// Incluir los archivos necesarios
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'ComentarioController.php');
require_once(MODEL . 'Comentario.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');

session_start();

// Verificamos que el usuario esté autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header('Location: login.php');
    exit;
}

// Comprobamos que el comentario y el id de la tarea existan
if (isset($_POST['comentario']) && isset($_POST['id_tarea']) && !empty($_POST['comentario'])) {
    $comentario = $_POST['comentario'];
    $tareaId = $_POST['id_tarea'];
    $usuarioController = new UsuarioController();
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $usuario = $usuarioController->getUserByName($nombre_usuario);
    $usuarioId = $usuario->getIdUsuario(); 

    // Crear una instancia del controlador de comentarios
    $comentarioController = new ComentarioController();
    
    // Insertamos el comentario en la base de datos
    $comentarioController->crearComentario($tareaId, $usuarioId, $comentario);

    // Redirigimos nuevamente a la página de detalle de la tarea
    header("Location: detalleTarea.php?id=$tareaId");
    exit;
} else {
    // Si no se ha enviado correctamente, redirigir a la página de detalle de la tarea
    echo "Error: Comentario no enviado.";
    // header("Location: detalleTarea.php?id=" . $_POST['id_tarea']);
    exit;
}
