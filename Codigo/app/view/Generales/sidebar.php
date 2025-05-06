<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(MODEL . 'Tarea.php');

// Obtener las tareas del usuario desde el controlador
if ((isset($_SESSION['nombre_usuario']))) {
    $tareaController = new TareaController();
    $id_usuario = $usuario->getIdUsuario();  // Asegúrate de tener el ID del usuario en la sesión
    $tareas = $tareaController->getTareasByUser($id_usuario);  // Método que obtiene las tareas de la base de datos

}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tareas</title>
    <style>
        .sidebar {
            width: 240px;
            height: 100%;
            position: absolute;
            top: 47px;
            left: -240px;
            background-color: #2B2B2B;
            padding-top: 20px;
            border-right: 1px solid #414548;
            z-index: 5;
            transition: left 0.3s ease-in-out;

        }



        .content {
            position: relative;
            left: 240px;
            flex-grow: 1;
            padding-top: 30px;
            transition: left 0.3s ease in-out;
            /* transition: margin-left 0.3s ease; */
            left: 240;
        }



        .sidebar.open {
            left: 0;
            /* Cuando se activa, se mueve a la izquierda */
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            border-bottom: 1px solid #414548;
        }

        .sidebar a:hover {
            background-color: #ddd;
        }

        .texto {

            text-align: center;

        }

        #botonAbrir {
            position: fixed;
            left: 0;
            /* Posición inicial a la izquierda de la página */
            top: 50%;
            transform: translateY(-50%);
            padding: 10px 15px;
            cursor: pointer;
            z-index: 1100;
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            transition: left 0.3s ease-in-out;
            /* Transición suave para el movimiento */
        }

        .sidebar.open+#botonAbrir {
            left: 240px;
        }
    </style>
</head>

<body>
    <div class="sidebar open">
        <h2 class="texto">Mis Tareas</h2>
        <?php
        if (!empty($tareas)) {
            foreach ($tareas as $tarea) {
                // Cada tarea es un enlace que llevará a una página de detalles o edición de la tarea
                echo '<a href="detalleTarea.php?id=' . $tarea->getIdTarea() . '">' . htmlspecialchars($tarea->getTitulo()) . '</a>';
            }
        } else if (!isset($_SESSION['nombre_usuario'])) {
            echo '<p class="texto">No has iniciado sesión</p>';
        } else {
            echo '<p class="texto">No tienes tareas pendientes</p>';
        }
        ?>
    </div>
    <button id="botonAbrir">
        ←
    </button>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener el botón de toggle y la barra lateral
        const toggleButton = document.getElementById("botonAbrir");
        const sidebar = document.querySelector(".sidebar");
        const mainContent = document.querySelector(".content");

        // Agregar un evento de clic al botón para alternar la clase "open" en la barra lateral
        toggleButton.addEventListener("click", () => {
            sidebar.classList.toggle("open"); // Alterna la visibilidad de la barra lateral
            mainContent.classList.toggle("sidebar-open"); // Alterna el estado del contenido principal (si aplica)

            if (sidebar.classList.contains("open")) {
                toggleButton.textContent = "←"; // Cambiar a "-" cuando el sidebar está abierto
            } else {
                toggleButton.textContent = "→"; // Cambiar a "+" cuando el sidebar está cerrado
            }
        });
    });
</script>

</html>