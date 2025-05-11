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

session_start();

if (!isset($_SESSION['nombre_usuario'])) {

    $usuario = null;
    $nombre_usuario = null;
} else {

    $usuarioController = new UsuarioController();
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $usuario = $usuarioController->getUserByName($nombre_usuario);
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $tareaId = $_GET['id'];

    // Obtener la tarea usando el controlador
    $tareaController = new TareaController();
    $Tareas = $tareaController->getTareaById($tareaId); // Asumimos que este método existe en tu controlador

    $compartidasController = new CompartidasController();

    $usuariosCompartidos = [];


    if ($Tareas && $Tareas->getIdUsuario() == $usuario->getIdUsuario()) {
        $compartidas = Compartidas::getByTarea($tareaId);
        $usuariosCompartidos = [];

        foreach ($compartidas as $compartida) {
            $usuarioDestino = $usuarioController->getUserById($compartida->getUsuario_Destino());
            if ($usuarioDestino) {
                $usuariosCompartidos[] = $usuarioDestino->getNombre();
            }
        }
    }


    $cont = 0;
} else {
    // Si no se pasa el ID, redirigir o mostrar un error
    $cont = 1;
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
    <?php
    include "../Generales/header.php";
    ?>
    <?php include "../Generales/sidebar.php"; ?>

    <?php if ($cont == 1) : ?>
        <div class="content general">
            <h3>Aqui veras la informacion de la tarea.</h2>
        </div>
    <?php else: ?>
        <div class="content">
            <div class="content-tareas">
                <!-- <div class="general"> -->
                <div class="tarea-detalle">
                    <h2><?php echo htmlspecialchars($Tareas->getTitulo()); ?></h2>
                    <p><?php echo htmlspecialchars($Tareas->getDescripcion()); ?></p>
                    <p><strong>Fecha de entrega:</strong> <?php echo htmlspecialchars($Tareas->getFechaLimite()); ?></p>
                    <p><strong>Estado:</strong> <?php echo htmlspecialchars($Tareas->getEstado()); ?></p>

                    <?php if (!empty($usuariosCompartidos)) : ?>
                        <!-- Icono de las personas compartidas -->
                        <div class="compartida-icon" id="compartida-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <!-- Ventana emergente (tooltip) de usuarios compartidos -->
                        <div class="tooltip" id="tooltip">
                            <h4>Compartida con:</h4>
                            <ul>
                                <?php foreach ($usuariosCompartidos as $nombreUsuario) : ?>
                                    <li><?php echo "- " . htmlspecialchars($nombreUsuario); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>


                <!-- </div> -->
                <!-- Sección de comentarios (Chat) -->
                <div class="comentarios-chat">
                    <h3>Comentarios</h3>
                    <div class="comentarios-lista">

                        <?php
                        $comentarioController = new ComentarioController();
                        $comentarios = $comentarioController->getComentariosByTarea($tareaId);
                        // Asegúrate de tener el controlador de usuarios
                        // $usuarioController = new UsuarioController();
                        if (empty($comentarios)) {
                            echo "<div style='text-align: center;'>
                                <i class='fas fa-comment-slash' style='font-size: 2rem; color: #bdc3c7;'></i>
                                <p style='color: #bdc3c7;'>No hay comentarios aún.</p>
                                <p style='color: #bdc3c7;'>¡Sé el primero en comentar!</p>
                                </div>";
                        } else {

                            foreach ($comentarios as $comentario) {
                                // Obtener el usuario correspondiente al id_usuario
                                $usuarioComentario = $usuarioController->getUserById($comentario['id_usuario']);
                                if ($usuarioComentario) {
                                    $nombreUsuario = $usuarioComentario->getNombre();
                                } else {
                                    $nombreUsuario = "Usuario desconocido";
                                }
                                echo "<div class='comentario'>";
                                // Mostrar la fecha del comentario
                                echo "<span class='comentario-fecha'>" . htmlspecialchars($comentario['fecha_comentario']) . "</span>";
                                echo "<strong style='display: inline;'>" . htmlspecialchars($nombreUsuario) . ": </strong>" . htmlspecialchars($comentario['contenido']);
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                    <!-- Formulario para agregar un nuevo comentario -->
                    <form method="POST" action="agregar_comentario.php">
                        <input type="hidden" name="id_tarea" value="<?php echo $tareaId; ?>">
                        <textarea name="comentario" placeholder="Comenta..." required></textarea>
                        <button type="submit">Enviar</button>
                    </form>
                </div>
            </div>

        </div>
    <?php endif; ?>
</body>

<style>
    .content-tareas {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        margin: 70px 10%;
        width: 90%;
        gap: 20px;

    }

    .tarea-detalle,
    .comentarios-chat {
        background-color: #2C3E50;
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
        /* Ajusta los anchos a un 45% */
    }



    /* Evitar que el contenido se desborde y añadir barra de desplazamiento si es necesario */
    .tarea-detalle p,
    .comentarios-chat p {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .comentarios-chat {
        width: 40%;
        margin: 0;
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
        display: none;
        position: absolute;
        transform: translateX(20px);
        background-color: #2C3E50;
        border: 2px solid #34495E;
        padding: 10px;
        border-radius: 5px;
        width: 200px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        z-index: 999;
    }

    .tooltip h4 {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .tooltip ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .tooltip li {
        font-size: 13px;
        margin-bottom: 5px;
    }

    /* Mostrar la ventana emergente cuando el mouse pasa por encima */
    .compartida-icon {
        width: 20px;
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
        max-width: 350px;
    }

    .comentarios-chat h3 {
        margin-bottom: 20px;
        text-align: center;
    }

    .comentarios-lista {
        margin-bottom: 20px;
        max-height: 300px;
        overflow-y: scroll;
        /* overflow: hidden; */


    }

    .comentarios-lista::-webkit-scrollbar,
    .tarea-detalle::-webkit-scrollbar,
    .comentarios-chat::-webkit-scrollbar {
        display: none;
        /* Oculta la barra de desplazamiento */
    }

    .comentario {
        background-color: #2C3E50;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;

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
</style>

<!-- Asegúrate de que FontAwesome esté incluido en tu proyecto para usar los iconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>