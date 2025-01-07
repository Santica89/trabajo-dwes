<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="product-detail-container">
        <?php
        require_once '../modelo/config.php';
        session_start();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "<p class='alert'>Producto no encontrado.</p>";
        } else {
            try {
                $connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASSWORD);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Consulta para obtener el producto
                $query = $connection->prepare("SELECT * FROM articulos WHERE id = :id");
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                $producto = $query->fetch(PDO::FETCH_ASSOC);

                if ($producto) {
                    echo "
                    <div class='product-detail'>
                        <img src='../../img/{$producto['imagen']}' alt='{$producto['nombre']}' class='product-detail-image'>
                        <div class='product-detail-info'>
                            <h1 class='product-detail-name'>{$producto['nombre']}</h1>
                            <p class='product-detail-price'>" . number_format($producto['precio'], 2) . "€</p>
                            <div class='product-detail-description'>
                                <h2>Descripción</h2>
                                <p>{$producto['descripcion']}</p>
                            </div>";

                    // Verificar si el usuario ha iniciado sesión
                    if (isset($_SESSION['usuario_id'])) {
                        echo "
                        <form method='POST' action='../../app/controlador/carritoControlador.php'>
                            <input type='hidden' name='id' value='{$producto['id']}'>
                            <input type='hidden' name='nombre' value='{$producto['nombre']}'>
                            <input type='hidden' name='precio' value='{$producto['precio']}'>
                            <button type='submit' name='action' value='add' class='product-detail-button'>Agregar al carrito</button>
                        </form>";
                    } else {
                        echo "<p class='alert'>Debes <a href='../../app/vista/login.php'>iniciar sesión</a> para agregar productos al carrito.</p>";
                    }

                    echo "
                        </div>
                    </div>";
                } else {
                    echo "<p class='alert'>Producto no encontrado.</p>";
                }
            } catch (PDOException $e) {
                echo "<p class='alert'>Error al cargar producto: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
