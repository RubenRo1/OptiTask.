<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'Usuario.php');

class UsuarioController
{

    public function getAllUsers()
    {
        // Si decides implementarlo en el modelo más adelante
        return Usuario::getAllUsers();
    }

    public function getUserByName($nombre)
    {
        return Usuario::getUserByName($nombre);
    }

    public function getUserById($id_usuario)
    {
        return Usuario::getUserById($id_usuario);
    }

    public function crearUsuario($nombre, $email, $contraseña)
    {

        if (Usuario::UsuarioExiste($nombre)) {

            return false;
        }

        $nuevoUsuario = new Usuario();
        $nuevoUsuario->setNombre($nombre);
        $nuevoUsuario->setEmail($email);
        $nuevoUsuario->setContraseña($contraseña);

        $nuevoUsuario->create();

        return true;
    }

    public function modificarUsuario($id_usuario, $nuevoNombre, $nuevoEmail, $nuevaContraseña, $imagen = null)
    {
        // Obtener el usuario actual desde la base de datos
        $usuario = Usuario::getUserById($id_usuario);

        if (!$usuario) {
            return false; // No existe el usuario actual
        }

        // Si el nuevo nombre ya existe y no es el mismo que el actual, no permitir
        if (Usuario::UsuarioExiste($nuevoNombre) && $nuevoNombre !== $usuario->getNombre()) {
            return false; // El nombre está en uso por otro usuario
        }

        // Actualizar campos

        $usuario->setNombre($nuevoNombre);
        $usuario->setEmail($nuevoEmail);
        $usuario->setContraseña($nuevaContraseña);
        $usuario->setImagen($imagen); // Si se proporciona una nueva imagen

        $usuario->update();
    }

    //Comprobar si el email de usuario ya existe
    public function emailExiste($email)
    {
        return Usuario::EmailExiste($email);
    }


    // Eliminar usuario
    public function eliminarUsuario($id_usuario)
    {
        $usuario = Usuario::getUserById($id_usuario);
        if ($usuario) {
            $usuario->delete();
        }
    }


    // Modificar el nombre del usuario
    public function modificarNombre($nombre, $nuevoNombre)
    {
        $usuario = Usuario::getUserByName($nombre);
        if ($usuario) {
            $usuario->setNombre($nuevoNombre);
            $usuario->update();
        }
    }

    // Modificar el email del usuario
    public function modificarEmail($id_usuario, $nuevoEmail)
    {
        $usuario = Usuario::getUserById($id_usuario);
        if ($usuario) {
            $usuario->setEmail($nuevoEmail);
            $usuario->update();
        }
    }

    // Modificar la contraseña del usuario
    public function modificarContraseña($id_usuario, $nuevaContraseña)
    {

        $usuario = Usuario::getUserById($id_usuario);
        if ($usuario) {
            $usuario->updatePassword($nuevaContraseña);
        }
    }

    // Modificar la imagen del usuario
    public function modificarImagen($id_usuario, $nuevaImagen)
    {
        $usuario = Usuario::getUserById($id_usuario);
        if ($usuario) {
            $usuario->setImagen($nuevaImagen);
            $usuario->update();
        }
    }
}
