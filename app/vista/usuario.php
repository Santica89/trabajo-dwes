<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado y es cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'cliente') {
    header('Location: login.php'); // Redirigir si no es un cliente
    exit();
}

require_once '../modelo/usuarioModelo.php';
$usuario = UsuarioModelo::obtenerPorId($_SESSION['usuario_id']); // Obtener datos del usuario desde el modelo
require_once '../modelo/pedidoModelo.php';
$pedidos = PedidoModelo::obtenerPedidosPorUsuario($_SESSION['usuario_id']); // Pedidos del cliente
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <script src="../../js/detallesPedido.js?v=<?php echo time(); ?>" defer></script>
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="user-container">
        <!-- Información del usuario -->
        <div class="user-info">
            <div class="user-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion']); ?></p>
        </div>
    </div>

    <!-- Tabla de Pedidos -->
    <div class="user-container">
        <h2>Mis Pedidos</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="4">No tienes pedidos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($pedido['total'], 2)); ?>€</td>
                            <td>
                                <a href="javascript:void(0);" class="table-button edit" onclick="mostrarDetalles(<?php echo $pedido['id']; ?>)">Ver Detalles</a>
                            </td>
                        </tr>
                        <!-- Contenedor para los detalles -->
                        <tr id="detalles-<?php echo $pedido['id']; ?>" class="detalles-row" style="display: none;">
                            <td colspan="4">
                                <div class="detalles-contenedor">Cargando detalles...</div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
