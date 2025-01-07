<?php
require_once '../modelo/usuarioModelo.php'; // Cargar el modelo de usuario
require_once '../modelo/pedidoModelo.php'; // Cargar el modelo de pedidos
require_once '../modelo/articuloModelo.php'; // Cargar el modelo de artículos

// Añadir usuario en panelAdmin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addUser') {
    // Capturar datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $password = trim($_POST['password']);
    $rol = trim($_POST['rol']);

    // Validar campos vacíos
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($telefono) || empty($direccion) || empty($password) || empty($rol)) {
        header('Location: ../vista/clientesAdmin.php?error=' . urlencode('Todos los campos son obligatorios.'));
        exit();
    }
    // Llamar al modelo para registrar el usuario
    $registrado = UsuarioModelo::registrarUsuario($nombre, $apellidos, $email, $password, $telefono, $direccion, $rol);

    if ($registrado) {
        // Redirigir con mensaje de éxito
        header('Location: ../vista/clientesAdmin.php?success=' . urlencode('Usuario añadido correctamente.'));
    } else {
        // Redirigir con mensaje de error
        header('Location: ../vista/clientesAdmin.php?error=' . urlencode('Error al añadir el usuario.'));
    }
    exit();
}

// Editar usuario en panelAdmin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'editarUsuario') {
        $id = intval($_POST['id']);
        $nombre = trim($_POST['nombre']);
        $apellidos = trim($_POST['apellidos']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        $rol = trim($_POST['rol']);
        // Validar campos vacíos
        if (empty($id) || empty($nombre) || empty($apellidos) || empty($email) || empty($telefono) || empty($direccion) || empty($rol)) {
            header('Location: ../vista/clientesAdmin.php?error=' . urlencode('Todos los campos son obligatorios.'));
            exit();
        }
        // Llamar al modelo para editar el usuario
        $resultado = UsuarioModelo::editarUsuario($id, $nombre, $apellidos, $email, $telefono, $direccion, $rol);

        if ($resultado) {
            header('Location: ../vista/clientesAdmin.php?success=' . urlencode('Usuario actualizado correctamente.'));
        } else {
            header('Location: ../vista/clientesAdmin.php?error=' . urlencode('Error al actualizar el usuario.'));
        }
        exit();
    }
}

// Acción para borrar un usuario en panelAdmin
if (isset($_GET['action']) && $_GET['action'] === 'borrarUsuario' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Llamar al modelo para borrar el usuario
    $resultado = UsuarioModelo::borrarUsuario($id);
    if ($resultado) {
        header('Location: ../vista/clientesAdmin.php?success=' . urlencode('Usuario eliminado correctamente.'));
    } else {
        header('Location: ../vista/clientesAdmin.php?error=' . urlencode('Error al eliminar el usuario.'));
    }
    exit();
}

// Acción para borrar un pedido en panelAdmin
if (isset($_GET['action']) && $_GET['action'] === 'borrarPedido' && isset($_GET['id'])) {
    $pedidoId = intval($_GET['id']);
    // Llamar al modelo para borrar el pedido
    $resultado = PedidoModelo::borrarPedido($pedidoId);

    if ($resultado) {
        header('Location: ../vista/pedidosAdmin.php?success=' . urlencode('Pedido eliminado correctamente.'));
    } else {
        header('Location: ../vista/pedidosAdmin.php?error=' . urlencode('Error al eliminar el pedido.'));
    }
    exit();
}

// Borrar un producto en panelAdmin
if (isset($_GET['action']) && $_GET['action'] === 'borrarProducto' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Validar el ID
    if (empty($id)) {
        header('Location: ../vista/productosAdmin.php?error=' . urlencode('ID del producto no válido.'));
        exit();
    }

    // Llamar al modelo para borrar el producto
    $resultado = ArticuloModelo::borrarArticulo($id);

    if ($resultado) {
        header('Location: ../vista/productosAdmin.php?success=' . urlencode('Producto eliminado correctamente.'));
    } else {
        header('Location: ../vista/productosAdmin.php?error=' . urlencode('Error al eliminar el producto.'));
    }
    exit();
}

// Gestionar productos en panelAdmin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            // Crear un nuevo producto
            case 'crearProducto':
                $nombre = trim($_POST['nombre']);
                $descripcion = trim($_POST['descripcion']);
                $precio = floatval($_POST['precio']);
                $stock = intval($_POST['stock']);
                $imagen = trim($_POST['imagen']);

                if (empty($nombre) || $precio <= 0 || $stock < 0) {
                    header('Location: ../vista/productosAdmin.php?error=' . urlencode('Todos los campos obligatorios deben ser completados correctamente.'));
                    exit();
                }

                $resultado = ArticuloModelo::crearArticulo($nombre, $descripcion, $precio, $stock, $imagen);

                if ($resultado) {
                    header('Location: ../vista/productosAdmin.php?success=' . urlencode('Producto creado correctamente.'));
                } else {
                    header('Location: ../vista/productosAdmin.php?error=' . urlencode('Error al crear el producto.'));
                }
                exit();

            // Editar un producto existente
            case 'editarProducto':
                $id = intval($_POST['id']);
                $nombre = trim($_POST['nombre']);
                $descripcion = trim($_POST['descripcion']);
                $precio = floatval($_POST['precio']);
                $stock = intval($_POST['stock']);
                $imagen = trim($_POST['imagen']);

                if (empty($id) || empty($nombre) || $precio <= 0 || $stock < 0) {
                    header('Location: ../vista/productosAdmin.php?error=' . urlencode('Todos los campos obligatorios deben ser completados correctamente.'));
                    exit();
                }

                $resultado = ArticuloModelo::editarArticulo($id, $nombre, $descripcion, $precio, $stock, $imagen);

                if ($resultado) {
                    header('Location: ../vista/productosAdmin.php?success=' . urlencode('Producto actualizado correctamente.'));
                } else {
                    header('Location: ../vista/productosAdmin.php?error=' . urlencode('Error al actualizar el producto.'));
                }
                exit();
        }
    }
}

// Redirigir a la tabla en caso de acceso directo no permitido
header('Location: ../vista/clientesAdmin.php');
exit();
