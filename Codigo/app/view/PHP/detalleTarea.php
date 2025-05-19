<?php

require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(MODEL . 'Tarea.php');
require_once(CONTROLLER . 'CompartidasController.php');
require_once(MODEL . 'Compartidas.php');
require_once(CONTROLLER . 'ComentarioController.php');
require_once(MODEL . 'Comentario.php');
require_once(CONTROLLER . 'SubtareaController.php');
require_once(MODEL . 'Subtarea.php');

session_start();

$usuarioQueCompartio = null;

if (!isset($_SESSION['nombre_usuario'])) {
    $usuario = null;
    $nombre_usuario = null;
} else {
    $usuarioController = new UsuarioController();
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $usuario = $usuarioController->getUserByName($nombre_usuario);
}

$cont = 0; // Variable para verificar si la tarea existe

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $tareaId = $_GET['id'];

    // Obtener la tarea usando el controlador
    $tareaController = new TareaController();
    $Tareas = $tareaController->getTareaById($tareaId); // Asumimos que este método existe en tu controlador

    // Verificar si la tarea existe
    if ($Tareas === null) {
        // Si la tarea no existe, establecer $cont = 1
        $cont = 1;
        
    } else {
        // Si la tarea existe, continuar con la lógica
        $compartidasController = new CompartidasController();

        // Obtener todas las comparticiones de la tarea una sola vez
        $compartidas = Compartidas::getByTarea($tareaId);

        $usuariosCompartidos = [];
        if ($Tareas && $Tareas->getIdUsuario() == $usuario->getIdUsuario()) {
            // Si es el dueño, listar a quiénes compartió
            foreach ($compartidas as $compartida) {
                $usuarioDestino = $usuarioController->getUserById($compartida->getUsuario_Destino());
                if ($usuarioDestino) {
                    $usuariosCompartidos[] = $usuarioDestino->getNombre();
                }
            }
        }

        $usuarioQueCompartio = null;
        $otrosUsuariosCompartidos = [];
        $permisoUsuario = null;

        if ($usuario) {
            // Si la tarea fue compartida contigo, obtener quien la compartió
            $compartidaConmigo = $compartidasController->obtenerCompartidasPorUsuarioYTarea($tareaId, $usuario->getIdUsuario());
            if ($compartidaConmigo) {
                $usuarioOrigen = $usuarioController->getUserById($compartidaConmigo->getUsuario_Origen());
                if ($usuarioOrigen) {
                    $usuarioQueCompartio = $usuarioOrigen->getNombre();
                }
            }

            // Obtener otros usuarios a quienes se compartió excluyendo al actual
            foreach ($compartidas as $compartida) {
                $idUsuarioDestino = $compartida->getUsuario_Destino();
                if ($idUsuarioDestino != $usuario->getIdUsuario()) {
                    $usuarioDestino = $usuarioController->getUserById($idUsuarioDestino);
                    if ($usuarioDestino) {
                        $otrosUsuariosCompartidos[] = $usuarioDestino->getNombre();
                    }
                }
            }

            $compartidaConmigo = $compartidasController->obtenerCompartidasPorUsuarioYTarea($tareaId, $usuario->getIdUsuario());
            if ($compartidaConmigo) {
                $permisoUsuario = $compartidaConmigo->getPermiso();  // Asumo que el objeto tiene este método
            } else if ($Tareas->getIdUsuario() == $usuario->getIdUsuario()) {
                // Si es dueño, permiso completo (por ejemplo, 'editar')
                $permisoUsuario = 'editar';
            } else {
                // Si no es dueño ni está compartida, permiso nulo o 'ninguno'
                $permisoUsuario = null;
            }
        }
    }
} else {

    $cont = 1;
}

