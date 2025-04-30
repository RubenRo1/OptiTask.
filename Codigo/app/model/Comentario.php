<?php

require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');

class Comentario
{
    private $id_comentario;
    private $id_tarea;
    private $id_usuario;
    private $contenido;
    private $fecha_comentario;

    // Getters
    public function getIdComentario()
    {
        return $this->id_comentario;
    }

    public function getIdTarea()
    {
        return $this->id_tarea;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getContenido()
    {
        return $this->contenido;
    }

    public function getFechaComentario()
    {
        return $this->fecha_comentario;
    }

    // Setters
    public function setIdComentario($id_comentario)
    {
        $this->id_comentario = $id_comentario;
    }

    public function setIdTarea($id_tarea)
    {
        $this->id_tarea = $id_tarea;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }

    public function setFechaComentario($fecha_comentario)
    {
        $this->fecha_comentario = $fecha_comentario;
    }

    // Crear un nuevo comentario
    public function create()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO comentarios (id_tarea, id_usuario, contenido) VALUES (?, ?, ?)");
            $stmt->execute([$this->id_tarea, $this->id_usuario, $this->contenido]);
            $this->id_comentario = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al crear el comentario: " . $e->getMessage();
        }
    }

    public static function getComentarioById($id_comentario)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM comentarios WHERE id_comentario = ?");
            $stmt->execute([$id_comentario]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $comentario = new Comentario();
                $comentario->setIdComentario($result['id_comentario']);
                $comentario->setIdTarea($result['id_tarea']);
                $comentario->setIdUsuario($result['id_usuario']);
                $comentario->setContenido($result['contenido']);
                $comentario->setFechaComentario($result['fecha_comentario']);
                return $comentario;
            }
            return null; // Si no se encuentra el comentario
        } catch (PDOException $e) {
            echo "Error al obtener el comentario: " . $e->getMessage();
            return null; // En caso de error
        }
    }


    public static function getAllComentarios()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM comentarios");
            $stmt->execute();

            $comentarios = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $comentario = new Comentario();
                $comentario->setIdComentario($row['id_comentario']);
                $comentario->setIdTarea($row['id_tarea']);
                $comentario->setContenido($row['contenido']);
                $comentario->setFechaComentario($row['fecha_comentario']);
                $comentario->setIdUsuario($row['id_usuario']);
                $comentarios[] = $comentario;
            }

            return $comentarios;
        } catch (PDOException $e) {
            echo "Error al obtener comentarios: " . $e->getMessage();
            return [];
        }
    }

    // Obtener comentarios por usuario
    public static function getByUser($id_usuario)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM comentarios WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener los comentarios por usuario: " . $e->getMessage();
        }
    }



    // Obtener comentarios por tarea
    public static function getByTarea($id_tarea)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM comentarios WHERE id_tarea = ?");
            $stmt->execute([$id_tarea]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener los comentarios: " . $e->getMessage();
        }
    }

    public function update()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE comentarios SET contenido = ? WHERE id_comentario = ?");
            $stmt->execute([$this->contenido, $this->id_comentario]);
        } catch (PDOException $e) {
            echo "Error al actualizar el comentario: " . $e->getMessage();
        }
    }


    public function delete()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("DELETE FROM comentarios WHERE id_comentario = ?");
            $stmt->execute([$this->id_comentario]);
        } catch (PDOException $e) {
            echo "Error al eliminar el comentario: " . $e->getMessage();
        }
    }
}
