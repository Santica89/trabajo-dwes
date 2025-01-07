<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="cart-container">
        <h1 class="cart-title">Carrito de Compras</h1>
        <?php
        $carrito = $_SESSION['carrito'] ?? [];
        if (empty($carrito)) {
            echo "<p class='cart-empty'>No hay productos en tu carrito.</p>";
        } else {
            echo "<div class='cart-table-container'>"; // Contenedor con scroll horizontal
            echo "<table class='cart-table'>";
            echo "<thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
              </thead>
              <tbody>";
            $total = 0;
            foreach ($carrito as $id => $producto) {
                $subtotal = $producto['precio'] * $producto['cantidad'];
                $total += $subtotal;
                echo "<tr>
                    <td>
                        <img src='../../img/" . htmlspecialchars($producto['imagen']) . "' alt='Imagen del Producto' class='carrito-img'>
                    </td>
                    <td>" . htmlspecialchars($producto['nombre']) . "</td>
                    <td>" . htmlspecialchars(number_format($producto['precio'], 2)) . "€</td>
                    <td>
                        <form method='POST' action='../../app/controlador/carritoControlador.php'>
                            <input type='hidden' name='id' value='{$id}'>
                            <input type='number' name='cantidad' value='" . htmlspecialchars($producto['cantidad']) . "' min='1' style='width: 50px;'>
                    </td>
                    <td>" . htmlspecialchars(number_format($subtotal, 2)) . "€</td>
                    <td>
                        <div class='cart-action-buttons'>
                            <button type='submit' name='action' value='update' class='cart-update-button'>Actualizar</button>
                            <button type='submit' name='action' value='delete' class='cart-delete-button'>Eliminar</button>
                        </div>
                        </form>
                    </td>
                  </tr>";
            }
            echo "</tbody>
              </table>";
            echo "</div>"; // Cierra el contenedor con scroll horizontal
            echo "<p class='cart-total'>Total: " . htmlspecialchars(number_format($total, 2)) . "€</p>";
        }
        ?>
        <!-- Botones -->
        <form method="POST" action="../../app/controlador/carritoControlador.php">
            <button type="submit" name="action" value="clear" class="clear-cart-button">Vaciar Carrito</button>
        </form>
        <form action="productos.php" method="GET">
            <button type="submit" class="form-button">Continuar Comprando</button>
        </form>
        <form action="finalizarCompra.php" method="GET">
            <button type="submit" class="form-button">Finalizar Compra</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>
