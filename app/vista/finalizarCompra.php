<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar si el carrito tiene productos
$carrito = $_SESSION['carrito'] ?? [];
if (empty($carrito)) {
    header('Location: carrito.php?error=' . urlencode('El carrito está vacío.'));
    exit();
}

// Obtener las imágenes asociadas al carrito desde la base de datos
require_once '../modelo/articuloModelo.php';
foreach ($carrito as $id => &$producto) {
    $articulo = ArticuloModelo::obtenerArticuloPorId($id);
    $producto['imagen'] = $articulo['imagen'] ?? 'default-image.png';
}

// Calcular el total del carrito
$total = array_reduce($carrito, function ($carry, $item) {
    return $carry + ($item['precio'] * $item['cantidad']);
}, 0);

// Obtener los datos del usuario
require_once '../modelo/usuarioModelo.php';
$usuario = UsuarioModelo::obtenerPorId($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">

</head>

<body>
    <?php include 'header.php'; ?>

    <div class="finalizar-compra-container">
        <!-- Detalles del Cliente -->
        <div class="cliente-info">
            <h2>Detalles del Cliente</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion']); ?></p>
        </div>

        <!-- Resumen del Carrito -->
        <div class="carrito-resumen">
            <h2>Resumen del Carrito</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>
                                <img src="../../img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del Producto" class="carrito-img">
                            </td>
                            <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($producto['precio'], 2)); ?>€</td>
                            <td><?php echo htmlspecialchars(number_format($producto['precio'] * $producto['cantidad'], 2)); ?>€</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong><?php echo number_format($total, 2); ?>€</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Métodos de Pago -->
        <div class="metodos-pago">
            <h2>Métodos de Pago</h2>
            <label class="metodo-pago">
                <input type="radio" name="metodo_pago" value="transferencia" onclick="mostrarBoton('transferencia')" required>
                <i class="fas fa-university"></i> Transferencia Bancaria
            </label>
            <label class="metodo-pago">
                <input type="radio" name="metodo_pago" value="stripe" onclick="mostrarBoton('stripe')">
                <i class="fas fa-credit-card"></i> Stripe (Pago Seguro)
            </label>
        </div>

        <!-- Botón para Transferencia -->
        <form method="POST" action="../controlador/pedidoControlador.php" class="form-compra" id="boton-transferencia" style="display: none;">
            <input type="hidden" name="action" value="crearPedido">
            <input type="hidden" name="metodo_pago" value="transferencia">
            <button type="submit" class="form-button">Pagar con Transferencia</button>
        </form>

        <!-- Botón para Stripe -->
        <form method="POST" action="../controlador/pedidoControlador.php" class="form-compra" id="boton-stripe" style="display: none;">
            <input type="hidden" name="action" value="crearPedido">
            <input type="hidden" name="metodo_pago" value="stripe">
            <button type="submit" class="form-button">Pagar con Stripe</button>
        </form>

        <!-- Botón Volver al Carrito -->
        <form action="carrito.php" method="GET">
            <button type="submit" class="form-button">Volver al Carrito</button>
        </form>
    </div>

    </div>

    <?php include 'footer.php'; ?>
    <script src="../../js/finalizarCompra.js"></script>

</body>

</html>