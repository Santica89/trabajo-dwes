<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['administrador', 'superadministrador'])) {
    header('Location: login.php'); // Redirige si no es un administrador
    exit();
}

require_once '../modelo/usuarioModelo.php';

// Mensajes de éxito o error
$mensajeExito = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : null;
$mensajeError = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;

// Obtener la lista de clientes
$usuarios = UsuarioModelo::obtenerTodosLosUsuarios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
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

        <!-- Contenedor de la Tabla de Clientes -->
        <div class="table-container">
            <h2>Lista de Clientes</h2>
            <!-- Contenedor para el scroll horizontal -->
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="5">No hay clientes registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td><?php echo ucfirst(htmlspecialchars($usuario['rol'])); ?></td>
                                    <td>
                                        <a href="editarUsuario.php?id=<?php echo $usuario['id']; ?>" class="table-button edit">Editar</a> |
                                        <a href="../controlador/adminControlador.php?action=borrarUsuario&id=<?php echo $usuario['id']; ?>"
                                            onclick="return confirm('¿Estás seguro de que deseas borrar este usuario?');" class="table-button delete">Borrar</a>
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