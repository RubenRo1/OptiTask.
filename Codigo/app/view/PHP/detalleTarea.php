<?php

require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(MODEL . 'Tarea.php');

session_start();

if (!isset($_SESSION['nombre_usuario'])) {

    $usuario = null;
    $nombre_usuario = null;
} else {

    $usuarioController = new UsuarioController();
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $usuario = $usuarioController->getUserByName($nombre_usuario);
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $tareaId = $_GET['id'];

    // Obtener la tarea usando el controlador
    $tareaController = new TareaController();
    $Tareas = $tareaController->getTareaById($tareaId); // Asumimos que este método existe en tu controlador
    $cont = 0;
} else {
    // Si no se pasa el ID, redirigir o mostrar un error
    $cont = 1;
}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea</title>
</head>

<body>
    <?php include "../Generales/header.php"; ?>
    <?php include "../Generales/sidebar.php"; ?>

    <?php if ($cont == 1) : ?>
        <div class="content general">
            <h3>Aqui veras la informacion de la tarea.</h2>
        </div>
    <?php else: ?>
        <div class="content">
            <div class="general">
                <div class="tarea-detalle">
                    <h2><?php echo htmlspecialchars($Tareas->getTitulo()); ?></h2>
                    <p><?php echo htmlspecialchars($Tareas->getDescripcion()); ?></p>
                    <p><strong>Fecha de entrega:</strong> <?php echo htmlspecialchars($Tareas->getFechaLimite()); ?></p>
                    <p><strong>Estado:</strong> <?php echo htmlspecialchars($Tareas->getEstado()); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>

<style>
    .general {
        margin: 70px auto;
        border: 1px solid #39424A;
        background-color: #2C3E50;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        width: 40%;
        color: white;
        word-wrap: break-word;

    }


    .general h2 {

        text-align: center;
        margin-top: 20px;
    }

    .general h3 {

        text-align: center;
        margin-top: 20px;

    }
</style>

</html>