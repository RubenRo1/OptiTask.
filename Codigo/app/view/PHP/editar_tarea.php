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
    $tiempo_estimado = $_POST['tiempo_estimado'];

    $valid = true;

    if (!is_numeric($tiempo_estimado)) {
        $error_tiempo = 'El tiempo estimado debe ser un número';
        $valid = false;
    }

    if (strtotime($fecha_limite) < strtotime(date('Y-m-d'))) {
        $error_fecha = 'La fecha límite no puede ser en el pasado';
        $valid = false;
    }

    if ($valid) {
        $tareaController->modificarTarea($id, $titulo, $fecha_limite, $prioridad, $estado, $descripcion, $tiempo_estimado);
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
        form input[type="number"],
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

        form textarea::-webkit-scrollbar {
            width: 5px;
        }

        form textarea::-webkit-scrollbar-thumb {
            background: #414548;
            border-radius: 3px;
        }

        form textarea::-webkit-scrollbar-track {
            background: #2B2B2B;
        }

        /* Oculta las flechas en input type=number en Chrome, Safari, Edge, Opera */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
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

        <label for="tiempo_estimado">Tiempo estimado (horas):</label>
        <input type="number" name="tiempo_estimado" id="tiempo_estimado" min="1" value="<?php echo $tarea->getTiempoEstimado(); ?>" required>

        <button type="submit">Guardar Cambios</button>

        <?php if (!empty($error_tiempo)): ?>
            <div class="error-message"><?php echo $error_tiempo; ?></div>
        <?php endif; ?>
    </form>

</body>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>