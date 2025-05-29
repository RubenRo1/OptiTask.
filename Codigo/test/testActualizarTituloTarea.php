<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../rutas.php');
require_once(MODEL . 'Tarea.php');

class testActualizarTituloTarea extends TestCase
{
    public function testActualizarTarea()
    {
        // Crear una nueva tarea
        $tarea = new Tarea();
        $tarea->setIdUsuario(1);
        $tarea->setTitulo("Título original");
        $tarea->setFechaLimite("2025-06-30");
        $tarea->setPrioridad("Media");
        $tarea->setEstado("Pendiente");
        $tarea->setDescripcion("Descripción de prueba");
        $tarea->create();

        // Actualizar el título
        $nuevoTitulo = "Título actualizado";
        $tarea->updateTitulo($nuevoTitulo);

        // Obtener la tarea actualizada desde la base de datos
        $tareaActualizada = Tarea::getTareaById($tarea->getIdTarea());

        // Verificar que el título fue actualizado
        $this->assertEquals($nuevoTitulo, $tareaActualizada->getTitulo());

        // Limpieza: eliminar la tarea creada
        $tarea->delete();
    }
}
