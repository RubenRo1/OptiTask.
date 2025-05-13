<?php

require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');

class Tarea
{
    private $id_tarea;
    private $id_usuario;
    private $titulo;
    private $fecha_creacion;
    private $fecha_limite;
    private $prioridad;
    private $estado;
    private $descripcion;
    private $tiempo_estimado;

    // Getters
    public function getIdTarea()
    {
        return $this->id_tarea;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    public function getFechaLimite()
    {
        return $this->fecha_limite;
    }

    public function getPrioridad()
    {
        return $this->prioridad;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getTiempoEstimado()
    {
        return $this->tiempo_estimado;
    }

    // Setters
    public function setIdTarea($id_tarea)
    {
        $this->id_tarea = $id_tarea;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function setFechaCreacion($fecha_creacion)
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function setFechaLimite($fecha_limite)
    {
        $this->fecha_limite = $fecha_limite;
    }

    public function setPrioridad($prioridad)
    {
        $this->prioridad = $prioridad;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setTiempoEstimado($tiempo_estimado)
    {
        $this->tiempo_estimado = $tiempo_estimado;
    }

    // Crear una nueva tarea
    public function create()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO tarea (id_usuario, titulo, fecha_limite, prioridad, estado, descripcion, tiempo_estimado) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$this->id_usuario, $this->titulo, $this->fecha_limite, $this->prioridad, $this->estado, $this->descripcion, $this->tiempo_estimado]);
            $this->id_tarea = $conn->lastInsertId();

            return $conn->lastInsertId(); // Retorna el ID de la tarea creada AL IGUAL BORRAR SI FALLA

        } catch (PDOException $e) {
            echo "Error al crear la tarea: " . $e->getMessage();
        }
    }

    //Obtiene todas las tareas
    public static function getAllTareas()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM tarea");
            $stmt->execute();

