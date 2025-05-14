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

if (isset($_POST['titulo'], $_POST['descripcion'], $_POST['fecha_limite'], $_POST['prioridad'], $_POST['estado'], $_POST['tiempo_estimado'])) {
    $titulo = htmlspecialchars($_POST['titulo']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $fecha_limite = htmlspecialchars($_POST['fecha_limite']);
    $prioridad = htmlspecialchars($_POST['prioridad']);
    $estado = htmlspecialchars($_POST['estado']);
    $tiempo_estimado = htmlspecialchars($_POST['tiempo_estimado']);

    // Validaciones
    $valid = true;

    if (!is_numeric($tiempo_estimado)) {
        $error_tiempo = 'El tiempo estimado debe ser un número';
        $valid = false;
    }

    if (strtotime($fecha_limite) < strtotime('today')) {
        $error_fecha = 'La fecha límite no puede ser en el pasado';
        $valid = false;
    }

    if ($valid) {
        $id_usuario = $usuario->getIdUsuario();
        $tareaController = new TareaController();
        $id_tarea = $tareaController->crearTarea($id_usuario, $titulo, $fecha_limite, $prioridad, $estado, $descripcion, $tiempo_estimado);
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
    <title>Tareas</title>
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

            <label>Prioridad:</label>
            <select name="prioridad" required>
                <option value="alta" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                <option value="media" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'media') ? 'selected' : ''; ?> selected>Media</option>
                <option value="baja" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'baja') ? 'selected' : ''; ?>>Baja</option>
            </select>

            <label>Estado:</label>
            <select name="estado" required>
                <option value="pendiente" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'pendiente') ? 'selected' : ''; ?> selected>Pendiente</option>
                <option value="en progreso" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'en progreso') ? 'selected' : ''; ?>>En Progreso</option>
                <option value="completada" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
            </select>

            <label>Tiempo estimado:</label>
            <input type="text" name="tiempo_estimado" placeholder="Tiempo en minutos" required value="<?php echo isset($_POST['tiempo_estimado']) ? htmlspecialchars($_POST['tiempo_estimado']) : ''; ?>">
            <?php if (!empty($error_tiempo)): ?>
                <div class="error-message"><?php echo $error_tiempo; ?></div>
            <?php endif; ?>

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
    form select:focus,
    form textarea:focus {
        outline: none;
        box-shadow: 0 0 0 2px #1ABC9C;
    }

    form textarea {
        min-height: 150px;
        max-height: 300px;
        white-space: pre-wrap;
        /* Conserva saltos de línea */
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

    form select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #2B2B2B;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white'%3E%3Cpath d='M4 6l4 4 4-4z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 32px;
        cursor: pointer;

    }
</style>

</html>