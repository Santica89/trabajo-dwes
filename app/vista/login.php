<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar la sesión si aún no está iniciada
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <!-- Header -->
    <?php include '../../app/vista/header.php'; ?>

    <!-- Contenido principal -->
    <div class="form-container">
        <h1 class="form-title">Iniciar Sesión</h1>

        <!-- Mostrar mensaje de error -->
        <?php if (isset($_SESSION['error_login'])): ?>
            <p class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_login']); ?></p>
            <?php unset($_SESSION['error_login']); // Limpiar el mensaje de error ?>
        <?php endif; ?>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($_GET['mensaje'])): ?>
            <p class="alert alert-success"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form method="POST" action="../../app/controlador/loginControlador.php" class="form-content">
            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" id="email" name="email" placeholder="Introduce tu email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" class="form-input" required>
            </div>
            <button type="submit" class="form-button">Entrar</button>
        </form>

        <p class="form-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>

    <!-- Footer -->
    <?php include '../../app/vista/footer.php'; ?>
</body>
</html>
