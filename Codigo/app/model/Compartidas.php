<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
class Compartidas
{
    private $id_compartidas;
    private $id_tarea;
    private $id_usuario_origen;
    private $id_usuario_destino;
    private $fecha_compartida;

    // Getters
    public function getIdCompartidas()
    {
        return $this->id_compartidas;
    }

    public function getIdTarea()
    {
        return $this->id_tarea;
    }

    public function getUsuario_Origen()
    {
        return $this->id_usuario_origen;
    }

    public function getUsuario_Destino()
    {
        return $this->id_usuario_destino;
    }

    public function getFechaCompartida()
    {
        return $this->fecha_compartida;
    }

    // Setters
    public function setIdCompartidas($id_compartidas)
    {
        $this->id_compartidas = $id_compartidas;
    }

    public function setIdTarea($id_tarea)
    {
        $this->id_tarea = $id_tarea;
    }

    public function setUsuario_Origen($id_usuario_origen)
    {
        $this->id_usuario_origen = $id_usuario_origen;
    }

    public function setUsuario_Destino($id_usuario_destino)
    {
        $this->id_usuario_destino = $id_usuario_destino;
    }

    public function setFechaCompartida($fecha_compartida)
    {
        $this->fecha_compartida = $fecha_compartida;
    }

    // Crear historial
    public function create()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO compartidas (id_tarea, id_usuario_origen, id_usuario_destino) VALUES (?, ?, ?)");
            $stmt->execute([$this->id_tarea, $this->id_usuario_origen, $this->id_usuario_destino]);
            $this->id_compartidas = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al guardar el historial de estado: " . $e->getMessage();
        }
    }

    public static function getCompartidasById($id_compartidas)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM compartidas WHERE id_compartidas = ?");
            $stmt->execute([$id_compartidas]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $historial = new Compartidas();
                $historial->setIdCompartidas($row['id_compartidas']);
                $historial->setIdTarea($row['id_tarea']);
                $historial->setUsuario_Origen($row['id_usuario_origen']);
                $historial->setUsuario_Destino($row['id_usuario_destino']);
                $historial->setFechaCompartida($row['fecha_compartida']);
                return $historial;
            }

            return null;
        } catch (PDOException $e) {
            echo "Error al obtener historial por ID: " . $e->getMessage();
            return null;
        }
    }


    public static function getAllCompartidas()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM compartidas");
            $stmt->execute();

            $historiales = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $historial = new Compartidas();
                $historial->setIdCompartidas($row['id_compartidas']);
                $historial->setIdTarea($row['id_tarea']);
                $historial->setUsuario_Origen($row['id_usuario_origen']);
                $historial->setUsuario_Destino($row['id_usuario_destino']);
                $historial->setFechaCompartida($row['fecha_compartida']);
                $historiales[] = $historial;
            }

            return $historiales;
        } catch (PDOException $e) {
            echo "Error al obtener compartidas: " . $e->getMessage();
            return [];
        }
    }

    // Obtener historial de una tarea
    // public static function getByTarea($id_tarea)
    // {
    //     try {
    //         $conn = getDBConnection();
    //         $stmt = $conn->prepare("SELECT * FROM compartidas WHERE id_tarea = ? ORDER BY fecha_compartida DESC");
    //         $stmt->execute([$id_tarea]);
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         echo "Error al obtener historial: " . $e->getMessage();
    //         return [];
    //     }
    // }

    public static function getByTarea($id_tarea)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM compartidas WHERE id_tarea = ? ORDER BY fecha_compartida DESC");
            $stmt->execute([$id_tarea]);

            $compartidas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $compartida = new Compartidas();
                $compartida->setIdCompartidas($row['id_compartidas']);
                $compartida->setIdTarea($row['id_tarea']);
                $compartida->setUsuario_Origen($row['id_usuario_origen']);
                $compartida->setUsuario_Destino($row['id_usuario_destino']);
                $compartida->setFechaCompartida($row['fecha_compartida']);
                $compartidas[] = $compartida;
            }

            return $compartidas;
        } catch (PDOException $e) {
            echo "Error al obtener historial: " . $e->getMessage();
            return [];
        }
    }

    public static function getByUser($id_usuario_origen)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM compartidas WHERE id_usuario_origen = ? ORDER BY fecha_compartida DESC");
            $stmt->execute([$id_usuario_origen]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener historial: " . $e->getMessage();
            return [];
        }
    }

    public static function getCompartidasByTareaAndUser($id_tarea, $id_usuario_destino)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM compartidas WHERE id_tarea = ? AND id_usuario_destino = ?");
            $stmt->execute([$id_tarea, $id_usuario_destino]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener compartida por tarea y usuario: " . $e->getMessage();
            return null;
        }
    }

    public static function getByUserDestino($id_usuario_destino)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM compartidas WHERE id_usuario_destino = ? ORDER BY fecha_compartida DESC");
            $stmt->execute([$id_usuario_destino]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener historial: " . $e->getMessage();
            return [];
        }
    }

    public function update()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE compartidas SET id_tarea = ?, id_usuario_origen = ?, id_usuario_destino = ? WHERE id_compartidas = ?");
            $stmt->execute([$this->id_tarea, $this->id_usuario_origen, $this->id_usuario_destino, $this->id_compartidas]);
        } catch (PDOException $e) {
            echo "Error al actualizar la compartida: " . $e->getMessage();
        }
    }

    // Eliminar historial
    public function delete()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("DELETE FROM compartidas WHERE id_compartidas = ?");
            $stmt->execute([$this->id_compartidas]);
        } catch (PDOException $e) {
            echo "Error al eliminar la compartida: " . $e->getMessage();
        }
    }
}
