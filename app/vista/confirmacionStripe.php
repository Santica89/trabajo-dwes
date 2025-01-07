<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Cargar Stripe
require_once '../../vendor/autoload.php';
Stripe\Stripe::setApiKey('sk_test_51QcySSClQmgKZgz72VZFVrW5mId3CttkZHbW7lRcgW5ntIfErYJWbltEIaulHsswkvlYKAtf3juIepxl6dhkDfUs00zL6RySEF');

// Verificar el estado de la sesión de Stripe
$sessionId = $_GET['session_id'] ?? null;

if ($sessionId) {
    try {
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        if ($session && $session->payment_status === 'paid') {
            // Limpiar el carrito después del pago exitoso
            unset($_SESSION['carrito']);
            $mensaje = "¡Pago exitoso! Tu pedido ha sido procesado.";
        } else {
            $mensaje = "Hubo un problema con tu pago. Por favor, intenta nuevamente.";
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        $mensaje = "Error al verificar el pago: " . $e->getMessage();
    }
} else {
    $mensaje = "No se encontró información de la sesión de pago.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="confirmacion-container">
        <h1>Confirmación de Pago</h1>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
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