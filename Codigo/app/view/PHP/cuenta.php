<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');

session_start();

// comprobamos si el usuario no está logeado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarioController = new UsuarioController();
$nombre_usuario = $_SESSION['nombre_usuario'];
$usuario = $usuarioController->getUserByName($nombre_usuario);

// Variable para el mensaje de error
$mensaje_error = '';

function comprobarContraseñaActual($contraseña_actual, $usuario)
{
    return $contraseña_actual === $usuario->getContraseña();
}

function cerrarSesion()
{
    session_destroy();
    header("Location: login.php");

    exit();
}

function borrarCuenta($usuarioController, $id_usuario)
{
    $usuarioController->eliminarUsuario($id_usuario);
    session_destroy();
    header("Location: login.php");

    exit();
}

if (isset($_POST['borrar_imagen'])) {
    if ($usuario->getImagen()) {
        $ruta_imagen = __DIR__ . '/../Imagenes/' . basename($usuario->getImagen());
        if (file_exists($ruta_imagen)) {
            unlink($ruta_imagen);
        }
        // Actualizar usuario sin imagen
        $usuarioController->modificarUsuario(
            $usuario->getIdUsuario(),
            $usuario->getNombre(),
            $usuario->getEmail(),
            $usuario->getContraseña(),
            null // Quitar imagen
        );
        header("Location: cuenta.php");
        exit();
    }
}

if (isset($_POST['modificar'])) {
    $contraseña_actual = $_POST['contraseña_actual'];
    $nuevo_nombre_usuario = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];  // La nueva contraseña
    $imagen_perfil = $_FILES['imagen_perfil'];

    // Verificar si la contraseña actual es correcta
    if (comprobarContraseñaActual($contraseña_actual, $usuario)) {

        // Si hay una nueva imagen
        if ($imagen_perfil['error'] === UPLOAD_ERR_OK) {
            // Validar que el archivo sea una imagen
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
            $tipo_archivo = mime_content_type($imagen_perfil['tmp_name']);

            // Verificar que el tipo MIME sea uno de los permitidos
            if (!in_array($tipo_archivo, $tipos_permitidos)) {
                $mensaje_error = "Solo se permiten imágenes (JPG, PNG o GIF).";
            } else {
                // Validar el tamaño del archivo (por ejemplo, máximo 2MB)
                $max_size = 2 * 1024 * 1024; // 2MB
                if ($imagen_perfil['size'] > $max_size) {
                    $mensaje_error = "El archivo es demasiado grande. El tamaño máximo permitido es 2MB.";
                } else {
                    // Si el archivo es válido, crear el nombre y las rutas
                    $nombre_imagen = uniqid() . '_' . basename($imagen_perfil['name']);
                    $directorio_fisico = __DIR__ . '/../Imagenes/';
                    $ruta_fisica = $directorio_fisico . $nombre_imagen;

                    // Ruta web (para guardar en BD y mostrar en navegador)
                    $ruta_web = '/OptiTask/Codigo/app/view/Imagenes/' . $nombre_imagen;

                    // Eliminar la imagen anterior si existe
                    if ($usuario->getImagen() && file_exists(__DIR__ . '/../Imagenes/' . basename($usuario->getImagen()))) {
                        unlink(__DIR__ . '/../Imagenes/' . basename($usuario->getImagen())); // Elimina la imagen antigua
                    }

                    // Mover la imagen al directorio de destino
                    if (move_uploaded_file($imagen_perfil['tmp_name'], $ruta_fisica)) {
                        $usuarioController->modificarUsuario(
                            $usuario->getIdUsuario(),
                            $nuevo_nombre_usuario,
                            $correo,
                            $contraseña,
                            $ruta_web
                        );
                        if ($nuevo_nombre_usuario !== $nombre_usuario) {
                            $_SESSION['nombre_usuario'] = $nuevo_nombre_usuario;
                        }
                        header("Location: cuenta.php");
                        exit();
                    } else {
                        $mensaje_error = "Hubo un error al subir la imagen.";
                    }
                }
            }
        } else {
            // Sin nueva imagen
            $usuarioController->modificarUsuario(
                $usuario->getIdUsuario(),
                $nuevo_nombre_usuario,
                $correo,
                $contraseña
            );
            header("Location: cuenta.php");
            exit();
        }
    } else {
        $mensaje_error = "La contraseña actual es incorrecta.";
    }
}


// borrar cuenta
if (isset($_POST['borrar_cuenta'])) {
    $contraseña_actual = $_POST['contraseña_actual'];

    if (comprobarContraseñaActual($contraseña_actual, $usuario)) {
        borrarCuenta($usuarioController, $usuario->getIdUsuario());
    } else {
        $mensaje_error = "La contraseña actual es incorrecta.";
    }
}

