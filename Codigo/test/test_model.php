<?php
require_once(__DIR__ . '/../rutas.php');
require_once(MODEL . 'Usuario.php');

// Crear un objeto Usuario
$usuario = new Usuario();
$usuario->setNombre("Uno");
$usuario->setEmail("Uno@example.com");
$usuario->setContraseña("contraseña321"); // Recuerda que la contraseña se hashea en el modelo
$usuario->create();

// Verificar que el usuario se ha creado correctamente (puedes buscarlo en la base de datos)
echo "Usuario creado con éxito.";
?>