            $tareas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tarea = new Tarea();
                $tarea->setIdTarea($row['id_tarea']);
                $tarea->setIdUsuario($row['id_usuario']);
                $tarea->setTitulo($row['titulo']);
                $tarea->setFechaCreacion($row['fecha_creacion']);
                $tarea->setFechaLimite($row['fecha_limite']);
                $tarea->setPrioridad($row['prioridad']);
                $tarea->setEstado($row['estado']);
                $tarea->setDescripcion($row['descripcion']);
                $tarea->setTiempoEstimado($row['tiempo_estimado']);
                $tareas[] = $tarea;
            }

            return $tareas;
        } catch (PDOException $e) {
            echo "Error al obtener tareas: " . $e->getMessage();
            return [];
        }
    }

    // Obtener tarea por ID
    public static function getTareaById($id_tarea)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM tarea WHERE id_tarea = ?");
            $stmt->execute([$id_tarea]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $tarea = new Tarea();
                $tarea->setIdTarea($result['id_tarea']);
                $tarea->setIdUsuario($result['id_usuario']);
                $tarea->setTitulo($result['titulo']);
                $tarea->setFechaCreacion($result['fecha_creacion']);
                $tarea->setFechaLimite($result['fecha_limite']);
                $tarea->setPrioridad($result['prioridad']);
                $tarea->setEstado($result['estado']);
                $tarea->setDescripcion($result['descripcion']);
                $tarea->setTiempoEstimado($result['tiempo_estimado']);
                return $tarea;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener la tarea: " . $e->getMessage();
            return null;
        }
    }
    // Obtener tareas por ID de usuario
    public static function getTareasByUser($id_usuario)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM tarea WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener todas las tareas

            $tareas = [];
            if ($result) {
                foreach ($result as $row) {
                    $tarea = new Tarea();
                    $tarea->setIdTarea($row['id_tarea']);
                    $tarea->setIdUsuario($row['id_usuario']);
                    $tarea->setTitulo($row['titulo']);
                    $tarea->setFechaCreacion($row['fecha_creacion']);
                    $tarea->setFechaLimite($row['fecha_limite']);
                    $tarea->setPrioridad($row['prioridad']);
                    $tarea->setEstado($row['estado']);
                    $tarea->setDescripcion($row['descripcion']);
                    $tarea->setTiempoEstimado($row['tiempo_estimado']);
                    $tareas[] = $tarea; // Añadir tarea al array
                }
                return $tareas;
            }
            return []; // Si no hay tareas, devolver un array vacío
        } catch (PDOException $e) {
            echo "Error al obtener las tareas del usuario: " . $e->getMessage();
            return [];
        }
    }


    // Actualizar la descripción de la tarea
    public function updateDescripcion($nuevaDescripcion)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE tarea SET descripcion = ? WHERE id_tarea = ?");
            $stmt->execute([$nuevaDescripcion, $this->id_tarea]);

            // Actualizamos el objeto con la nueva descripción
            $this->descripcion = $nuevaDescripcion;
        } catch (PDOException $e) {
            echo "Error al actualizar la descripción de la tarea: " . $e->getMessage();
        }
    }

    public function updateTitulo($nuevoTitulo)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE tarea SET titulo = ? WHERE id_tarea = ?");
            $stmt->execute([$nuevoTitulo, $this->id_tarea]);

            // Actualizamos el objeto con el nuevo título
            $this->titulo = $nuevoTitulo;
        } catch (PDOException $e) {
            echo "Error al actualizar el título de la tarea: " . $e->getMessage();
        }
    }

    public function updatePrioridad($nuevaPrioridad)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE tarea SET prioridad = ? WHERE id_tarea = ?");
            $stmt->execute([$nuevaPrioridad, $this->id_tarea]);

            // Actualizamos el objeto con la nueva prioridad
            $this->prioridad = $nuevaPrioridad;
        } catch (PDOException $e) {
            echo "Error al actualizar la prioridad de la tarea: " . $e->getMessage();
        }
    }

    public function updateEstado($nuevoEstado)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE tarea SET estado = ? WHERE id_tarea = ?");
            $stmt->execute([$nuevoEstado, $this->id_tarea]);

            // Actualizamos el objeto con el nuevo estado
            $this->estado = $nuevoEstado;
        } catch (PDOException $e) {
            echo "Error al actualizar el estado de la tarea: " . $e->getMessage();
        }
    }

    public function updateTiempoEstimado($nuevoTiempoEstimado)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE tarea SET tiempo_estimado = ? WHERE id_tarea = ?");
            $stmt->execute([$nuevoTiempoEstimado, $this->id_tarea]);

            // Actualizamos el objeto con el nuevo tiempo estimado
            $this->tiempo_estimado = $nuevoTiempoEstimado;
        } catch (PDOException $e) {
            echo "Error al actualizar el tiempo estimado de la tarea: " . $e->getMessage();
        }
    }

    public function updateFechaLimite($nuevaFechaLimite)
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE tarea SET fecha_limite = ? WHERE id_tarea = ?");
            $stmt->execute([$nuevaFechaLimite, $this->id_tarea]);

            // Actualizamos el objeto con la nueva fecha límite
            $this->fecha_limite = $nuevaFechaLimite;
        } catch (PDOException $e) {
            echo "Error al actualizar la fecha límite de la tarea: " . $e->getMessage();
        }
    }


    public static function getTareasUrgentes($id_usuario, $limite = 3)
    {
        try {
            $conn = getDBConnection();

            // IMPORTANTE: usar ? para el id_usuario y concatenar el límite de forma segura
            $sql = "SELECT * FROM tarea 
               WHERE id_usuario = ? 
               AND estado != 'Completada'
               AND fecha_limite >= CURDATE()
               ORDER BY 
                 CASE prioridad
                   WHEN 'Alta' THEN 1
                   WHEN 'Media' THEN 2
                   WHEN 'Baja' THEN 3
                   ELSE 4
                 END,
                 fecha_limite ASC
               LIMIT $limite";
               
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_usuario]);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $tareas_urgentes = [];
            foreach ($result as $row) {
                $tarea = new Tarea();
                $tarea->setIdTarea($row['id_tarea']);
                $tarea->setIdUsuario($row['id_usuario']);
                $tarea->setTitulo($row['titulo']);
                $tarea->setFechaCreacion($row['fecha_creacion']);
                $tarea->setFechaLimite($row['fecha_limite']);
                $tarea->setPrioridad($row['prioridad']);
                $tarea->setEstado($row['estado']);
                $tarea->setDescripcion($row['descripcion']);
                $tarea->setTiempoEstimado($row['tiempo_estimado']);

                $tareas_urgentes[] = $tarea;
            }

            return $tareas_urgentes;
        } catch (PDOException $e) {
            error_log("Error en getTareasUrgentes: " . $e->getMessage());
            return [];
        }
    }


    // Actualizar una tarea
    public function update()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE tarea SET titulo = ?, fecha_limite = ?, prioridad = ?, estado = ?, descripcion = ?, tiempo_estimado = ? WHERE id_tarea = ?");
            $stmt->execute([$this->titulo, $this->fecha_limite, $this->prioridad, $this->estado, $this->descripcion, $this->tiempo_estimado, $this->id_tarea]);
        } catch (PDOException $e) {
            echo "Error al actualizar la tarea: " . $e->getMessage();
        }
    }

    // Eliminar tarea
    public function delete()
    {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("DELETE FROM tarea WHERE id_tarea = ?");
            $stmt->execute([$this->id_tarea]);
        } catch (PDOException $e) {
            echo "Error al eliminar la tarea: " . $e->getMessage();
        }
    }
}
