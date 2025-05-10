<?php

require_once('C:\xampp\htdocs\OptiTask\Codigo\rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');

session_start();

// comprobamos si el usuario no está logeado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarioController = new UsuarioController();
$nombre_usuario = $_SESSION['nombre_usuario'];
$usuario = $usuarioController->getUserByName($nombre_usuario);

echo '<img src="' . $usuario->getImagen() . '" alt="Foto de perfil" style="width: 65px; height: 45px; border-radius: 50%;">';
echo  $usuario->getImagen()
?>