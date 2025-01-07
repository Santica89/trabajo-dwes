<?php
require_once 'db.php';

class UsuarioModelo {
    // Obtener un usuario por su correo electrónico
    public static function obtenerPorEmail($email) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por email: " . $e->getMessage());
            return null;
        }
    }

    // Obtener un usuario por su ID
    public static function obtenerPorId($usuarioId) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
                error_log("Error al obtener usuario por ID: " . $e->getMessage());
                return null;
        }
    }
    
    // Registrar un usuario
    public static function registrarUsuario($nombre, $apellidos, $email, $password, $telefono, $direccion, $rol = 'cliente') {
        try {
            $db = DB::connect();
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Cifra la contraseña
    
            $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellidos, email, contraseña, telefono, direccion, rol) 
                                  VALUES (:nombre, :apellidos, :email, :password, :telefono, :direccion, :rol)");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR); // Usar la contraseña cifrada
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }       

    // Obtener todos los usuarios
    public static function obtenerTodosLosUsuarios() {
    try {
        $db = DB::connect();
        $stmt = $db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    // Editar los usuarios
    public static function editarUsuario($id, $nombre, $apellidos, $email, $telefono, $direccion, $rol) {
        try {
            $db = DB::connect();
    
            // Verificar si se está intentando cambiar el rol de un superadministrador
            $stmt = $db->prepare("SELECT rol FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($usuario['rol'] === 'superadministrador' && $rol !== 'superadministrador') {
                return false; // No permitir cambiar el rol de superadministrador
            }
    
            // Actualizar los datos del usuario
            $stmt = $db->prepare("UPDATE usuarios 
                                  SET nombre = :nombre, apellidos = :apellidos, email = :email, telefono = :telefono, direccion = :direccion, rol = :rol 
                                  WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al editar usuario: " . $e->getMessage());
            return false;
        }
    }  

    // Borrar usuario
    public static function borrarUsuario($id) {
        try {
            $db = DB::connect();
    
            // Verificar si el usuario a eliminar es un superadministrador
            $stmt = $db->prepare("SELECT rol FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($usuario['rol'] === 'superadministrador') {
                return false; // No permitir borrar al superadministrador
            }
    
            // Proceder a borrar si no es superadministrador
            $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar usuario: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
