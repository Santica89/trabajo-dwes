<?php
session_start();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Incluir el modelo del carrito
require_once '../modelo/carritoModelo.php';

// Capturar la acción del formulario
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'add':
        $id = intval($_POST['id']);
        // Obtener el producto desde el modelo
        $producto = CarritoModelo::obtenerProductoPorId($id);

        if ($producto) {
            // Si el producto ya está en el carrito, aumentar la cantidad
            if (isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id]['cantidad']++;
            } else {
                // Agregar el producto con todos sus datos
                $_SESSION['carrito'][$id] = [
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'imagen' => $producto['imagen'], // Incluir la imagen
                    'cantidad' => 1
                ];
            }
        }
        break;

    case 'update':
        $id = intval($_POST['id']);
        $cantidad = intval($_POST['cantidad']);
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] = $cantidad;
        }
        break;

    case 'delete':
        $id = intval($_POST['id']);
        unset($_SESSION['carrito'][$id]);
        break;

    case 'clear':
        $_SESSION['carrito'] = [];
        break;
}

header('Location: ../../app/vista/carrito.php');
exit;
