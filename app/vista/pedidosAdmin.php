<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['administrador', 'superadministrador'])) {
    header('Location: login.php'); // Redirige si no es un administrador
    exit();
}

require_once '../modelo/pedidoModelo.php'; // Modelo para pedidos

// Obtener los pedidos
$pedidos = PedidoModelo::obtenerTodosConClientes(); // Nueva función en pedidoModelo

// Mensajes de éxito o error
$mensajeExito = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : null;
$mensajeError = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>

<body>
    <?php include 'header.php'; ?>
    <?php include 'adminHeader.php'; ?>
    <div class="admin-container">
        <!-- Mensajes de éxito o error -->
        <?php if ($mensajeExito): ?>
            <div class="alert alert-success">
                <?php echo $mensajeExito; ?>
            </div>
        <?php endif; ?>
        <?php if ($mensajeError): ?>
            <div class="alert alert-danger">
                <?php echo $mensajeError; ?>
            </div>
        <?php endif; ?>

        <!-- Tabla de Pedidos -->
        <div class="table-container">
            <h2>Lista de Pedidos</h2>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="5">No hay pedidos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pedido['pedido_id']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['cliente_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($pedido['total'], 2)); ?>€</td>
                                    <td>
                                        <a href="detallePedido.php?id=<?php echo $pedido['pedido_id']; ?>" class="table-button edit">Ver Detalles</a> |
                                        <a href="../controlador/adminControlador.php?action=borrarPedido&id=<?php echo $pedido['pedido_id']; ?>"
                                            onclick="return confirm('¿Estás seguro de que deseas borrar este pedido?');" class="table-button delete">Borrar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>
