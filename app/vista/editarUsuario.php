<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header('Location: login.php'); // Redirige si no es un administrador
    exit();
}

require_once '../modelo/usuarioModelo.php';

// Verificar si se recibió el ID del usuario
if (!isset($_GET['id'])) {
    header('Location: admin.php?error=' . urlencode('ID de usuario no especificado.'));
    exit();
}

$id = intval($_GET['id']);
$usuario = UsuarioModelo::obtenerPorId($id);

if (!$usuario) {
    header('Location: admin.php?error=' . urlencode('Usuario no encontrado.'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="form-container">
        <h2>Editar Usuario</h2>
        <form method="POST" action="../controlador/adminControlador.php">
            <input type="hidden" name="action" value="editarUsuario">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">

            <div class="form-group">
                <label class="form-label" for="nombre">Nombre:</label>
                <input class="form-input" type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="apellidos">Apellidos:</label>
                <input class="form-input" type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Correo Electrónico:</label>
                <input class="form-input" type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="telefono">Teléfono:</label>
                <input class="form-input" type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="direccion">Dirección:</label>
                <input class="form-input" type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="rol">Rol:</label>
                <select class="form-input" id="rol" name="rol" required>
                    <option value="cliente" <?php echo $usuario['rol'] === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                    <option value="administrador" <?php echo $usuario['rol'] === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <button type="submit" class="form-button">Guardar Cambios</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
