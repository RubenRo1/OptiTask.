<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'TareaController.php');
require_once(CONTROLLER . 'CompartidasController.php');
require_once(MODEL . 'Compartidas.php');
require_once(MODEL . 'Tarea.php');

// Obtener las tareas del usuario desde el controlador
if ((isset($_SESSION['nombre_usuario']))) {



    $tareaController = new TareaController();
    $compartidasController = new CompartidasController();

    $id_usuario = $usuario->getIdUsuario();

    $tareas = $tareaController->getTareasByUser($id_usuario);  // Método que obtiene las tareas de la base de datos
    $tareasCompartidas = $compartidasController->obtenerCompartidasPorUsuarioDestino($id_usuario);

    if (isset($_GET['id'])) {
        $id_tarea = $_GET['id'];
        $Tareas = $tareaController->getTareaById($id_tarea);
    } else {
        $Tareas = null;
    }
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
            overflow-y: auto;

        }

        .sidebar::-webkit-scrollbar {
            display: none;
            /* Esto oculta la barra de desplazamiento */
        }



        .content {
            position: relative;
            left: 240px;
            flex-grow: 1;
            padding-top: 30px;
            transition: left 0.3s ease in-out;
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
            /* border-bottom: 1px solid #414548; */
        }

        .contenedor {

            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 10px;
            height: 20px;
            border-bottom: 1px solid #414548;
            letter-spacing: 1px;
            word-wrap: break-word;

        }

        .contenedor:hover {
            background-color: #ddd;

        }

        .contenedor:hover .boton-compartir {
            display: inline-block;
        }

        .texto {

            font-size: 22px;
            text-align: center;

        }

        #botonAbrir {
            position: fixed;
            left: 0;
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
        }

        .boton-compartir {
            display: none;
            background-color: #4CAF50;
            color: white;
            padding: 4px 7px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
        }

        .sidebar.open+#botonAbrir {
            left: 240px;
        }

        #popupCompartir {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #2b2b2b;
            padding: 25px 30px;
            border-radius: 10px;
            z-index: 999;
            width: 320px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        #popupCompartir h3 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #ffffff;
        }

        #popupCompartir input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #888;
            background-color: #1f1f1f;
            color: white;
        }

        #popupCompartir button {
            margin: 5px;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        #popupCompartir button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }

        #popupCompartir button[type="submit"]:hover {
            background-color: #45a049;
        }

        #popupCompartir button[type="button"] {
            background-color: #777;
            color: white;
        }

        #popupCompartir button[type="button"]:hover {
            background-color: #999;
        }

        #resultadoCompartir {
            margin-top: 10px;
            font-size: 14px;
            color: #80ff80;
        }
    </style>
</head>

