<?php

require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');


/**
 * Clase que representa un usuario en el sistema
 *
 * @package Usuario
 * @author OptiTask <optitask@gmail.com>
 */
class Usuario
{
    /** @var int Id del pedido */
    private $id_usuario;
    /** @var string Nombre del usuario */
    private $nombre;
    /** @var string Email del usuario */
    private $email;
    /** @var string Contraseña del usuario */
    private $contraseña;
    /** @var string Fecha de registro del usuario */
    private $fecha_registro;
    /** @var string Imagen del usuario */
    private $imagen;

     /**
     * Obtiene el ID del usuario
     *
     * @return int ID del usuario
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * Obtiene el nombre del usuario
     * 
     * @return string Nombre del usuario
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Obtiene el email del usuario
     * 
     * @return string Email del usuario
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Obtiene la contrasela del usuario
     * 
     * @return string Contraseña del usuario
     */
    public function getContraseña()
    {
        return $this->contraseña;
    }

    /**
     * Obtiene la fecha de registro del usuario
     * 
     * @return string Fecha de registro del usuario
     */
    public function getFechaRegistro()
    {
        return $this->fecha_registro;
    }

    /**
     * Obtiene la imagen del usuario
     * 
     * @return string Imagen del usuario
     */
    public function getImagen()
    {
        return $this->imagen;
    }


    /**
     * Establece la ID del usuario
     *
     * @param string $id_usuario ID del usuario
     * @return void
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * Establece el nombre del usuario
     *
     * @param string $nombre Nombre del usuario
     * @return void
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Establece el email del usuario
     *
     * @param string $email Email del usuario
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Establece la contraseña del usuario
     *
     * @param string $contraseña Contraseña del usuario
     * @return void
     */
    public function setContraseña($contraseña)
    {
        $this->contraseña = $contraseña;
    }

    /**
     * Establece la fecha de registro del usuario
     *
     * @param string $fecha_registro Fecha de registro del usuario
     * @return void
     */
    public function setFechaRegistro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
    }

    /**
     * Establece la imagen del usuario
     *
     * @param string $imagen Imagen del usuario
     * @return void
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }


    /**
     * Crea un nuevo usuario en la base de datos
     *
     * @return void
     */
    public function create()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO usuario (nombre, email, contraseña, imagen) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->nombre, $this->email, $this->contraseña, $this->imagen]);
            $this->id_usuario = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al crear el usuario: " . $e->getMessage();
        }
    }

    /**
     * Obtiene todos los usuarios de la base de datos
     *
     * @return Usuario[] Lista de usuarios
     */
    public static function getAllUsers()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM usuario");
            $stmt->execute();
            $usuarios = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuario = new Usuario();
                $usuario->setIdUsuario($row['id_usuario']);
                $usuario->setNombre($row['nombre']);
                $usuario->setEmail($row['email']);
                $usuario->setContraseña($row['contraseña']);
                $usuario->setFechaRegistro($row['fecha_registro']);
                $usuario->setImagen($row['imagen']);
                $usuarios[] = $usuario;
            }

            return $usuarios;
        } catch (PDOException $e) {
            echo "Error al obtener los usuarios: " . $e->getMessage();
            return [];
        }
    }


    /**
     * Obtiene un usuario por su nombre
     *
     * @param string $nombre Nombre del usuario
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public static function getUserByName($nombre)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE nombre = ?");
            $stmt->execute([$nombre]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $usuario = new Usuario();
                $usuario->setIdUsuario($result['id_usuario']);
                $usuario->setNombre($result['nombre']);
                $usuario->setEmail($result['email']);
                $usuario->setContraseña($result['contraseña']);
                $usuario->setFechaRegistro($result['fecha_registro']);
                $usuario->setImagen($result['imagen']); // Asignar la imagen
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener el usuario por nombre: " . $e->getMessage();
            return null;
        }
    }


    /**
     * Obtiene un usuario por su email
     *
     * @param string $email Email del usuario
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public static function getUserByEmail($email)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");
            $stmt->execute([$email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $usuario = new Usuario();
                $usuario->setIdUsuario($result['id_usuario']);
                $usuario->setNombre($result['nombre']);
                $usuario->setEmail($result['email']);
                $usuario->setContraseña($result['contraseña']);
                $usuario->setFechaRegistro($result['fecha_registro']);
                $usuario->setImagen($result['imagen']); // Asignar la imagen
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
        }
    }

/**
     * Obtiene un usuario por su ID
     *
     * @param int $id_usuario ID del usuario
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public static function getUserById($id_usuario)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $usuario = new Usuario();
                $usuario->setIdUsuario($result['id_usuario']);
                $usuario->setNombre($result['nombre']);
                $usuario->setEmail($result['email']);
                $usuario->setContraseña($result['contraseña']);
                $usuario->setFechaRegistro($result['fecha_registro']);
                $usuario->setImagen($result['imagen']); // Asignar la imagen
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener el usuario por ID: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Verifica si las credenciales del usuario son válidas
     *
     * @param string $email Email del usuario
     * @param string $contraseña Contraseña del usuario
     * @return Usuario|false Usuario encontrado o false si las credenciales son incorrectas
     */
    public static function verifyPassword($email, $contraseña)
    {

        $usuario = self::getUserByEmail($email);

        if ($usuario && $contraseña === $usuario->getContraseña()) {
            return $usuario;
        }
        return false;
    }

    /**
     * Actualiza la contraseña del usuario
     *
     * @param string $nuevaContraseña Nueva contraseña del usuario
     * @return void
     */
    public function updatePassword($nuevaContraseña)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE usuario SET contraseña = ? WHERE id_usuario = ?");
            $stmt->execute([$nuevaContraseña, $this->id_usuario]);
        } catch (PDOException $e) {
            echo "Error al actualizar la contraseña: " . $e->getMessage();
        }
    }


    /**
     * Actualiza los datos del usuario
     *
     * @return void
     */
    public function update()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE usuario SET nombre = ?, email = ?, contraseña = ?, imagen = ? WHERE id_usuario = ?");
            $stmt->execute([$this->nombre, $this->email, $this->contraseña , $this->imagen, $this->id_usuario]);
        } catch (PDOException $e) {
            echo "Error al actualizar el usuario: " . $e->getMessage();
        }
    }

/**
     * Verifica si el email del usuario ya existe
     *
     * @param string $email Email del usuario
     * @return bool true si el email existe, false en caso contrario
     */
    public static function EmailExiste($email)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email = ?");
            $stmt->execute([$email]);
            $result = $stmt->fetchColumn();
            return $result > 0; // Si existe, retorna true
        } catch (PDOException $e) {
            echo "Error al verificar el email: " . $e->getMessage();
            return false;
        }
    }


    /**
     * Verifica si el nombre de usuario ya existe
     *
     * @param string $nombre Nombre del usuario
     * @return bool true si el nombre de usuario existe, false en caso contrario
     */
    public static function UsuarioExiste($nombre)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE nombre = ?");
            $stmt->execute([$nombre]);
            $result = $stmt->fetchColumn();
            return $result > 0; // Si existe, retorna true
        } catch (PDOException $e) {
            echo "Error al verificar el nombre de usuario: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Elimina el usuario de la base de datos
     *
     * @return void
     */
    public function delete()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$this->id_usuario]);
        } catch (PDOException $e) {
            echo "Error al eliminar el usuario: " . $e->getMessage();
        }
    }
}
