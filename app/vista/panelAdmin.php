<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header('Location: login.php'); // Redirige si no es un administrador
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Información del Administrador -->
    <div class="user-container">
        <div class="user-icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h2>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['usuario_email']); ?></p>
        <p><strong>Rol:</strong> <?php echo ucfirst(htmlspecialchars($_SESSION['usuario_rol'])); ?></p>
    </div>

    <!-- Opciones del Panel -->
    <div class="admin-panel-options">
        <div class="admin-option">
            <a href="clientesAdmin.php">
                <i class="fas fa-users"></i>
                <p>Ver Usuarios</p>
            </a>
        </div>
        <div class="admin-option">
            <a href="registroAdmin.php">
                <i class="fas fa-user-plus"></i>
                <p>Añadir Usuario</p>
            </a>
        </div>
        <div class="admin-option">
            <a href="pedidosAdmin.php">
                <i class="fas fa-shopping-cart"></i>
                <p>Ver Pedidos</p>
            </a>
        </div>
        <div class="admin-option">
        <a href="productosAdmin.php">
            <i class="fas fa-box-open"></i>
            <p>Ver Productos</p>
        </a>
        </div>
        <div class="admin-option">
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <p>Salir</p>
            </a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
