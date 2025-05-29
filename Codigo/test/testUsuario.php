<?php
use PHPUnit\Framework\TestCase;

//Rutas
require_once(__DIR__ . '/../rutas.php');
require_once(MODEL . 'Usuario.php');


class testUsuario extends TestCase
{
    // Método de prueba para verificar que los métodos setNombre y getNombre funcionen correctamente
    public function testSetYGetNombre()
    {
        // Crea un objeto Usuario
        $usuario = new Usuario();
        $usuario->setNombre("María");
        
        // Comprueba que el método getNombre devuelve el valor que se estableció ("María")
        $this->assertEquals("María", $usuario->getNombre());
    }
}
