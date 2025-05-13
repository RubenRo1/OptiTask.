<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'Subtarea.php');

class SubtareaController
{
    // Crear una nueva subtarea
    public function crearSubtarea($descripcion, $tareaId)
    {
        $subtarea = new Subtarea();
        $subtarea->setDescripcion($descripcion);
        $subtarea->setIdTarea($tareaId);
        $subtarea->setCompletado(0); // Asumiendo que por defecto no está completada
        $subtarea->create();
        return $subtarea;
    }

    // Obtener una subtarea por su ID
    public function obtenerSubtareaPorId($id)
    {
        return Subtarea::getSubtareaById($id);
    }

    // Obtener todas las subtareas
    public function obtenerTodasSubtareas()
    {
        return Subtarea::getAllSubtareas();
    }

    // Obtener todas las subtareas de una tarea
    public function obtenerSubtareasPorTarea($idTarea)
    {
        return Subtarea::getSubtareasByTarea($idTarea);
    }

    // Actualizar una subtarea (descripción y completado)
    public function actualizarSubtarea($id, $nuevaDescripcion, $nuevoEstadoCompletado)
    {
        $subtarea = Subtarea::getSubtareaById($id);
        if ($subtarea) {
            // Actualizamos la descripción y el estado de completado
            $subtarea->updateDescripcion($nuevaDescripcion);
            $subtarea->updateCompletado($nuevoEstadoCompletado);
            return true;
        }
        return false;
    }

    // Eliminar una subtarea
    public function eliminarSubtarea($id)
    {
        $subtarea = Subtarea::getSubtareaById($id);
        if ($subtarea) {
            $subtarea->delete();
            return true;
        }
        return false;
    }
}
