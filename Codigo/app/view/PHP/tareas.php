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

// Inicializar variables de error
$error_tiempo = '';
$error_fecha = '';

if (isset($_POST['titulo'], $_POST['descripcion'], $_POST['fecha_limite'], $_POST['prioridad'], $_POST['estado'])) {
    $titulo = htmlspecialchars($_POST['titulo']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $fecha_limite = htmlspecialchars($_POST['fecha_limite']);
    $prioridad = htmlspecialchars($_POST['prioridad']);
    $estado = htmlspecialchars($_POST['estado']);

    // Validaciones
    $valid = true;

    if (strtotime($fecha_limite) < strtotime('today')) {
        $error_fecha = 'La fecha límite no puede ser en el pasado';
        $valid = false;
    }

    if ($valid) {
        $id_usuario = $usuario->getIdUsuario();
        $tareaController = new TareaController();
        $id_tarea = $tareaController->crearTarea($id_usuario, $titulo, $fecha_limite, $prioridad, $estado, $descripcion);
        header("Location: detalleTarea.php?id=" . $id_tarea);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tarea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../CSS/tareas.css">
</head>

<body>
    <?php include "../Generales/header.php" ?>
    <div class="content">
        <form method="POST" action="tareas.php">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" placeholder="Título" required maxlength="30" value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">

            <label>Descripción</label>
            <textarea name="descripcion" placeholder="Añadir una descripción más detallada..."><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>

            <label>Fecha límite:</label>
            <input type="date" name="fecha_limite" required value="<?php echo isset($_POST['fecha_limite']) ? $_POST['fecha_limite'] : ''; ?>">
            <?php if (!empty($error_fecha)): ?>
                <div class="error-message"><?php echo $error_fecha; ?></div>
            <?php endif; ?>

            <div class="select-wrapper">
                <label>Prioridad:</label>
                <select name="prioridad" required>
                    <option value="alta" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                    <option value="media" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'media') ? 'selected' : ''; ?> selected>Media</option>
                    <option value="baja" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'baja') ? 'selected' : ''; ?>>Baja</option>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>

            <div class="select-wrapper">
                <label>Estado:</label>
                <select name="estado" required>
                    <option value="pendiente" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'pendiente') ? 'selected' : ''; ?> selected>Pendiente</option>
                    <option value="en progreso" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'en progreso') ? 'selected' : ''; ?>>En Progreso</option>
                    <option value="completada" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>

            <button type="submit">Crear tarea</button>
        </form>
    </div>
</body>
<!-- Asegúrate de que FontAwesome esté incluido en tu proyecto para usar los iconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>