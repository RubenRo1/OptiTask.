<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
class HistorialEstado
{
    private $id_historial;
    private $id_tarea;
    private $estado_anterior;
    private $estado_nuevo;
    private $fecha_cambio;

    // Getters
    public function getIdHistorial()
    {
        return $this->id_historial;
    }

    public function getIdTarea()
    {
        return $this->id_tarea;
    }

    public function getEstadoAnterior()
    {
        return $this->estado_anterior;
    }

    public function getEstadoNuevo()
    {
        return $this->estado_nuevo;
    }

    public function getFechaCambio()
    {
        return $this->fecha_cambio;
    }

    // Setters
    public function setIdHistorial($id_historial)
    {
        $this->id_historial = $id_historial;
    }

    public function setIdTarea($id_tarea)
    {
        $this->id_tarea = $id_tarea;
    }

    public function setEstadoAnterior($estado)
    {
        $this->estado_anterior = $estado;
    }

    public function setEstadoNuevo($estado)
    {
        $this->estado_nuevo = $estado;
    }

    public function setFechaCambio($fecha)
    {
        $this->fecha_cambio = $fecha;
    }

    // Crear historial
    public function create()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO historial_estados (id_tarea, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
            $stmt->execute([$this->id_tarea, $this->estado_anterior, $this->estado_nuevo]);
            $this->id_historial = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al guardar el historial de estado: " . $e->getMessage();
        }
    }

    public static function getHistorialEstadosById($id_historial)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM historial_estados WHERE id_historial = ?");
            $stmt->execute([$id_historial]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $historial = new HistorialEstado();
                $historial->setIdHistorial($row['id_historial']);
                $historial->setIdTarea($row['id_tarea']);
                $historial->setEstadoAnterior($row['estado_anterior']);
                $historial->setEstadoNuevo($row['estado_nuevo']);
                $historial->setFechaCambio($row['fecha_cambio']);
                return $historial;
            }

            return null;
        } catch (PDOException $e) {
            echo "Error al obtener historial por ID: " . $e->getMessage();
            return null;
        }
    }


    public static function getAllHistorialEstados()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM historial_estados");
            $stmt->execute();

            $historiales = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $historial = new HistorialEstado();
                $historial->setIdHistorial($row['id_historial']);
                $historial->setIdTarea($row['id_tarea']);
                $historial->setEstadoAnterior($row['estado_anterior']);
                $historial->setEstadoNuevo($row['estado_nuevo']);
                $historial->setFechaCambio($row['fecha_cambio']);
                $historiales[] = $historial;
            }

            return $historiales;
        } catch (PDOException $e) {
            echo "Error al obtener historial de estados: " . $e->getMessage();
            return [];
        }
    }

    // Obtener historial de una tarea
    public static function getByTarea($id_tarea)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM historial_estados WHERE id_tarea = ? ORDER BY fecha_cambio DESC");
            $stmt->execute([$id_tarea]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener historial: " . $e->getMessage();
            return [];
        }
    }
}
