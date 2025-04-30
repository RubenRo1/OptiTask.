<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');

class Categoria {
    private $id_categoria;
    private $nombre_categoria;

    // Getters
     public function getIdCategoria() {
        return $this->id_categoria;
    }

    public function getNombreCategoria() {
        return $this->nombre_categoria;
    }



    // Setters
    public function setIdCategoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    public function setNombreCategoria($nombre_categoria) {
        $this->nombre_categoria = $nombre_categoria;
    }

    public function create() {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO categoria (nombre_categoria) VALUES (?)");
            $stmt->execute([$this->nombre_categoria]);
            $this->id_categoria = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al crear la categoría: " . $e->getMessage();
        }
    }

    
    public static function getCategoriaById($id_categoria) {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM categoria WHERE id_categoria = ?");
            $stmt->execute([$id_categoria]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $categoria = new Categoria();
                $categoria->setIdCategoria($result['id_categoria']);
                $categoria->setNombreCategoria($result['nombre_categoria']);
                return $categoria;
            }
            return null;
        } catch (PDOException $e) {
            echo "Error al obtener la categoría: " . $e->getMessage();
        }
    }
     // Obtener todas las categorías
     public static function getAllCategorias() {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM categoria");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categorias = [];
            foreach ($result as $row) {
                $categoria = new Categoria();
                $categoria->setIdCategoria($row['id_categoria']);
                $categoria->setNombreCategoria($row['nombre_categoria']);
                $categorias[] = $categoria;
            }
            return $categorias;
        } catch (PDOException $e) {
            echo "Error al obtener las categorías: " . $e->getMessage();
        }
    }

     // Actualizar el nombre de una categoría
     public function updateNombreCategoria($nuevoNombre) {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("UPDATE categoria SET nombre_categoria = ? WHERE id_categoria = ?");
            $stmt->execute([$nuevoNombre, $this->id_categoria]);
            $this->nombre_categoria = $nuevoNombre;  // Actualizamos el nombre en el objeto también
            echo "Categoría actualizada con éxito.";
        } catch (PDOException $e) {
            echo "Error al actualizar el nombre de la categoría: " . $e->getMessage();
        }
    }

    public function delete() {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("DELETE FROM categoria WHERE id_categoria = ?");
            $stmt->execute([$this->id_categoria]);
        } catch (PDOException $e) {
            echo "Error al eliminar la categoría: " . $e->getMessage();
        }
    }

}
