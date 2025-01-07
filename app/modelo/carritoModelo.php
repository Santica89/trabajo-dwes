<?php
require_once 'db.php';

class CarritoModelo {
    public static function obtenerProductoPorId($id) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("SELECT id, nombre, precio, imagen FROM articulos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener producto por ID: " . $e->getMessage());
            return null;
        }
    }
}
