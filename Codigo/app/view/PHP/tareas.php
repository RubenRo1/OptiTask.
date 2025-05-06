<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(MODEL . 'Tarea.php');

session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarioController = new UsuarioController();
$nombre_usuario = $_SESSION['nombre_usuario'];
$usuario = $usuarioController->getUserByName($nombre_usuario);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
</head>

<body>
    <?php include "../Generales/header.php" ?>

    <div class="content">

        <form method="POST" action="tareas.php">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="descripcion" placeholder="Descripción..."></textarea>
            <label>Fecha límite:</label>
            <input type="date" name="fecha_limite" required>
            <label>Prioridad:</label>
            <select name="prioridad" required>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="baja">Baja</option>
            </select>
            <label>Estado:</label>
            <select name="estado" required>
                <option value="pendiente">Pendiente</option>
                <option value="en progreso">En progreso</option>
                <option value="completada">Completada</option>
            </select>
            <label>Tiempo estimado:</label>
            <input type="text" name="tiempo_estimado" placeholder="Ej: 2 horas" required>
            <!-- <label>Categoría:</label> -->
            <!-- <select name="id_categoria" required> -->
            <!--     <option value="1">Trabajo</option> -->
            <!--     <option value="2">Personal</option> -->
            <!--     <option value="3">Estudio</option> -->
            <!-- </select> -->
            <button type="submit">Crear tarea</button>
        </form>

    </div>



    <?php
    if (isset($_POST['titulo'], $_POST['descripcion'], $_POST['fecha_limite'], $_POST['prioridad'], $_POST['estado'], $_POST['tiempo_estimado'])) {
        $titulo = htmlspecialchars($_POST['titulo']);
        $descripcion = htmlspecialchars($_POST['descripcion']);
        $fecha_limite = htmlspecialchars($_POST['fecha_limite']);
        $prioridad = htmlspecialchars($_POST['prioridad']);
        $estado = htmlspecialchars($_POST['estado']);
        $tiempo_estimado = htmlspecialchars($_POST['tiempo_estimado']);
        // $id_categoria = htmlspecialchars($_POST['id_categoria']);
        $id_usuario = $usuario->getIdUsuario();
        $tareaController = new TareaController();
        $tareaController->crearTarea($id_usuario, $titulo, $fecha_limite, $prioridad, $estado, $descripcion, $tiempo_estimado);
    }

    ?>
</body>
    <style>

        form {
            
            margin: 50px auto;
        }
    </style>

</html>