// cerrar sesión
if (isset($_POST['cerrar_sesion'])) {

    cerrarSesion($usuario);
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta</title>
    <!-- <link rel="stylesheet" href="../CSS/cuenta.css"> -->
</head>

<body>
    <?php include "../Generales/header.php" ?>
    <div class="content">
        <form action="cuenta.php" method="POST" enctype="multipart/form-data">
            <h1>Datos personales</h1>
            <div>
                <b>Nombre de usuario:</b><br>
                <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($usuario->getNombre()) ?>">
            </div>

            <div>
                <b>Correo:</b><br>
                <input type="email" name="correo" value="<?= htmlspecialchars($usuario->getEmail()) ?>">
            </div>

            <div>
                <b>Contraseña:</b><br>
                <input type="password" name="contraseña" value="<?= htmlspecialchars($usuario->getContraseña()) ?>">
            </div>

            <div>
                <b>Foto de perfil:</b><br>
                <?php if ($usuario->getImagen()): ?>
                    <div class="imagen-perfil-container">
                        <img class="imagen" src="<?= $usuario->getImagen() ?>" alt="Foto de perfil" width="100"><br><br>
                        <button type="submit" name="borrar_imagen" style="background-color: gray; color: white;">Borrar imagen</button>
                    </div>


                <?php endif; ?>
                <br>
                <input type="file" name="imagen_perfil" accept="image/jpeg, image/png, image/gif">
            </div>

            <br>
            <div>
                <b>Contraseña actual:</b><br>
                <input type="password" name="contraseña_actual">
            </div>

            <?php if ($mensaje_error) { ?>
                <p style="color:red;"><?= htmlspecialchars($mensaje_error) ?></p>
            <?php } ?>

            <br>
            <div>
                <?php if (($_SESSION['nombre_usuario']) != "admin"): ?>
                    <button type="submit" name="modificar" style="background-color: rgb(104,86,52); color: white">Modificar</button>
                <?php endif; ?>

            </div>
            <br>
            <div>
                <button type="submit" name="cerrar_sesion" style="background-color: red;  color: white">Cerrar sesión</button>
                <?php if (($_SESSION['nombre_usuario']) != "admin"): ?>
                    <button type="submit" name="borrar_cuenta" style="background-color: red;  color: white">Borrar cuenta</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>

<style>
    /* Mejoras en los elementos dentro del formulario */
    form {
        background-color: #3A3A3A;
        /* Fondo oscuro para el formulario */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 50px auto;
        font-size: 14px;


    }

    h1 {
        font-size: 1.4em;
        margin-bottom: 15px;
        text-align: center;
        color: #fff;
        /* Título blanco */
    }

    form div {
        margin-bottom: 10px;
    }

    form div b {
        font-size: 0.95em;
        color: #4CAF50;
        /* Color de las etiquetas (verde) */
        margin-bottom: 5px;
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form input[type="file"] {
        padding: 7px;
        font-size: 0.9em;
        border: 1px solid #555;
        /* Borde oscuro */
        border-radius: 4px;
        width: 100%;
        background-color: #4A4A4A;
        /* Fondo gris oscuro */
        color: white;
        /* Texto blanco */
        box-sizing: border-box;
        /* Evita que el padding afecte al ancho */
    }

    form input[type="text"]:focus,
    form input[type="email"]:focus,
    form input[type="password"]:focus {
        border-color: #4CAF50;
        /* Borde verde al enfocar */
        outline: none;
    }

    form input[type="file"] {
        padding: 5px;
        /* color-scheme: dark; */
    }

    form button {
        padding: 8px;
        font-size: 0.9em;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        color: white;
        margin-top: 8px;
        width: 100%;
        text-align: center;
    }

    /* Botones */
    form button[type="submit"]:hover {
        background-color: #45a049;
        /* Verde más oscuro al pasar el cursor */
    }

    form button[type="submit"]:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    form button[name="cerrar_sesion"] {
        background-color: #f44336;
        /* Rojo para cerrar sesión */
        margin-right: 10px;
    }

    form button[name="cerrar_sesion"]:hover {
        background-color: #e53935;
        /* Rojo más oscuro al pasar el cursor */
    }

    form button[name="borrar_cuenta"] {
        background-color: #f44336;
        /* Rojo para borrar cuenta */
    }

    form button[name="borrar_cuenta"]:hover {
        background-color: #e53935;
        /* Rojo más oscuro al pasar el cursor */
    }

    /* Mensajes de error y éxito */
    .error-message {
        color: #f44336;
        /* Rojo para errores */
        font-size: 0.5em;
        margin-top: 5px;
        text-align: center;
    }

    .success-message {
        color: #4CAF50;
        /* Verde para mensajes de éxito */
        font-size: 0.5em;
        margin-top: 10px;
        text-align: center;
    }

    /* Estilo para el mensaje de error que aparece en el formulario */
    p {
        color: #f44336;
        /* Color rojo para los errores */
        font-size: 0.9em;
        text-align: center;
        margin-top: 15px;
    }

    .imagen-perfil-container {
        margin-top: 20px;
        text-align: center;
    }

    .imagen-perfil-container img.imagen {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #3A7BFF;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    .imagen-perfil-container img.imagen:hover {
        transform: scale(1.05);
    }
</style>

</html>