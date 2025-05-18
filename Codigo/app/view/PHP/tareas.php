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
<style>
    html,
    body {
        overflow: hidden;
        overflow-y: auto;
    }

    body::-webkit-scrollbar {
        display: none;

    }

    .error-message {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
        padding: 5px;
        background-color: rgba(231, 76, 60, 0.1);
        border-radius: 4px;
        border-left: 3px solid #e74c3c;
    }

    form {
        background-color: #1E1E1E;
        border-radius: 12px;
        padding: 25px 20px;
        max-width: 680px;
        margin: 50px auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #EEE;
        font-family: 'Comfortaa', sans-serif;
    }

    form label {
        font-size: 14px;
        font-weight: 600;
        color: #CCCCCC;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="date"],
    form select,
    form textarea {
        background-color: #2B2B2B;
        border: none;
        border-radius: 6px;
        padding: 10px 12px;
        color: white;
        font-size: 14px;
        box-shadow: inset 0 0 0 1px #444;
        transition: box-shadow 0.2s ease;
        resize: none;

    }

    form input[type="text"]:focus,
    form input[type="date"]:focus,
    form input[type="number"],
    form select:focus,
    form textarea:focus {
        outline: none;
        box-shadow: 0 0 0 2px #1ABC9C;
    }

    form textarea {
        min-height: 150px;
        max-height: 300px;
        white-space: pre-wrap;
        overflow-y: auto;
        word-wrap: break-word;
        /* Rompe palabras largas */
    }

    form button {
        background-color: #1ABC9C;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 12px 20px;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        align-self: flex-end;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #16A085;
    }

    .select-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .select-wrapper select {
        width: 100%;
        padding-right: 40px;
        background-color: #2B2B2B;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 14px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        box-shadow: inset 0 0 0 1px #444;
    }

    .select-wrapper i {
        position: absolute;
        right: 12px;
        top: 60%;
        pointer-events: none;
        color: #ccc;
        font-size: 9px;
    }

    /* Oculta las flechas en input type=number en Chrome, Safari, Edge, Opera */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
<!-- Asegúrate de que FontAwesome esté incluido en tu proyecto para usar los iconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>