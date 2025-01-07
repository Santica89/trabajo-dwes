<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['administrador', 'superadministrador'])) {
    header('Location: login.php'); // Redirige si no es un administrador
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cliente</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'adminHeader.php'; ?>
    <div class="form-container">
        <h2>Añadir Nuevo Usuario</h2>
        <form method="POST" action="../controlador/adminControlador.php">
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre:</label>
                <input class="form-input" type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="apellidos">Apellidos:</label>
                <input class="form-input" type="text" id="apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Correo Electrónico:</label>
                <input class="form-input" type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="telefono">Teléfono:</label>
                <input class="form-input" type="tel" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="direccion">Dirección:</label>
                <input class="form-input" type="text" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Contraseña:</label>
                <input class="form-input" type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="rol">Rol:</label>
                <select class="form-input" id="rol" name="rol">
                    <option value="cliente">Cliente</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <button type="submit" name="action" value="addUser" class="form-button">Añadir Usuario</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
