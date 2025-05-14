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
        $usuariosCompartidos = [];

        if ($Tareas && $Tareas->getIdUsuario() == $usuario->getIdUsuario()) {
            $compartidas = Compartidas::getByTarea($tareaId);
            foreach ($compartidas as $compartida) {
                $usuarioDestino = $usuarioController->getUserById($compartida->getUsuario_Destino());
                if ($usuarioDestino) {
                    $usuariosCompartidos[] = $usuarioDestino->getNombre();
                }
            }
        }
    }
} else {
    // Si no se pasa el ID, redirigir o mostrar un error
    $cont = 1;  // Establecer $cont = 1 si no se pasa el ID
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
                    <h2><?php echo htmlspecialchars($Tareas->getTitulo()); ?></h2>
                    <div id="countdown" style="font-size: 16px; color: #f39c12; margin-top: 5px;"></div>
                    <p><?php echo nl2br(str_replace(" ", "&nbsp;", htmlspecialchars($Tareas->getDescripcion()))); ?></p>
                    <p><strong>Fecha de entrega:</strong> <?php echo htmlspecialchars($Tareas->getFechaLimite()); ?></p>
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
                    <form method="POST" action="eliminar_subtareas_completadas.php" onsubmit="return confirm('¿Estás seguro de que deseas eliminar todas las subtareas completadas?');">
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
                        <?php foreach ($usuariosCompartidos as $nombreUsuario): ?>
                            <li><?php echo "- " . htmlspecialchars($nombreUsuario); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
<!-- Al poner el script no va genera -->
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
</script>

<style>
    .content-tareas {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        margin: 70px 10%;
        width: 90%;
        gap: 20px;

    }

    .tarea-detalle {
        background-color: #34495E;
        border: 1px solid #39424A;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: white;
        max-height: 600px;
        /* Limitar la altura de los contenedores */
        overflow: auto;
        /* Si el contenido excede la altura, muestra una barra de desplazamiento */
        width: 45%;
        transition: transform 0.3s ease;
        /* Ajusta los anchos a un 45% */

    }



    /* Evitar que el contenido se desborde y añadir barra de desplazamiento si es necesario */
    .tarea-detalle p,
    .subtareas p,
    .comentarios-chat p {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .tarea-detalle p {
        /* white-space: pre; */
        /* Respeta todos los espacios y saltos originales */
        overflow-x: auto;
        /* Permite scroll horizontal si es necesario */
        word-break: keep-all;
        /* Evita la división de palabras/palabras largas */
        font-family: monospace;
        /* Usa fuente monoespaciada para consistencia */
        font-size: 16px;
        /* Establece un tamaño de fuente fijo */
        letter-spacing: 0;
        /* Elimina espaciado adicional entre letras */
        line-height: 1.2;
        /* Controla el espacio entre líneas */
        max-width: 100%;
        /* Asegura que no exceda el ancho del contenedor */
        display: block;
        /* Mejor control del flujo */
    }

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


    .tooltip {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        left: 50%;
        bottom: 100%;
        transform: translateX(-50%);
        background-color: #2C3E50;
        border: 1px solid #34495E;
        padding: 15px;
        border-radius: 8px;
        min-width: 200px;
        max-width: 300px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 1001;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        max-height: 300px;
        overflow-y: auto;
        text-overflow: ellipsis;
        white-space: nowrap;

    }

    .tooltip h4 {
        margin: 0 0 10px 0;
        color: #ecf0f1;
        font-size: 16px;
    }

    .tooltip ul {
        max-height: 200px;
        overflow-y: auto;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .tooltip li {
        padding: 5px 0;
        border-bottom: 1px solid #3d4a52;
        color: #bdc3c7;
        font-size: 14px;
    }

    .tooltip li:last-child {
        border-bottom: none;
    }

    /* Scrollbar para la lista de usuarios */
    .tooltip ul::-webkit-scrollbar {
        width: 6px;
    }

    .tooltip ul::-webkit-scrollbar-track {
        background: #34495E;
    }

    .tooltip ul::-webkit-scrollbar-thumb {
        background: #7f8c8d;
        border-radius: 3px;
    }

    /* Mostrar la ventana emergente cuando el mouse pasa por encima */
    .compartida-icon {

        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #3498db;
        padding: 8px 12px;
        border-radius: 20px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;

    }

    .compartida-icon:hover .tooltip {
        visibility: visible;
        opacity: 1;
        margin-left: 10px;
    }

    .compartida-icon:hover+.tooltip {
        display: block;
    }

    .general h2 {

        text-align: center;
        margin-top: 20px;
    }

    .general h3 {

        text-align: center;
        margin-top: 20px;

    }

    .usuarios-compartidos {
        flex: 1;
        background-color: #34495E;
        padding: 15px;
        border-radius: 8px;
        color: white;
    }

    .usuarios-compartidos-container {
        position: absolute;
        right: 20px;
        bottom: 10px;
        z-index: 1000;
    }

    .usuarios-compartidos h4 {
        margin-bottom: 10px;
        text-align: center;
    }


    .comentarios-chat {
        flex: 1;
        background-color: #34495E;
        padding: 15px;
        border-radius: 8px;
        color: white;
        max-width: 310px;
        width: 40%;
        margin: 0;
        transition: transform 0.3s ease;
    }

    .comentarios-chat h3 {
        margin-bottom: 20px;
        text-align: center;
    }

    .comentarios-lista {
        margin-bottom: 20px;
        max-height: 355px;
        overflow-y: scroll;
        /* overflow: hidden; */


    }

    .comentarios-lista::-webkit-scrollbar,
    .tarea-detalle::-webkit-scrollbar,
    .subtareas-lista::-webkit-scrollbar,
    .tooltip::-webkit-scrollbar,
    .comentarios-chat::-webkit-scrollbar {
        display: none;
        /* Oculta la barra de desplazamiento */
    }

    .comentario {
        background-color: #2C3E50;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        word-break: break-word;

        white-space: normal;
    }

    .comentario strong {
        display: block;
        font-size: 0.9rem;
        /* Comentarios un poco más pequeños */
    }

    .comentario-fecha {
        font-size: 0.75rem;
        /* Más pequeño para la fecha */
        color: #bdc3c7;
        /* Color más claro para diferenciar */
        display: block;
        margin-bottom: 4px;
    }

    textarea {
        width: 90%;
        height: 100px;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        background-color: #34495E;
        color: white;
        margin-bottom: 10px;


    }

    button {
        background-color: #1ABC9C;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #16A085;
    }

    .subtareas {
        background-color: #34495E;
        border: 1px solid #39424A;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: white;
        max-height: 600px;
        overflow-y: auto;
        width: 15%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        transition: transform 0.3s ease;
    }

    .subtareas ul {
        list-style: none;
        padding-left: 0;
    }

    .subtareas li {
        margin-bottom: 6px;
        background-color: #34495E;
        border-radius: 6px;
        padding: 0;
    }

    .subtareas li:hover {
        background-color: #3c5a73;
    }

    .subtareas input[type="checkbox"] {
        transform: scale(1.2);
        cursor: pointer;
    }

    .subtareas strong {
        font-size: 0.85rem;
        color: #bdc3c7;
        white-space: nowrap;
    }

    .subtareas .estado-icono {
        margin-left: auto;
        font-size: 1rem;
    }

    .subtareas .estado-icono.completado {
        color: #2ecc71;
    }

    .subtareas .estado-icono.pendiente {
        color: #f1c40f;
    }

    .subtarea-form {
        display: block;
        margin: 0;

    }

    .subtareas form input[type="text"] {
        width: 100%;
        /* padding: 10px; */
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #34495E;
        color: white;
        /* margin-bottom: 10px; */
        font-size: 14px;
        /* Asegura que el texto sea legible */
        height: 30px;
        /* Añade altura similar al textarea */
        resize: none;
        /* Deshabilita el cambio de tamaño */
    }

    .subtareas form button {
        margin-top: 10px;
        width: 100%;
        padding: 5px;
        border: none;
        background-color: #1ABC9C;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .subtareas form button:hover {
        background-color: #16A085;
    }

    /* Estilo para el botón de borrar subtareas completadas */
    .subtareas form button.borrar-subtareas {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 5px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 6px;

    }

    /* Cambio de color al pasar el cursor */
    .subtareas form button.borrar-subtareas:hover {
        background-color: #c0392b;
        /* Rojo más oscuro cuando se pasa el mouse */
    }

    .subtarea-contenido {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
    }

    .subtarea-izquierda {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-grow: 1;
        overflow: hidden;
    }

    .subtarea-izquierda span {
        white-space: normal;
        word-break: break-word;
        color: white;
    }

    .subtarea-derecha {
        flex-shrink: 0;
        margin-left: 10px;
    }

    .estado-icono {
        font-size: 1rem;
    }

    .estado-icono.completado {
        color: #2ecc71;
    }

    .estado-icono.pendiente {
        color: #f1c40f;
    }

    .subtareas-lista {
        max-height: 410px;
        overflow-y: auto;
        margin-bottom: 20px;
    }

    .tarea-detalle:hover,
    .subtareas:hover,
    .comentarios-chat:hover {

        transform: scale(1.01);

    }

    .compartida-icon:hover {
        transform: scale(1.05);
        background-color: #2980b9;
    }
</style>

<!-- Asegúrate de que FontAwesome esté incluido en tu proyecto para usar los iconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>