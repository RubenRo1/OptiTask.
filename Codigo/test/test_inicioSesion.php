<?php
require_once(__DIR__ . '/../rutas.php');
require_once(MODEL . 'Usuario.php');

$email = "Uno@example.com";  // Correo electrónico del usuario
$contraseña = "contraseña321"; // Contraseña ingresada por el usuario

// Verificar si el usuario existe y si la contraseña es correcta
$usuario = Usuario::verifyPassword($email, $contraseña);

if ($usuario) {
    echo "Usuario autenticado con éxito.";
} else {
    echo "Credenciales incorrectas.";
}
?>
