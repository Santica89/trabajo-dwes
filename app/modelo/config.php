<?php
// Configuración de la base de datos
define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASSWORD', '');

// Función para establecer conexión con la base de datos
function conectarBD() {
    try {
        // Crear una nueva instancia de PDO
        $conexion = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER,
            DB_PASSWORD
        );
        // Configurar el manejo de errores
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conexion;
    } catch (PDOException $e) {
        // Mostrar mensaje de error en caso de fallo
        die('Error de conexión: ' . $e->getMessage());
    }
}
?>

