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

$tareaController = new TareaController();

$error_fecha = '';
$error_tiempo = '';
$tarea = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_tarea'];
    $titulo = htmlspecialchars($_POST['titulo']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $fecha_limite = $_POST['fecha_limite'];
    $estado = $_POST['estado'];
    $prioridad = $_POST['prioridad'];

    $valid = true;

    if (strtotime($fecha_limite) < strtotime(date('Y-m-d'))) {
        $error_fecha = 'La fecha límite no puede ser en el pasado';
        $valid = false;
    }

    if ($valid) {
        $tareaController->modificarTarea($id, $titulo, $fecha_limite, $prioridad, $estado, $descripcion);
        header("Location: detalleTarea.php?id=$id");
        exit();
    } else {
        // Si hay errores, recupera la tarea para repoblar el formulario con valores antiguos
        $tarea = $tareaController->getTareaById($id);
    }
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $tarea = $tareaController->getTareaById($id);
    if (!$tarea) {
        echo "<p class='error-message'>Tarea no encontrada.</p>";
        exit();
    }
} else {
    echo "<p class='error-message'>ID de tarea no especificado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../CSS/editar_tarea.css">
</head>

<body>
    <?php include "../Generales/header.php" ?>
    <h2>Editar Tarea</h2>
    <form method="POST" action="editar_tarea.php">
        <input type="hidden" name="id_tarea" value="<?php echo $tarea->getIdTarea(); ?>">

        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($tarea->getTitulo()); ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" rows="5" required><?php echo htmlspecialchars($tarea->getDescripcion()); ?></textarea>

        <label for="fecha_limite">Fecha límite:</label>
        <input type="date" name="fecha_limite" id="fecha_limite" value="<?php echo date('Y-m-d', strtotime($tarea->getFechaLimite())); ?>" required>
        <?php if (!empty($error_fecha)): ?>
            <div class="error-message"><?php echo $error_fecha; ?></div>
        <?php endif; ?>

        <div class="select-wrapper">
            <label for="prioridad">Prioridad:</label>
            <select name="prioridad" id="prioridad" required>
                <option value="Alta" <?php if ($tarea->getPrioridad() == 'Alta') echo 'selected'; ?>>Alta</option>
                <option value="Media" <?php if ($tarea->getPrioridad() == 'Media') echo 'selected'; ?>>Media</option>
                <option value="Baja" <?php if ($tarea->getPrioridad() == 'Baja') echo 'selected'; ?>>Baja</option>
            </select>
            <i class="fas fa-chevron-down"></i>
        </div>

        <div class="select-wrapper">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado">
                <option value="Pendiente" <?php if ($tarea->getEstado() == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="En progreso" <?php if ($tarea->getEstado() == 'En progreso') echo 'selected'; ?>>En progreso</option>
                <option value="Completada" <?php if ($tarea->getEstado() == 'Completada') echo 'selected'; ?>>Completada</option>
            </select>
            <i class="fas fa-chevron-down"></i>
        </div>

        <button type="submit">Guardar Cambios</button>

        <?php if (!empty($error_tiempo)): ?>
            <div class="error-message"><?php echo $error_tiempo; ?></div>
        <?php endif; ?>
    </form>

</body>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>