<body>
    <div class="sidebar open">
        <h2 class="texto">Mis Tareas</h2>
        <?php
        if (!empty($tareas)) {
            foreach ($tareas as $tarea) {

                echo '<div class="contenedor" onclick="window.location.href=\'detalleTarea.php?id=' . $tarea->getIdTarea() . '\'" style="cursor:pointer;">';
                echo '<span style="display:block; max-width:150px; word-break:break-word;">' . htmlspecialchars($tarea->getTitulo()) . '</span>';
                echo '<button class="boton-compartir" onclick="event.stopPropagation(); abrirPopup(' . $tarea->getIdTarea() . ', \'' . addslashes($tarea->getTitulo()) . '\')">Compartir</button>';
                echo '</div>';
            }
        } else if (!isset($_SESSION['nombre_usuario'])) {
            echo '<p class="texto">No has iniciado sesión</p>';
        } else {
            echo '<p class="texto">No tienes tareas pendientes</p>';
        }
        ?>
        <h2 class="texto">Compartida Conmigo</h2>
        <?php
        if (!empty($tareasCompartidas)) {
            foreach ($tareasCompartidas as $compartida) {
                $tareaCompartida = $tareaController->getTareaById($compartida['id_tarea']);
                if ($tareaCompartida) {

                    echo '<div class="contenedor" onclick="window.location.href=\'detalleTarea.php?id=' . $tareaCompartida->getIdTarea() . '\'" style="cursor:pointer;">';
                    echo '<span style="display:block; max-width:150px; word-break:break-word;">' . htmlspecialchars($tareaCompartida->getTitulo()) . '</span>';
                    echo '</div>';
                }
            }
        } else {
            echo '<p class="texto">No tienes tareas compartidas</p>';
        }
        ?>
    </div>
    <button id="botonAbrir">
        ←
    </button>

    <div id="popupCompartir">
        <h3></h3>
        <form id="formCompartir">
            <input type="hidden" name="id_tarea" id="popupIdTarea">
            <label for="usuario">Nombre del usuario a compartir:</label><br>
            <input type="text" name="nombre_usuario_destino" id="popupUsuario" required><br><br>
            <button type="submit">Compartir</button>
            <button type="button" onclick="cerrarPopup()">Cancelar</button>
        </form>
        <div id="resultadoCompartir" style="margin-top: 10px; color: green;"></div>
    </div>

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

    function abrirPopup(idTarea, tituloTarea) {
        document.getElementById('popupIdTarea').value = idTarea;
        document.getElementById('popupCompartir').style.display = 'block';
        document.getElementById('popupFondo').style.display = 'block'; // Mostrar fondo
        document.getElementById('popupCompartir').querySelector('h3').textContent = 'Compartir tarea: ' + tituloTarea;
        document.getElementById('resultadoCompartir').textContent = '';
    }


    function cerrarPopup() {
        document.getElementById('popupCompartir').style.display = 'none';
        document.getElementById('popupFondo').style.display = 'none';
        document.getElementById('resultadoCompartir').textContent = ''; // Limpiar mensaje de resultado

    }

    document.getElementById('formCompartir').addEventListener('submit', function(e) {
        e.preventDefault(); // Evita el envío tradicional del formulario

        const idTarea = document.getElementById('popupIdTarea').value;
        const nombreUsuario = document.getElementById('popupUsuario').value;

        fetch('../Generales/procesarCompartir.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_tarea=' + encodeURIComponent(idTarea) + '&nombre_usuario_destino=' + encodeURIComponent(nombreUsuario)
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('resultadoCompartir').textContent = data;
                setTimeout(() => cerrarPopup(), 1500); // Cierra el popup después de mostrar el mensaje
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('resultadoCompartir').textContent = 'Error al compartir la tarea.';
            });
    });
</script>

</html>


































<!-- Respaldo por si acaso -->



<?php
// require_once(__DIR__ . '/../../../rutas.php');
// require_once(CONTROLLER . 'TareaController.php');
// require_once(MODEL . 'Tarea.php');

// // Obtener las tareas del usuario desde el controlador
// if ((isset($_SESSION['nombre_usuario']))) {
//     $tareaController = new TareaController();
//     $id_usuario = $usuario->getIdUsuario();  // Asegúrate de tener el ID del usuario en la sesión
//     $tareas = $tareaController->getTareasByUser($id_usuario);  // Método que obtiene las tareas de la base de datos

// }

?>

<!-- <!DOCTYPE html>
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

        .boton {
            padding: 10px;
            width: 100%;
            border: none;
            background-color: #2B2B2B;
            text-align: left;
            font-size: 17px;
            color: white;
            display: block;
            border-bottom: 1px solid #414548;
        }

        .sidebar button:hover {
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
        <h2 class="texto">Mis Tareas</h2> -->
<?php
// if (!empty($tareas)) {
//     foreach ($tareas as $tarea) {
//         // Cada tarea es un enlace que llevará a una página de detalles o edición de la tarea
//         echo '<button class="boton" data-tarea-id="' . $tarea->getIdTarea() . '">' . htmlspecialchars($tarea->getTitulo()) . '</button>';
//     }
// } else if (!isset($_SESSION['nombre_usuario'])) {
//     echo '<p class="texto">No has iniciado sesión</p>';
// } else {
//     echo '<p class="texto">No tienes tareas pendientes</p>';
// }
?>
<!-- </div>
    <button id="botonAbrir">
        ←
    </button>
</body> -->

<!-- <script>
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


    const tareaButtons = document.querySelectorAll('.yboton');
    tareaButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tareaId = this.getAttribute('data-tarea-id');
            // Redirigir a la página de detalle de la tarea
            window.location.href = 'detalleTarea.php?id=' + tareaId;
        });
    });
</script>

</html> -->