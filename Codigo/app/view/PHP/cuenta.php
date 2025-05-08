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
            // Definir la carpeta donde se guardará la imagen
            $directorio = __DIR__ . '/../Imagenes/';
            $nombre_imagen = uniqid() . '_' . basename($imagen_perfil['name']);  // Generar un nombre único para la imagen
            $ruta_imagen = $directorio . $nombre_imagen;

            // Mover el archivo a la carpeta deseada
            if (move_uploaded_file($imagen_perfil['tmp_name'], $ruta_imagen)) {
                // Si la imagen se subió correctamente, actualizar la base de datos
                $usuarioController->modificarUsuario($usuario->getIdUsuario(), $nuevo_nombre_usuario, $correo, $contraseña, $ruta_imagen);
                echo "Imagen subida correctamente: " . $ruta_imagen;
                // Actualizar la sesión con el nuevo nombre de usuario si ha cambiado
                if ($nuevo_nombre_usuario !== $nombre_usuario) {
                    $_SESSION['nombre_usuario'] = $nuevo_nombre_usuario;
                }
                // Redirigir a la página de cuenta
                header("Location: cuenta.php");
                exit();
            } else {
                $mensaje_error = "Hubo un error al subir la imagen.";
            }
        } else {
            // Si no se sube una nueva imagen, simplemente modificar los otros datos
            $usuarioController->modificarUsuario($usuario->getIdUsuario(), $nuevo_nombre_usuario, $correo, $contraseña);
            // Redirigir a la página de cuenta
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
        <h1>Datos personales</h1>
        <form action="cuenta.php" method="POST">
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
                    <img src="<?= $usuario->getImagen() ?>" alt="Foto de perfil" width="100">
                <?php endif; ?>
                <br>
                <input type="file" name="imagen_perfil">
            </div>

            <br>
            <div>
                <b>Contraseña actual:</b><br>
                <input type="password" name="contraseña_actual">
            </div>

            <!-- Mostrar la foto de perfil actual si existe -->


            <!-- Mostrar el mensaje de error solo si hay uno -->
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


</html>