<?php
session_start();
require_once 'app/modelo/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Tienda Online</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'app/vista/header.php'; ?>
    
    <!-- Banner principal -->
    <div class="home-banner">
        <h1>¡Bienvenido a Tienda Online!</h1>
        <p>Descubre los mejores productos deportivos a los mejores precios.</p>
        <a href="app/vista/productos.php" class="banner-button">Explorar Productos</a>
    </div>

    <!-- Mensaje de bienvenida desde el login -->
    <div class="welcome-container">
        <?php
        if (isset($_GET['mensaje'])) {
            echo "<p class='welcome-message'>" . htmlspecialchars($_GET['mensaje']) . "</p>";
        }
        ?>
    </div>
    <!-- Productos destacados -->
    <div class="featured-products">
        <h2>Productos Destacados</h2>
        <div class="product-grid">
            <?php
            try {
                $connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASSWORD);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $connection->query("SELECT * FROM articulos LIMIT 4");
                $productos = $query->fetchAll(PDO::FETCH_ASSOC);

                foreach ($productos as $producto) {
                    echo "
                    <div class='product-card'>
                        <a href='app/vista/detalleProducto.php?id={$producto['id']}' class='product-link'>
                            <img src='img/{$producto['imagen']}' alt='{$producto['nombre']}' class='product-image'>
                            <h3 class='product-name'>{$producto['nombre']}</h3>
                            <p class='product-price'>" . number_format($producto['precio'], 2) . "€</p>
                        </a>
                    </div>";
                }
            } catch (PDOException $e) {
                echo "<p>Error al cargar productos destacados: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </div>

    <!-- Descripción de la tienda -->
    <div class="store-description">
        <h2>Sobre Nosotros</h2>
        <p>
            En Tienda Online ofrecemos una amplia gama de productos deportivos de alta calidad
            a precios asequibles. Nuestro compromiso es ayudarte a alcanzar tus metas, ya sea
            entrenar más duro, correr más rápido o simplemente llevar un estilo de vida activo.
        </p>
    </div>

    <?php include 'app/vista/footer.php'; ?>
</body>
</html>
