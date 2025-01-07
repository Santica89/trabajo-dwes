<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener los datos del pedido
require_once '../modelo/pedidoModelo.php';
$pedidoId = intval($_GET['id']);
$pedido = PedidoModelo::obtenerPedidoPorId($pedidoId);

if (!$pedido || $pedido['id_usuario'] !== $_SESSION['usuario_id']) {
    header('Location: pedidosCliente.php?error=' . urlencode('Pedido no encontrado.'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Transferencia</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="confirmacion-container">
        <h2>Pedido Confirmado</h2>
        <p>Gracias por tu compra. Por favor, realiza una transferencia bancaria a la siguiente cuenta:</p>
        <p class="account-number">ES91 2100 0418 4502 0005 1332</p>
        <p><strong>Referencia del pedido:</strong> <?php echo htmlspecialchars($pedido['id']); ?></p>
        <p><strong>Total a pagar:</strong> <?php echo htmlspecialchars(number_format($pedido['total'], 2)); ?>€</p>
        <div class="botones-container">
            <?php
            // Determinar la redirección según el rol del usuario
            $redireccion = ($_SESSION['usuario_rol'] === 'administrador' || $_SESSION['usuario_rol'] === 'superadministrador') ? 'panelAdmin.php' : 'usuario.php';
            ?>
            <a href="<?php echo $redireccion; ?>" class="form-button">Volver a Mis Pedidos</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>