<?php

require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'Compartidas.php');

class CompartidasController
{
    // Compartir una tarea con un usuario
    public function compartirTarea($id_tarea, $id_usuario_origen, $id_usuario_destino, $permiso)
    {
        // Verificar si la tarea ya está compartida con el usuario destino
        $compartidaExistente = Compartidas::getCompartidasByTareaAndUser($id_tarea, $id_usuario_destino);
        if ($compartidaExistente) {
            return $compartidaExistente; // Retornar la compartida existente
        }

        // Crear una nueva compartida
        {
            $compartida = new Compartidas();
            $compartida->setIdTarea($id_tarea);
            $compartida->setUsuario_Origen($id_usuario_origen);
            $compartida->setUsuario_Destino($id_usuario_destino); // Asumiendo que el usuario origen es el mismo que el destino
            $compartida->setPermiso($permiso);
            $compartida->create();
            return $compartida;
        }
    }

    // Obtener una tarea compartida por ID de compartida
    public function obtenerCompartidaPorId($id_compartidas)
    {
        return Compartidas::getCompartidasById($id_compartidas);
    }

    // Obtener todas las tareas compartidas
    public function obtenerTodasLasCompartidas()
    {
        return Compartidas::getAllCompartidas();
    }

    // Obtener tareas compartidas por tarea
    public function obtenerCompartidasPorTarea($id_tarea)
    {
        return Compartidas::getByTarea($id_tarea);
    }

    // Obtener tareas compartidas por usuario
    public function obtenerCompartidasPorUsuario($id_usuario_origen)
    {
        return Compartidas::getByUser($id_usuario_origen);
    }

    public function obtenerCompartidasPorUsuarioDestino($id_usuario_destino)
    {
        return Compartidas::getByUserDestino($id_usuario_destino);
    }

    // Obtener tareas compartidas por usuario y tarea
    public function obtenerCompartidasPorUsuarioYTarea($id_usuario_origen, $id_tarea)
    {
        return Compartidas::getCompartidasByTareaAndUser($id_usuario_origen, $id_tarea);
    }

    // Modificar una tarea compartida
    public function modificarCompartida($id_compartidas, $id_tarea, $id_usuario_origen, $id_usuario_destino, $permiso)
    {
        $compartida = Compartidas::getCompartidasById($id_compartidas);
        if ($compartida) {
            $compartida->setIdTarea($id_tarea);
            $compartida->setUsuario_Origen($id_usuario_origen);
            $compartida->setUsuario_Destino($id_usuario_destino);
            $compartida->setPermiso($permiso);
            $compartida->update();
        }
    }

    public function modificarPermiso($idTarea, $idUsuario, $nuevoPermiso)
    {
        // Obtener la fila concreta de compartición (tarea + usuario)
        $compartida = Compartidas::getCompartidasByTareaAndUser($idTarea, $idUsuario);

        if ($compartida) {
            // Actualizamos el permiso en el objeto
            $compartida->setPermiso($nuevoPermiso);
            // Llamamos al método que persiste el cambio en la DB
            return $compartida->updatePermiso();
        }
    }

    // Eliminar una tarea compartida
    public function eliminarCompartida($id_compartidas)
    {
        $compartida = Compartidas::getCompartidasById($id_compartidas);
        if ($compartida) {
            $compartida->delete();
        }
    }
}
