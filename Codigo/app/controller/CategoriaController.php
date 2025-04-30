<?php
require_once(__DIR__ . '/../../rutas.php');
require_once(CONFIG . 'dbConnection.php');
require_once(MODEL . 'Categoria.php');

class CategoriaController
{
    // Crear una nueva categoría
    public function crearCategoria($nombre)
    {
        $categoria = new Categoria();
        $categoria->setNombreCategoria($nombre);
        $categoria->create();
        return $categoria;
    }

    // Obtener una categoría por su ID
    public function obtenerCategoriaPorId($id)
    {
        return Categoria::getCategoriaById($id);
    }

    // Obtener todas las categorías
    public function obtenerTodasCategorias()
    {
        return Categoria::getAllCategorias();
    }

    // Actualizar una categoría
    public function actualizarCategoria($id, $nuevoNombre)
    {
        $categoria = Categoria::getCategoriaById($id);
        if ($categoria) {
            $categoria->updateNombreCategoria($nuevoNombre);
            return true;
        }
        return false;
    }

    // Eliminar una categoría
    public function eliminarCategoria($id)
    {
        $categoria = Categoria::getCategoriaById($id);
        if ($categoria) {
            $categoria->delete();
            return true;
        }
        return false;
    }
}
