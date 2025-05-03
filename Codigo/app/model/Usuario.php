<?php

require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');

class Usuario
{
    private $id_usuario;
    private $nombre;
    private $email;
    private $contraseña;
    private $fecha_registro;

    // Getters
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getContraseña()
    {
        return $this->contraseña;
    }

    public function getFechaRegistro()
    {
        return $this->fecha_registro;
    }

    // Setters
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setContraseña($contraseña)
    {
        $this->contraseña = $contraseña;
    }

    public function setFechaRegistro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
    }

    // Crear un nuevo usuario
    public function create()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO usuario (nombre, email, contraseña) VALUES (?, ?, ?)");
            $stmt->execute([$this->nombre, $this->email, $this->contraseña]);
            $this->id_usuario = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al crear el usuario: " . $e->getMessage();
        }
    }
    // Obtener todos los usuarios
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
                $usuarios[] = $usuario;
            }

            return $usuarios;
        } catch (PDOException $e) {
            echo "Error al obtener los usuarios: " . $e->getMessage();
            return [];
        }
    }


    // Obtener un usuario por su nombre
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
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener el usuario por nombre: " . $e->getMessage();
            return null;
        }
    }


    // Obtener un usuario por email
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
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
        }
    }

    // Obtener un usuario por su ID
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
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener el usuario por ID: " . $e->getMessage();
            return null;
        }
    }

    // Verificar la contraseña al iniciar sesión
    public static function verifyPassword($email, $contraseña)
    {

        $usuario = self::getUserByEmail($email);

        if ($usuario && $contraseña === $usuario->getContraseña()) {
            return $usuario;
        }
        return false;
    }

    //Actualiza la contraseña del usuario
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


    // Actualizar datos del usuario
    public function update()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE usuario SET nombre = ?, email = ?, contraseña = ? WHERE id_usuario = ?");
            $stmt->execute([$this->nombre, $this->email, $this->contraseña, $this->id_usuario]);
        } catch (PDOException $e) {
            echo "Error al actualizar el usuario: " . $e->getMessage();
        }
    }

    // Verificar si el nombre de usuario ya existe
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

    // Eliminar usuario
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
