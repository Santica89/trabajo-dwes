<?php
require_once 'db.php';

class ArticuloModelo {
    public static function obtenerArticuloPorId($id) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("SELECT * FROM articulos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener el artículo por ID: " . $e->getMessage());
            return null;
        }
    }

    public static function obtenerTodosLosArticulos() {
        try {
            $db = DB::connect();
            $stmt = $db->query("SELECT * FROM articulos");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todos los artículos: " . $e->getMessage());
            return [];
        }
    }

    public static function crearArticulo($nombre, $descripcion, $precio, $stock, $imagen) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("INSERT INTO articulos (nombre, descripcion, precio, stock, imagen) VALUES (:nombre, :descripcion, :precio, :stock, :imagen)");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al crear el artículo: " . $e->getMessage());
            return false;
        }
    }

    public static function editarArticulo($id, $nombre, $descripcion, $precio, $stock, $imagen) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("UPDATE articulos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, imagen = :imagen WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al editar el artículo: " . $e->getMessage());
            return false;
        }
    }

    public static function borrarArticulo($id) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("DELETE FROM articulos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar el artículo: " . $e->getMessage());
            return false;
        }
    }
}
?>
