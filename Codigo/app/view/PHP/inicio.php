<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(MODEL . 'Tarea.php');
require_once(CONTROLLER . 'CompartidasController.php');
require_once(MODEL . 'Compartidas.php');

session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarioController = new UsuarioController();
$tareaController = new TareaController();
$compartidasController = new CompartidasController();

$nombre_usuario = $_SESSION['nombre_usuario'];
$usuario = $usuarioController->getUserByName($nombre_usuario);
$id_usuario = $usuario->getIdUsuario();

$tareas = $tareaController->getTareasByUser($id_usuario);
$tareas_compartidas = $compartidasController->obtenerCompartidasPorUsuarioDestino($id_usuario);
$tareas_urgentes = $tareaController->getTareasUrgentes($id_usuario, 3);

if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['modificar_cuenta'])) {
    header("Location: cuenta.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>

<body>
    <?php
    include "../Generales/header.php";
    include "../Generales/sidebar.php";
    ?>

    <div class="content">
        <div class="general estilo">
            <h1>Bienvenido <?php echo htmlspecialchars($nombre_usuario); ?></h1>

            <div class="controls">
                <button class="btn-new-task" onclick="window.location.href='tareas.php'">
                    <i class="fas fa-plus"></i> Nueva tarea
                </button>
            </div>

            <div class="columnas">
                <!-- Pendientes -->
                <div class="column">
                    <div class="column-header">
                        <h3><i class="fas fa-list"></i> Pendientes</h3>
                        <span class="badge"><?= count(array_filter($tareas, fn($t) => $t->getEstado() == 'Pendiente')) ?></span>
                    </div>
                    <div class="column-content">
                        <?php foreach (array_filter($tareas, fn($t) => $t->getEstado() == 'Pendiente') as $t): ?>
                            <div class="card pending" onclick="window.location.href='detalleTarea.php?id=<?= $t->getIdTarea() ?>'">
                                <div class="card-priority priority-<?= strtolower($t->getPrioridad()) ?>"></div>
                                <h4><?= htmlspecialchars($t->getTitulo()) ?></h4>
                                <div class="card-due-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d M', strtotime($t->getFechaLimite())) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- En Progreso -->
                <div class="column">
                    <div class="column-header">
                        <h3><i class="fas fa-spinner"></i> En Progreso</h3>
                        <span class="badge"><?= count(array_filter($tareas, fn($t) => $t->getEstado() == 'En Progreso')) ?></span>
                    </div>
                    <div class="column-content">
                        <?php foreach (array_filter($tareas, fn($t) => $t->getEstado() == 'En Progreso') as $t): ?>
                            <div class="card in-progress" onclick="window.location.href='detalleTarea.php?id=<?= $t->getIdTarea() ?>'">
                                <div class="card-priority priority-<?= strtolower($t->getPrioridad()) ?>"></div>
                                <h4><?= htmlspecialchars($t->getTitulo()) ?></h4>
                                <div class="card-due-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d M', strtotime($t->getFechaLimite())) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Completadas -->
                <div class="column">
                    <div class="column-header">
                        <h3><i class="fas fa-check-circle"></i> Completadas</h3>
                        <span class="badge"><?= count(array_filter($tareas, fn($t) => $t->getEstado() == 'Completada')) ?></span>
                    </div>
                    <div class="column-content">
                        <?php foreach (array_filter($tareas, fn($t) => $t->getEstado() == 'Completada') as $t): ?>
                            <div class="card completed" onclick="window.location.href='detalleTarea.php?id=<?= $t->getIdTarea() ?>'">
                                <div class="card-priority priority-<?= strtolower($t->getPrioridad()) ?>"></div>
                                <h4><?= htmlspecialchars($t->getTitulo()) ?></h4>
                                <div class="card-due-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d M', strtotime($t->getFechaLimite())) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .estilo {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-left: 350px;
            padding: 20px;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            align-items: center;
        }

        .btn-new-task {
            background: #5aac44;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .btn-new-task:hover {
            background: #61bd4f;
        }

        .columnas {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding-bottom: 20px;
            overflow-x: hidden;
        }

        .column {
            background: #3A3A3A;
            border-radius: 8px;
            flex: 1 1 300px;
            max-width: 400px;
            padding: 15px;
        }

        .column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #555;
        }

        .column-header h3 {
            margin: 0;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .column-header h3 i {
            margin-right: 8px;
        }

        .badge {
            background: #444;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .column-content {
            min-height: 100px;
        }

        .card {
            background: #444;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            position: relative;
        }

        .card:hover {
            background: #555;
        }

        .card.completed {
            opacity: 0.7;
            border-left: 4px solid #5aac44;
        }

        .card.in-progress {

            opacity: 0.7;
            border-left: 4px solid #2196f3;

        }

        .card.pending {

            opacity: 0.7;
            border-left: 4px solid #ffeb3b;

        }

        .card-priority {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .priority-alta {
            background: #ff6b6b;
        }

        .priority-media {
            background: #ffa500;
        }

        .priority-baja {
            background: #4CAF50;
        }

        .card-due-date {
            font-size: 12px;
            color: #ccc;
            display: flex;
            align-items: center;
            margin-top: 8px;
        }

        .card-due-date i {
            margin-right: 5px;
        }

        @media (max-width: 900px) {
            .estilo {
                
                margin-left: 100px;
            
            }
        }
    </style>
</body>

</html>