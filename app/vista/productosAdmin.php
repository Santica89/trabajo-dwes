<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es administrador o superadministrador
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['administrador', 'superadministrador'])) {
    header('Location: login.php'); // Redirige si no es un administrador
    exit();
}

require_once '../modelo/articuloModelo.php';

// Mensajes de éxito o error
$mensajeExito = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : null;
$mensajeError = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;

// Obtener la lista de productos
$productos = ArticuloModelo::obtenerTodosLosArticulos();

// Verificar si se está editando un producto
$producto = null;
if (isset($_GET['id'])) {
    $idProducto = intval($_GET['id']);
    $producto = ArticuloModelo::obtenerArticuloPorId($idProducto);
    if (!$producto) {
        header('Location: productosAdmin.php?error=' . urlencode('Producto no encontrado.'));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
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

        <!-- Formulario para Crear o Editar Producto -->
        <div class="form-container">
            <h2><?php echo $producto ? 'Editar Producto' : 'Añadir Nuevo Producto'; ?></h2>
            <form method="POST" action="../controlador/adminControlador.php">
                <input type="hidden" name="action" value="<?php echo $producto ? 'editarProducto' : 'crearProducto'; ?>">
                <?php if ($producto): ?>
                    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label class="form-label" for="nombre">Nombre del Producto:</label>
                    <input class="form-input" type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="descripcion">Descripción:</label>
                    <textarea class="form-input" id="descripcion" name="descripcion"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="precio">Precio (€):</label>
                    <input class="form-input" type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($producto['precio'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="stock">Cantidad en Stock:</label>
                    <input class="form-input" type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($producto['stock'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="imagen">URL de la Imagen:</label>
                    <input class="form-input" type="text" id="imagen" name="imagen" value="<?php echo htmlspecialchars($producto['imagen'] ?? ''); ?>">
                </div>
                <button type="submit" class="form-button"><?php echo $producto ? 'Guardar Cambios' : 'Añadir Producto'; ?></button>
            </form>
        </div>

        <!-- Tabla de Productos -->
        <div class="table-container">
            <h2>Lista de Productos</h2>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio (€)</th>
                            <th>Stock</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="7">No hay productos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($producto['id']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($producto['precio'], 2)); ?>€</td>
                                    <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                                    <td>
                                        <img src="../../img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del producto" class="product-image">
                                    </td>
                                    <td>
                                        <a href="productosAdmin.php?id=<?php echo $producto['id']; ?>" class="table-button edit">Editar</a> |
                                        <a href="../controlador/adminControlador.php?action=borrarProducto&id=<?php echo $producto['id']; ?>"
                                            onclick="return confirm('¿Estás seguro de eliminar este producto?');" class="table-button delete">Borrar</a>
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
