<?php
require_once 'db.php';

class PedidoModelo {
    // Borra un pedido en la vista de Admin
    public static function borrarPedido($pedidoId) {
        try {
            $db = DB::connect();
    
            // Eliminar los detalles del pedido primero
            $stmtDetalles = $db->prepare("DELETE FROM detalle_pedido WHERE id_pedido = :id_pedido");
            $stmtDetalles->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
            $stmtDetalles->execute();
    
            // Luego eliminar el pedido
            $stmtPedido = $db->prepare("DELETE FROM pedidos WHERE id = :id");
            $stmtPedido->bindParam(':id', $pedidoId, PDO::PARAM_INT);
            return $stmtPedido->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar pedido: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener todos los clientes para detalles en Admin
    public static function obtenerTodosConClientes() {
        try {
            $db = DB::connect();
            $stmt = $db->query("
                SELECT pedidos.id AS pedido_id, usuarios.nombre AS cliente_nombre, pedidos.fecha_pedido, pedidos.total
                FROM pedidos
                JOIN usuarios ON pedidos.id_usuario = usuarios.id
                ORDER BY pedidos.fecha_pedido DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos con clientes: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener todos los pedidos
    public static function obtenerTodos() {
        try {
            $db = DB::connect();
            $stmt = $db->query("SELECT * FROM pedidos");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todos los pedidos: " . $e->getMessage());
            return [];
        }
    }

    // Obtener pedidos de un usuario específico
    public static function obtenerPedidosPorUsuario($usuarioId) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("SELECT * FROM pedidos WHERE id_usuario = :usuario_id ORDER BY fecha_pedido DESC");
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos por usuario: " . $e->getMessage());
            return [];
        }
    }

    // Añadir un nuevo pedido
    public static function añadirPedido($usuarioId, $total) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("INSERT INTO pedidos (id_usuario, fecha_pedido, total) VALUES (:id_usuario, NOW(), :total)");
            $stmt->bindParam(':id_usuario', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':total', $total, PDO::PARAM_STR);
            $stmt->execute();
            return $db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al añadir pedido: " . $e->getMessage());
            return false;
        }
    }

    // Obtener detalles del pedido
    public static function obtenerDetallesPorPedido($idPedido) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("
                SELECT 
                    dp.id AS detalle_id,
                    a.nombre AS producto_nombre,
                    a.imagen AS producto_imagen,
                    dp.cantidad,
                    dp.precio_unitario,
                    (dp.cantidad * dp.precio_unitario) AS subtotal
                FROM detalle_pedido dp
                JOIN articulos a ON dp.id_articulo = a.id
                WHERE dp.id_pedido = :id_pedido
            ");
            $stmt->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles del pedido: " . $e->getMessage());
            return [];
        }
    }

    // Obtener pedido por ID
    public static function obtenerPedidoPorId($pedidoId) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("SELECT * FROM pedidos WHERE id = :id");
            $stmt->bindParam(':id', $pedidoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedido por ID: " . $e->getMessage());
            return null;
        }
    }

    // Añadir detalles del pedido en finalizar compra
    public static function añadirDetallePedido($pedidoId, $idProducto, $cantidad, $precioUnitario) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("INSERT INTO detalle_pedido (id_pedido, id_articulo, cantidad, precio_unitario) 
                                  VALUES (:id_pedido, :id_articulo, :cantidad, :precio_unitario)");
            $stmt->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
            $stmt->bindParam(':id_articulo', $idProducto, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':precio_unitario', $precioUnitario, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar detalle del pedido: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
