<?php
require_once '../modelo/pedidoModelo.php'; // Modelo de pedidos
require_once '../../vendor/autoload.php'; // Autoload de Stripe

use Stripe\Stripe;
use Stripe\Checkout\Session;

// Configurar la clave secreta de Stripe
Stripe::setApiKey('sk_test_51QcySSClQmgKZgz72VZFVrW5mId3CttkZHbW7lRcgW5ntIfErYJWbltEIaulHsswkvlYKAtf3juIepxl6dhkDfUs00zL6RySEF');

// Definir URL base
$base_url = 'https://swiftstep.infinityfreeapp.com/';

// Obtener detalles del pedido
if (isset($_GET['action']) && $_GET['action'] === 'obtenerDetalles' && isset($_GET['id'])) {
    $idPedido = intval($_GET['id']);

    // Obtener los detalles del pedido
    $detalles = PedidoModelo::obtenerDetallesPorPedido($idPedido);

    header('Content-Type: application/json');
    echo json_encode($detalles);
    exit();
}

// Crear un nuevo pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'crearPedido') {
    session_start();

    $metodoPago = $_POST['metodo_pago'] ?? '';
    $carrito = $_SESSION['carrito'] ?? [];

    if (empty($carrito)) {
        header('Location: ../vista/carrito.php?error=' . urlencode('El carrito está vacío.'));
        exit();
    }

    // Calcular el total del pedido
    $total = array_reduce($carrito, function ($carry, $item) {
        return $carry + ($item['precio'] * $item['cantidad']);
    }, 0);

    // Insertar el pedido en la tabla `pedidos`
    $pedidoId = PedidoModelo::añadirPedido($_SESSION['usuario_id'], $total);

    if (!$pedidoId) {
        header('Location: ../vista/finalizarCompra.php?error=' . urlencode('Error al procesar el pedido.'));
        exit();
    }

    // Insertar detalles del pedido
    foreach ($carrito as $idProducto => $producto) {
        PedidoModelo::añadirDetallePedido($pedidoId, $idProducto, $producto['cantidad'], $producto['precio']);
    }

    // Redirigir según el método de pago
    switch ($metodoPago) {
    case 'transferencia':
        // Limpiar el carrito después de finalizar el pedido
        unset($_SESSION['carrito']);
        header('Location: ../vista/confirmacionTransferencia.php?id=' . $pedidoId);
        exit();

    case 'stripe':
        try {
    // Configuración de Stripe Checkout
            $line_items = [];
            foreach ($carrito as $producto) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => $producto['nombre']],
                        'unit_amount' => intval($producto['precio'] * 100), // Convertir a centavos
                    ],
                    'quantity' => $producto['cantidad'],
                ];
            }

            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => 'https://swiftstep.infinityfreeapp.com/app/vista/confirmacionStripe.php?id=' . $pedidoId . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'https://swiftstep.infinityfreeapp.com/app/vista/finalizarCompra.php',
            ]);

            // Limpiar el carrito antes de redirigir
            unset($_SESSION['carrito']);
            header('Location: ' . $checkout_session->url);
            exit();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Error en Stripe: " . $e->getMessage());
            header('Location: ../vista/finalizarCompra.php?error=' . urlencode('Error al procesar el pago con Stripe.'));
            exit();
        }

    default:
        header('Location: ../vista/finalizarCompra.php?error=' . urlencode('Método de pago no válido.'));
        exit();
        }

}

// Redirigir en caso de acceso no válido
header('Location: ' . $base_url . 'index.php');
exit();
