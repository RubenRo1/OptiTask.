<?php

require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'HistorialEstado.php');

class HistorialEstadoController
{
    // Crear un nuevo historial de estado
    public function crearHistorial($id_tarea, $estado_anterior, $estado_nuevo)
    {
        $historial = new HistorialEstado();
        $historial->setIdTarea($id_tarea);
        $historial->setEstadoAnterior($estado_anterior);
        $historial->setEstadoNuevo($estado_nuevo);
        $historial->create();
        return $historial;
    }

    // Obtener un historial por ID
    public function obtenerHistorialPorId($id_historial)
    {
        return HistorialEstado::getHistorialEstadosById($id_historial);
    }

    // Obtener todos los historiales
    public function obtenerTodosLosHistoriales()
    {
        return HistorialEstado::getAllHistorialEstados();
    }

    // Obtener historial por tarea
    public function obtenerHistorialPorTarea($id_tarea)
    {
        return HistorialEstado::getByTarea($id_tarea);
    }
}
