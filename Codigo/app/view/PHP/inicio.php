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
    <link rel="stylesheet" href="../CSS/inicio.css">
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
</body>

</html>