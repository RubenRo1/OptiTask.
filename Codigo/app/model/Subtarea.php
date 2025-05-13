<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');

class Subtarea
{

    private $id_subtarea;
    private $id_tarea;
    private $descripcion;
    private $completado;

    // Getters

    public function getIdSubtarea()
    {
        return $this->id_subtarea;
    }
    public function getIdTarea()
    {
        return $this->id_tarea;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getCompletado()
    {
        return $this->completado;
    }
    // Setters
    public function setIdSubtarea($id_subtarea)
    {
        $this->id_subtarea = $id_subtarea;
    }
    public function setIdTarea($id_tarea)
    {
        $this->id_tarea = $id_tarea;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setCompletado($completado)
    {
        $this->completado = $completado;
    }

    // Crear una nueva subtarea

    public function create()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO subtarea (id_tarea, descripcion, completado) VALUES (?, ?, ?)");
            $stmt->execute([$this->id_tarea, $this->descripcion, $this->completado]);
            $this->id_subtarea = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al crear la subtarea: " . $e->getMessage();
        }
    }

    // Obtener subtarea por ID

    public static function getSubtareaById($id_subtarea)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM subtarea WHERE id_subtarea = ?");
            $stmt->execute([$id_subtarea]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $subtarea = new Subtarea();
                $subtarea->setIdSubtarea($result['id_subtarea']);
                $subtarea->setIdTarea($result['id_tarea']);
                $subtarea->setDescripcion($result['descripcion']);
                $subtarea->setCompletado($result['completado']);
                return $subtarea;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener la subtarea: " . $e->getMessage();
        }
    }


    // Obtener todas las categorías

    public static function getAllSubtareas()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM subtarea");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $subtareas = [];
            foreach ($result as $row) {
                $subtarea = new Subtarea();
                $subtarea->setIdSubtarea($row['id_subtarea']);
                $subtarea->setIdTarea($row['id_tarea']);
                $subtarea->setDescripcion($row['descripcion']);
                $subtarea->setCompletado($row['completado']);
                $subtareas[] = $subtarea;
            }
            return $subtareas;
        } catch (PDOException $e) {
            echo "Error al obtener las subtareas: " . $e->getMessage();
        }
    }

    // Actualizar la descripcion de una categoría
    public function updateDescripcion($nuevaDescripcion)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE subtarea SET descripcion = ? WHERE id_subtarea = ?");
            $stmt->execute([$nuevaDescripcion, $this->id_subtarea]);
            $this->descripcion = $nuevaDescripcion;  // Actualizamos la descripción en el objeto también
            echo "Subtarea actualizada con éxito.";
        } catch (PDOException $e) {
            echo "Error al actualizar la subtarea: " . $e->getMessage();
        }
    }

    // Actualizar el estado de completado de una categoría
    public function updateCompletado($nuevoEstado)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE subtarea SET completado = ? WHERE id_subtarea = ?");
            $stmt->execute([$nuevoEstado, $this->id_subtarea]);
            $this->completado = $nuevoEstado;  // Actualizamos el estado en el objeto también
            echo "Subtarea actualizada con éxito.";
        } catch (PDOException $e) {
            echo "Error al actualizar la subtarea: " . $e->getMessage();
        }
    }


    // Obtener todas las subtareas de una tarea
    public static function getSubtareasByTarea($id_tarea)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM subtarea WHERE id_tarea = ?");
            $stmt->execute([$id_tarea]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $subtareas = [];
            foreach ($result as $row) {
                $subtarea = new Subtarea();
                $subtarea->setIdSubtarea($row['id_subtarea']);
                $subtarea->setIdTarea($row['id_tarea']);
                $subtarea->setDescripcion($row['descripcion']);
                $subtarea->setCompletado($row['completado']);
                $subtareas[] = $subtarea;
            }
            return $subtareas;
        } catch (PDOException $e) {
            echo "Error al obtener las subtareas: " . $e->getMessage();
        }
    }
    //Actualizar subtarea
    public function update()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE subtarea SET id_tarea = ?, descripcion = ?, completado = ? WHERE id_subtarea = ?");
            $stmt->execute([$this->id_tarea, $this->descripcion, $this->completado, $this->id_subtarea]);
        } catch (PDOException $e) {
            echo "Error al actualizar la subtarea: " . $e->getMessage();
        }
    }

    // Eliminar una categoría
    public function delete()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("DELETE FROM subtarea WHERE id_subtarea = ?");
            $stmt->execute([$this->id_subtarea]);
        } catch (PDOException $e) {
            echo "Error al eliminar la subtarea: " . $e->getMessage();
        }
    }
}
