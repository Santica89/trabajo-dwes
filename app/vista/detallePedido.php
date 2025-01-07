<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['administrador', 'superadministrador'])) {
    header('Location: login.php'); // Redirige si no es un administrador
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: pedidosAdmin.php?error=' . urlencode('ID de pedido no especificado.'));
    exit();
}

require_once '../modelo/pedidoModelo.php';

// Obtener el ID del pedido desde la URL
$idPedido = intval($_GET['id']);

// Obtener los detalles del pedido
$detalles = PedidoModelo::obtenerDetallesPorPedido($idPedido);

// Verificar si el pedido tiene detalles
if (empty($detalles)) {
    $mensajeError = 'No hay detalles para este pedido.';
} else {
    $mensajeExito = 'Detalles cargados correctamente.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="admin-container">
        <h2>Detalles del Pedido #<?php echo htmlspecialchars($idPedido); ?></h2>

        <!-- Mensajes de éxito o error -->
        <?php if (!empty($mensajeExito)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($mensajeExito); ?>
            </div>
        <?php elseif (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($mensajeError); ?>
            </div>
        <?php endif; ?>

        <!-- Tabla de Detalles -->
        <div class="table-container">
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Foto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detalles)): ?>
                            <?php foreach ($detalles as $detalle): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detalle['producto_nombre']); ?></td>
                                    <td><img src="../../img/<?php echo htmlspecialchars($detalle['producto_imagen']); ?>" alt="<?php echo htmlspecialchars($detalle['producto_nombre']); ?>" class="product-image"></td>
                                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                                    <td><?php echo number_format($detalle['precio_unitario'], 2); ?>€</td>
                                    <td><?php echo number_format($detalle['subtotal'], 2); ?>€</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="back-button-container">
                <a href="pedidosAdmin.php" class="back-button">Volver</a>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
