<?php
require_once '../modelo/usuarioModelo.php'; // Importar el modelo de usuario

// Verificar si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    // Validar campos vacíos
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password) || empty($telefono) || empty($direccion)) {
        header('Location: ../vista/registro.php?error=' . urlencode('Todos los campos son obligatorios.'));
        exit();
    }

    // Comprobar si el correo ya está registrado
    if (UsuarioModelo::obtenerPorEmail($email)) {
        header('Location: ../vista/registro.php?error=' . urlencode('El correo ya está registrado.'));
        exit();
    }

    // Registrar al usuario
    $registrado = UsuarioModelo::registrarUsuario($nombre, $apellidos, $email, $password, $telefono, $direccion);

    if ($registrado) {
        // Redirigir al login con mensaje de éxito
        header('Location: ../vista/login.php?mensaje=' . urlencode('Registro exitoso. Por favor, inicia sesión.'));
    } else {
        // Redirigir al registro con mensaje de error
        header('Location: ../vista/registro.php?error=' . urlencode('Error al registrar. Intenta nuevamente.'));
    }
    exit();
}
?>
