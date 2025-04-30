<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'Tarea.php');
require_once(MODEL . 'Categoria.php');  // Suponiendo que también quieras acceder a categorías desde aquí.

class TareaController {
    // Obtener todas las tareas
    public function getAllTareas() {
        return Tarea::getAllTareas();
    }

    // Obtener tarea por ID
    public function getTareaById($id_tarea) {
        return Tarea::getTareaById($id_tarea);
    }

    // Crear una nueva tarea
    public function crearTarea($id_usuario, $titulo, $fecha_creacion, $fecha_limite, $prioridad, $estado, $descripcion, $tiempo_estimado, $id_categoria = null) {
        $nuevaTarea = new Tarea();
        $nuevaTarea->setIdUsuario($id_usuario);
        $nuevaTarea->setTitulo($titulo);
        $nuevaTarea->setFechaCreacion($fecha_creacion);
        $nuevaTarea->setFechaLimite($fecha_limite);
        $nuevaTarea->setPrioridad($prioridad);
        $nuevaTarea->setEstado($estado);
        $nuevaTarea->setDescripcion($descripcion);
        $nuevaTarea->setTiempoEstimado($tiempo_estimado);
        $nuevaTarea->setIdCategoria($id_categoria);  // Solo si se proporciona

        $nuevaTarea->create();
    }

    // Modificar tarea (cualquier campo)
    public function modificarTarea($id_tarea, $titulo, $fecha_creacion, $fecha_limite, $prioridad, $estado, $descripcion, $tiempo_estimado, $id_categoria = null) {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setTitulo($titulo);
            $tarea->setFechaCreacion($fecha_creacion);
            $tarea->setFechaLimite($fecha_limite);
            $tarea->setPrioridad($prioridad);
            $tarea->setEstado($estado);
            $tarea->setDescripcion($descripcion);
            $tarea->setTiempoEstimado($tiempo_estimado);
            $tarea->setIdCategoria($id_categoria);  // Solo si se proporciona

            $tarea->update();
        }
    }

    // Eliminar tarea
    public function eliminarTarea($id_tarea) {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->delete();
        }
    }

    public function modificarTitulo($id_tarea, $nuevoTitulo) {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setTitulo($nuevoTitulo);
            $tarea->updateTitulo($nuevoTitulo);  // Pasamos el nuevo título aquí
        }
    }
    
    public function modificarEstado($id_tarea, $nuevoEstado) {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setEstado($nuevoEstado);
            $tarea->updateEstado($nuevoEstado);  // Pasamos el nuevo estado aquí
        }
    }
    
    public function modificarPrioridad($id_tarea, $nuevaPrioridad) {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setPrioridad($nuevaPrioridad);
            $tarea->updatePrioridad($nuevaPrioridad);  // Pasamos la nueva prioridad aquí
        }
    }
    
    public function modificarTiempoEstimado($id_tarea, $nuevoTiempoEstimado) {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setTiempoEstimado($nuevoTiempoEstimado);
            $tarea->updateTiempoEstimado($nuevoTiempoEstimado);  // Pasamos el nuevo tiempo estimado aquí
        }
    }
    
    public function modificarFechaLimite($id_tarea, $nuevaFechaLimite) {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setFechaLimite($nuevaFechaLimite);
            $tarea->updateFechaLimite($nuevaFechaLimite);  // Pasamos la nueva fecha límite aquí
        }
    }
    
}
?>
