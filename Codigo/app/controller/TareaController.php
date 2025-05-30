<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'Tarea.php');

class TareaController
{
    // Obtener todas las tareas
    public function getAllTareas()
    {
        return Tarea::getAllTareas();
    }

    // Obtener tarea por ID
    public function getTareaById($id_tarea)
    {
        return Tarea::getTareaById($id_tarea);
    }
    // Obtener tareas por usuario
    public function getTareasByUser($id_usuario)
    {
        return Tarea::getTareasByUser($id_usuario);
    }

    // Crear una nueva tarea
    public function crearTarea($id_usuario, $titulo, $fecha_limite, $prioridad, $estado, $descripcion)
    {
        $nuevaTarea = new Tarea();
        $nuevaTarea->setIdUsuario($id_usuario);
        $nuevaTarea->setTitulo($titulo);
        $nuevaTarea->setFechaLimite($fecha_limite);
        $nuevaTarea->setPrioridad($prioridad);
        $nuevaTarea->setEstado($estado);
        $nuevaTarea->setDescripcion($descripcion);
 

        $nuevaTarea->create();

        $id_tarea = $nuevaTarea->getIdTarea(); // Obtener el ID de la tarea recién creada

        return $id_tarea;
    }

    // Modificar tarea (cualquier campo)
    public function modificarTarea($id_tarea, $titulo, $fecha_limite, $prioridad, $estado, $descripcion)
    {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setTitulo($titulo);
            $tarea->setFechaLimite($fecha_limite);
            $tarea->setPrioridad($prioridad);
            $tarea->setEstado($estado);
            $tarea->setDescripcion($descripcion);

            $tarea->update();
        }
    }

    // Eliminar tarea
    public function eliminarTarea($id_tarea)
    {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->delete();
        }
    }

    public function modificarTitulo($id_tarea, $nuevoTitulo)
    {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setTitulo($nuevoTitulo);
            $tarea->updateTitulo($nuevoTitulo);  // Pasamos el nuevo título aquí
        }
    }

    public function modificarEstado($id_tarea, $nuevoEstado)
    {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setEstado($nuevoEstado);
            $tarea->updateEstado($nuevoEstado);  // Pasamos el nuevo estado aquí
        }
    }

    public function modificarPrioridad($id_tarea, $nuevaPrioridad)
    {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setPrioridad($nuevaPrioridad);
            $tarea->updatePrioridad($nuevaPrioridad);  // Pasamos la nueva prioridad aquí
        }
    }

    public function modificarFechaLimite($id_tarea, $nuevaFechaLimite)
    {
        $tarea = Tarea::getTareaById($id_tarea);
        if ($tarea) {
            $tarea->setFechaLimite($nuevaFechaLimite);
            $tarea->updateFechaLimite($nuevaFechaLimite);  // Pasamos la nueva fecha límite aquí
        }
    }

    // Obtener tareas urgentes (por fecha límite)

    public function getTareasUrgentes($id_usuario, $limite = 5)
    {
        return Tarea::getTareasUrgentes($id_usuario, $limite);
    }
}
