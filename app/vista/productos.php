<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar la sesión si aún no está iniciada
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="product-gallery">
        <h1 class="gallery-title">Nuestra Galería de Productos</h1>
        <?php
        if (!isset($_SESSION['usuario_id'])) {
            echo "<p class='alert'>Debes iniciar sesión para añadir productos al carrito.</p>";
        }
        ?>

        <div class="gallery-grid">
            <?php
            require_once '../modelo/config.php';

            try {
                $connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASSWORD);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Obtener productos de la base de datos
                $query = $connection->query("SELECT * FROM articulos");
                $productos = $query->fetchAll(PDO::FETCH_ASSOC);

                if (count($productos) === 0) {
                    echo "<p class='alert'>No hay productos disponibles.</p>";
                } else {
                    foreach ($productos as $producto) {
                        echo "
                        <div class='product-card'>
                            <a href='detalleProducto.php?id={$producto['id']}' class='product-link'>
                                <img src='../../img/{$producto['imagen']}' alt='{$producto['nombre']}' class='product-image'>
                                <h2 class='product-name'>{$producto['nombre']}</h2>
                                <p class='product-description'>{$producto['descripcion']}</p>
                                <p class='product-price'>" . number_format($producto['precio'], 2) . "€</p>
                            </a>
                            " . (isset($_SESSION['usuario_id']) ? "
                            <form method='POST' action='../../app/controlador/carritoControlador.php'>
                                <input type='hidden' name='id' value='{$producto['id']}'>
                                <input type='hidden' name='nombre' value='{$producto['nombre']}'>
                                <input type='hidden' name='precio' value='{$producto['precio']}'>
                                <button type='submit' name='action' value='add' class='product-button'>Agregar al carrito</button>
                            </form>
                            " : "") . "
                        </div>";
                    }
                }
            } catch (PDOException $e) {
                echo "<p class='alert'>Error al cargar productos: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