if (isset($_POST["permisos"])) {

    $id_tarea = $_POST['id_tarea'] ?? null;
    $id_usuario_destino = $_POST['usuario_destino'] ?? null;
    $permiso = htmlspecialchars($_POST['permiso'] ?? '');
    $result = false;

    // 2) Validación mínima
    if (! $id_tarea || ! $id_usuario_destino || ! $permiso) {
        echo "<p style='color: red;'>Faltan datos para asignar el permiso.</p>";
    } else {
        require_once(CONTROLLER . 'CompartidasController.php');
        $controller = new CompartidasController();

        // 3) Procesamos y, aunque no devuelva booleano,
        //    asumimos que si no lanza excepción, fue OK.
        $controller->modificarPermiso($id_tarea, $id_usuario_destino, $permiso);
    }

    // 4) Redirigimos (PRG) para limpiar el POST y recargar
    header("Location: detalleTarea.php?id=" . urlencode($id_tarea));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../CSS/detalleTarea.css">
</head>

<body>
    <?php include "../Generales/header.php"; ?>
    <?php include "../Generales/sidebar.php"; ?>

    <?php if ($cont == 1 || $Tareas === null) : ?>
        <div class="content general">
            <h3>Aqui veras la informacion de la tarea.</h3>
        </div>
    <?php else: ?>
        <div class="content">
            <div class="content-tareas">
                <div class="tarea-detalle">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h2><?php echo htmlspecialchars($Tareas->getTitulo()); ?></h2>


                        <div style="display: flex; gap: 40px;">
                            <?php if (!empty($usuariosCompartidos)) : ?>
                                <a onclick="mostrarPopupPermisos()" class="botones">
                                    <i class="fas fa-user-shield"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ($permisoUsuario !== 'Leer'): ?>
                                <a href="editar_tarea.php?id=<?php echo $tareaId; ?>" class="botones">
                                    <i class="fas fa-pen"></i>
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div id="countdown" style="font-size: 16px; color: #f39c12; margin-top: 5px;"></div>
                    <p><?php echo nl2br(str_replace(" ", "&nbsp;", htmlspecialchars($Tareas->getDescripcion()))); ?></p>
                    <p><strong>Fecha de entrega:</strong> <?php echo date('d M', strtotime($Tareas->getFechaLimite())); ?></p>
                    <p><strong>Estado:</strong> <?php echo htmlspecialchars($Tareas->getEstado()); ?></p>

                </div>
                <div class="subtareas">
                    <h3>Subtareas</h3>
                    <div class="subtareas-lista">


                        <?php
                        // Obtener las subtareas asociadas a esta tarea
                        $subtareaController = new SubtareaController();
                        $subtareas = $subtareaController->obtenerSubtareasPorTarea($tareaId);

                        if (empty($subtareas)) {
                            echo "<p>No hay subtareas asignadas a esta tarea.</p>";
                        } else {
                            echo "<ul>";
                            foreach ($subtareas as $subtarea) {
                                $completado = $subtarea->getCompletado();
                                $checked = $completado ? "checked" : "";  // Si está completada, marcar el checkbox
                                echo "<li>";
                                echo "<form method='POST' action='actualizar_subtarea.php' class='subtarea-form'>";
                                echo "<input type='hidden' name='id_subtarea' value='" . $subtarea->getIdSubtarea() . "'>";
                                echo "<div class='subtarea-contenido'>";
                                echo "<div class='subtarea-izquierda'>";
                                echo "<input type='checkbox' name='completado' $checked onclick='this.form.submit()'> ";
                                echo "<span>" . htmlspecialchars($subtarea->getDescripcion()) . "</span>";
                                echo "</div>";
                                $icono = $completado
                                    ? "<i class='fas fa-check-circle estado-icono completado' title='Completada'></i>"
                                    : "<i class='fas fa-hourglass-half estado-icono pendiente' title='Pendiente'></i>";
                                echo "<div class='subtarea-derecha'>$icono</div>";

                                echo "</div>";
                                echo "</form>";
                                echo "</li>";
                            }
                            echo "</ul>";
                        }
                        ?>
                    </div>

                    <!-- Formulario para agregar una nueva subtarea -->
                    <form method="POST" action="agregar_subtarea.php">
                        <input type="hidden" name="id_tarea" value="<?php echo $tareaId; ?>">
                        <input type="text" name="descripcion" placeholder="Nueva subtarea" required>
                        <button type="submit">Agregar Subtarea</button>
                    </form>
                    <!-- Botón para borrar subtareas completadas -->
                    <form method="POST" action="eliminar_subtareas_completadas.php">
                        <input type="hidden" name="id_tarea" value="<?php echo $tareaId; ?>">
                        <button type="submit" class="borrar-subtareas">
                            Borrar Subtareas Completadas
                        </button>
                    </form>
                </div>
                <div class="comentarios-chat">
                    <h3>Comentarios</h3>
                    <div class="comentarios-lista">
                        <?php
                        $comentarioController = new ComentarioController();
                        $comentarios = $comentarioController->getComentariosByTarea($tareaId);
                        if (empty($comentarios)) {
                            echo "<div style='text-align: center;'>
                            <i class='fas fa-comment-slash' style='font-size: 2rem; color: #bdc3c7;'></i>
                            <p style='color: #bdc3c7;'>No hay comentarios aún.</p>
                            <p style='color: #bdc3c7;'>¡Sé el primero en comentar!</p>
                            </div>";
                        } else {
                            foreach ($comentarios as $comentario) {
                                $usuarioComentario = $usuarioController->getUserById($comentario['id_usuario']);
                                $nombreUsuario = $usuarioComentario ? $usuarioComentario->getNombre() : "Usuario desconocido";
                                echo "<div class='comentario'>";
                                echo "<span class='comentario-fecha'>" . htmlspecialchars($comentario['fecha_comentario']) . "</span>";
                                echo "<strong style='display: inline;'>" . htmlspecialchars($nombreUsuario) . ": </strong>" . htmlspecialchars($comentario['contenido']);
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                    <form method="POST" action="agregar_comentario.php">
                        <input type="hidden" name="id_tarea" value="<?php echo $tareaId; ?>">
                        <textarea name="comentario" placeholder="Comenta..." required></textarea>
                        <button type="submit">Enviar</button>
                    </form>
                </div>

            </div>

        </div>
    <?php endif; ?>
    <?php if (!empty($usuariosCompartidos)) : ?>
        <div class="usuarios-compartidos-container">
            <div class="compartida-icon">
                <i class="fas fa-users"></i>
                <span class="compartida-texto">Compartidos</span>
                <div class="tooltip">
                    <h4>Compartida con:</h4>
                    <ul>
                        <?php foreach ($compartidas as $compartida): ?>
                            <?php
                            $usuarioDestino = Usuario::getUserById($compartida->getUsuario_Destino());
                            if ($usuarioDestino):
                            ?>
                                <li>
                                    <?php echo "- " . htmlspecialchars($usuarioDestino->getNombre()) . " (" . htmlspecialchars($compartida->getPermiso()) . ")"; ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($usuarioQueCompartio !== null): ?>
        <div class="usuarios-compartidos-container">
            <div class="compartida-icon">
                <i class="fas fa-user-friends"></i>
                <span class="compartida-texto">Compartida por</span>
                <div class="tooltip">
                    <h4>Compartida por:</h4>
                    <ul>
                        <li><?php echo htmlspecialchars($usuarioQueCompartio); ?></li>
                    </ul>

                    <?php if (!empty($otrosUsuariosCompartidos)): ?>
                        <h4>También compartido con:</h4>
                        <ul>
                            <?php foreach ($compartidas as $compartida): ?>
                                <?php
                                $usuarioDestino = Usuario::getUserById($compartida->getUsuario_Destino());
                                if ($usuarioDestino && $compartida->getUsuario_Destino() != $usuario->getIdUsuario()):
                                ?>
                                    <li>
                                        <?php echo "- " . htmlspecialchars($usuarioDestino->getNombre()) . " (" . htmlspecialchars($compartida->getPermiso()) . ")"; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div id="popupPermisos" class="popup">
        <div class="popup-content">
            <span class="close" onclick="cerrarPopupPermisos()">&times;</span>
            <h3>Añadir Permisos</h3>
            <form method="POST">
                <input type="hidden" name="id_tarea" value="<?php echo $tareaId; ?>">
                <label for="usuario_destino">Seleccionar usuario:</label>
                <div class="custom-select-wrapper">
                    <select name="usuario_destino" required>
                        <?php
                        // Obtenemos los usuarios que ya tienen permisos en la tarea
                        foreach ($compartidas as $compartida) {
                            $idUsuarioDestino = $compartida->getUsuario_Destino(); // getter para id_usuario_destino
                            $usuarioDestino = Usuario::getUserById($idUsuarioDestino); // función que obtenga el usuario por id
                            if ($usuarioDestino) {
                                echo "<option value='" . $usuarioDestino->getIdUsuario() . "'>" . htmlspecialchars($usuarioDestino->getNombre()) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <i class="fas fa-chevron-down"></i>
                </div><br><br>
                <label for="permiso">Permiso:</label>
                <div class="custom-select-wrapper">
                    <select name="permiso" id="popupPermiso" required>
                        <option value="Editar">Editar</option>
                        <option value="Leer">Leer</option>
                    </select>
                    <i class="fas fa-chevron-down"></i>
                </div><br><br>
                <button type="submit" name="permisos">Asignar</button>
            </form>
        </div>
    </div>

    <?php

    ?>

    <div id="popupFondo" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background-color:rgba(0,0,0,0.5); 
    z-index:998;"></div>
</body>
<script>
    // Solo ejecutar el script si estamos en la vista de detalle de tarea
    <?php if ($cont != 1 && $Tareas !== null) : ?>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaLimiteTexto = "<?php echo $Tareas->getFechaLimite(); ?>".replace(" ", "T");
            const fechaLimite = new Date(fechaLimiteTexto).getTime();
            const countdownElement = document.getElementById("countdown");

            if (countdownElement && !isNaN(fechaLimite)) {
                function actualizarCountdown() {
                    const ahora = new Date().getTime();
                    const tiempoRestante = fechaLimite - ahora;

                    if (tiempoRestante <= 0) {
                        countdownElement.innerHTML = "¡La fecha límite ha expirado!";
                        countdownElement.style.color = "red";
                        clearInterval(intervalo);
                        return;
                    }

                    const dias = Math.floor(tiempoRestante / (1000 * 60 * 60 * 24));
                    const horas = Math.floor((tiempoRestante % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
                    const segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);

                    countdownElement.innerHTML = `Tiempo restante: ${dias}d ${horas}h ${minutos}m ${segundos}s`;
                    countdownElement.style.color = "#f39c12";
                }

                const intervalo = setInterval(actualizarCountdown, 1000);
                actualizarCountdown();
            }
        });
    <?php endif; ?>

    function mostrarPopupPermisos() {
        document.getElementById("popupPermisos").style.display = "block";
        document.getElementById('popupFondo').style.display = 'block';
    }

    function cerrarPopupPermisos() {
        document.getElementById("popupPermisos").style.display = "none";
        document.getElementById('popupFondo').style.display = 'none';
    }
</script>

<!-- Asegúrate de que FontAwesome esté incluido en tu proyecto para usar los iconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>