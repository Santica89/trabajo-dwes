<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="form-container">
        <h1 class="form-title">Registro</h1>
        <form method="POST" action="../controlador/registroControlador.php" class="form-content">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="form-button">Registrar</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
