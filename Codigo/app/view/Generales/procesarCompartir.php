<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(CONTROLLER . 'CompartidasController.php');
require_once(MODEL . 'Usuario.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tarea = $_POST['id_tarea'] ?? null;
    $nombre_usuario_destino = $_POST['nombre_usuario_destino'] ?? null;
    $permiso = $_POST['permiso'] ?? null;


    if (!$id_tarea || !$nombre_usuario_destino || !$permiso) {
        echo 'Datos incompletos.';
        exit;
    }

    // Buscar el usuario destino por nombre
    $usuarioDestino = Usuario::getUserByName($nombre_usuario_destino);
    if (!$usuarioDestino) {
        echo 'Usuario no encontrado.';
        exit;
    }

    session_start();
    $nombre_usuario_origen = $_SESSION['nombre_usuario']; // Asegúrate de que esté en sesión

    if ($nombre_usuario_destino === $nombre_usuario_origen) {
        echo 'No puedes compartir la tarea contigo mismo.';
        exit;
    }

    //No puedes compartir una tarea 2 veces con el mismo usuario
    $compartidaExistente = Compartidas::getCompartidasByTareaAndUser($id_tarea, $usuarioDestino->getIdUsuario());
    if ($compartidaExistente) {
        echo 'La tarea ya está compartida con este usuario.';
        exit;
    }
    $id_usuario_origen = Usuario::getUserByName($nombre_usuario_origen)->getIdUsuario();
    $controller = new CompartidasController();
    $controller->compartirTarea($id_tarea, $id_usuario_origen, $usuarioDestino->getIdUsuario(), $permiso);

    echo 'Tarea compartida con ' . htmlspecialchars($nombre_usuario_destino);
}
