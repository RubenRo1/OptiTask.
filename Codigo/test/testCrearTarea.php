<?php
use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../rutas.php');
require_once(MODEL . 'Tarea.php');

class testCrearTarea extends TestCase
{
    public function testCrearYObtenerTarea()
    {
        // Crear una nueva tarea
        $tarea = new Tarea();
        $tarea->setIdUsuario(1); 
        $tarea->setTitulo("Test Tarea");
        $tarea->setFechaLimite("2025-12-31");
        $tarea->setPrioridad("Alta");
        $tarea->setEstado("Pendiente");
        $tarea->setDescripcion("Descripción de prueba");
        $idCreada = $tarea->create();

        // Obtener la tarea desde la base de datos
        $tareaDB = Tarea::getTareaById($idCreada);

        // Verificar que se recupera correctamente
        $this->assertNotNull($tareaDB);
        $this->assertEquals("Test Tarea", $tareaDB->getTitulo());
        $this->assertEquals("Alta", $tareaDB->getPrioridad());
        $this->assertEquals("Pendiente", $tareaDB->getEstado());
        $this->assertEquals("Descripción de prueba", $tareaDB->getDescripcion());

        // Limpieza: eliminar la tarea creada
        $tareaDB->delete();
    }
}
