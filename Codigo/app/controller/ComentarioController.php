<?php

require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'Comentario.php');

class ComentarioController {

    // Obtener todos los comentarios
    public function getAllComentarios() {
        return Comentario::getAllComentarios(); // Llamamos al método estático para obtener todos los comentarios
    }

    // Obtener todos los comentarios de una tarea específica
    public function getComentariosByTarea($id_tarea) {
        return Comentario::getByTarea($id_tarea); // Llamamos al método estático para obtener comentarios por tarea
    }

    // Obtener comentarios de un usuario específico
    public function getComentariosByUser($id_usuario) {
        return Comentario::getByUser($id_usuario); // Llamamos al método estático para obtener comentarios por usuario
    }

    // Crear un nuevo comentario
    public function crearComentario($id_tarea, $id_usuario, $contenido) {
        $nuevoComentario = new Comentario();
        $nuevoComentario->setIdTarea($id_tarea);
        $nuevoComentario->setIdUsuario($id_usuario);
        $nuevoComentario->setContenido($contenido);
        $nuevoComentario->create(); // Llamamos al método create para insertar el nuevo comentario
    }

    // Eliminar un comentario por ID
    public function eliminarComentario($id_comentario) {
        $comentario = Comentario::getComentarioById($id_comentario); // Obtén el comentario por su ID
        if ($comentario) {
            $comentario->delete(); // Llama al método delete en el objeto
        }
    }

    // Modificar el contenido de un comentario
    public function modificarComentario($id_comentario, $nuevoContenido) {
        $comentario = Comentario::getComentarioById($id_comentario); // Obtén el comentario por su ID
        if ($comentario) {
            $comentario->setContenido($nuevoContenido);
            $comentario->update(); // Llama al método update para actualizar el comentario
        }
    }
}
