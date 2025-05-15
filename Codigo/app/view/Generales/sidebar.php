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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .sidebar {
            position: fixed;
            top: 47px;
            /* Altura del header */
            left: -240px;
            bottom: 0;
            width: 240px;
            background-color: #2B2B2B;
            border-right: 1px solid #414548;
            box-shadow: 1px 0 0 #414548;
            /* Refuerza el borde */
            overflow-y: auto;
            z-index: 5;
            transition: left 0.3s ease-in-out;

        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #414548;
        }



        .content {
            position: relative;
            flex-grow: 1;
            padding-top: 30px;
            transition: left 0.3s ease in-out;
            /* left: 240; */
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
            border-bottom: 1px solid #414548;
            min-height: 50px;
            /* Altura fija para el contenedor */
            box-sizing: border-box;
            /* Incluye padding en la altura total */
        }

        .titulo-tarea {
            flex-grow: 1;
            max-width: 150px;
            word-break: break-word;
            min-height: 20px;
            /* Altura fija para evitar desplazamientos */
            line-height: 20px;
            /* Centrar verticalmente el texto */
        }

        .contenedor-botones {
            display: flex;
            gap: 5px;
            /* Espacio entre botones */
        }


        .contenedor:hover {
            background-color: #3a3a3a;

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
            transition: left 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        /* Estilo para ambos botones */
        .boton-eliminar,
        .boton-compartir {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            padding: 4px 6px;
            border-radius: 3px;
            transition: all 0.2s;
            height: 24px;
            /* Altura fija para los botones */
            width: 24px;
            /* Ancho fijo para los botones */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .boton-eliminar,
        .boton-compartir {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            padding: 4px 6px;
            border-radius: 3px;
            transition: all 0.2s;
        }

        .boton-eliminar {
            color: #e74c3c;
        }

        .boton-eliminar:hover {
            color: #c0392b;
            background: rgba(231, 76, 60, 0.1);
        }

        /* Estilo específico para compartir */
        .boton-compartir {
            color: #2ecc71;
        }

        .contenedor:hover .boton-eliminar,
        .contenedor:hover .boton-compartir {
            display: inline-block;
        }

        .boton-compartir:hover {
            color: #27ae60;
            background: rgba(46, 204, 113, 0.1);
        }

        .sidebar.open+#botonAbrir {
            left: 240px;
            transform: rotate(180deg);
            /* Rota el botón */
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

        #overlayFondo {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 100vw;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 4;
            /* Debajo de sidebar (z-index: 5) */
        }

        /* Mostrar fondo oscuro cuando sidebar esté abierta en móviles */
        @media (max-width: 768px) {
            .sidebar.open~#overlayFondo {
                display: block;
            }

            .sidebar {
                left: -240px;
                /* Inicialmente cerrada en móviles */
            }

            .sidebar.open {
                left: 0;
                /* Abierta cuando se activa */
            }

            /* Asegúrate de que el botón de abrir esté visible */
            #botonAbrir {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .sidebar {
                left: 0;
                /* Siempre abierta en escritorio */
            }

            /* Oculta el botón de abrir en escritorio si no es necesario */
            #botonAbrir {
                display: none;
            }
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
                echo '<span class="titulo-tarea">' . htmlspecialchars($tarea->getTitulo()) . '</span>';
                echo '<div class="contenedor-botones">'; // Nuevo contenedor para los botones
                echo '<button class="boton-eliminar" onclick="event.stopPropagation(); eliminarTarea(' . $tarea->getIdTarea() . ')"><i class="fas fa-times"></i></button>';
                echo '<button class="boton-compartir" onclick="event.stopPropagation(); abrirPopup(' . $tarea->getIdTarea() . ', \'' . addslashes($tarea->getTitulo()) . '\')"><i class="fas fa-share-alt"></i></button>';
                echo '</div>';
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
        →
    </button>

    <div id="overlayFondo"></div>


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
    const sidebar = document.querySelector(".sidebar");
    const toggleButton = document.getElementById("botonAbrir");
    const overlayFondo = document.getElementById("overlayFondo");

    // Verificar si es un dispositivo móvil (ancho <= 768px)
    const isMobile = window.matchMedia("(max-width: 768px)").matches;

    // Si es móvil, cerrar la sidebar al inicio
    if (isMobile) {
        sidebar.classList.remove("open"); // Asegura que esté cerrada
    }

    // Botón para abrir/cerrar sidebar
    toggleButton.addEventListener("click", () => {
        sidebar.classList.toggle("open");
    });

    // Cerrar sidebar al hacer clic fuera (overlay)
    overlayFondo.addEventListener("click", () => {
        sidebar.classList.remove("open");
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

                setTimeout(() => {
                    cerrarPopup();

                    // Redirigir después de cerrar el popup
                    refrescarPagina(idTarea);
                }, 1500); // Espera 1.5 segundos para mostrar mensaje
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('resultadoCompartir').textContent = 'Error al compartir la tarea.';
            });
    });

    function refrescarPagina(idTarea) {
        if (window.location.pathname.endsWith('/detalleTarea.php')) {
            location.reload();
        } else {
            window.location.href = 'detalleTarea.php?id=' + encodeURIComponent(idTarea);
        }
    }

    function eliminarTarea(idTarea) {
        if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
            fetch('../Generales/eliminarTarea.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_tarea=' + encodeURIComponent(idTarea)
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    // Recargar la página para ver los cambios
                    location.reload(); // <-- Esta es la solución más simple
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la tarea');
                });
        }
    }
</script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>