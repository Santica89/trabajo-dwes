<?php
require_once '../modelo/usuarioModelo.php'; // Modelo para consultas a la base de datos

// Función para procesar el inicio de sesión
function iniciarSesion() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Capturar datos del formulario
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Validar campos vacíos
        if (empty($email) || empty($password)) {
            redirigirConError('Los campos no pueden estar vacíos.');
            return;
        }

        // Obtener usuario de la base de datos
        $usuario = UsuarioModelo::obtenerPorEmail($email);

        // Verificar si el usuario existe y la contraseña es válida
        if ($usuario && password_verify($password, $usuario['contraseña'])) { // Validar contraseña
            // Iniciar sesión
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre']; // Guardar el nombre del usuario
            $_SESSION['usuario_email'] = $usuario['email'];   // Guardar el email del usuario
            $_SESSION['usuario_rol'] = $usuario['rol'];       // Guardar el rol del usuario

            // Redirigir según el rol
            if ($usuario['rol'] === 'administrador') {
                header('Location: ../vista/panelAdmin.php'); // Página para administradores
            } elseif ($usuario['rol'] === 'superadministrador') {
                header('Location: ../vista/panelSuperadmin.php'); // Página para superadministradores
            } elseif ($usuario['rol'] === 'cliente') {
                header('Location: ../vista/usuario.php'); // Página para clientes
            } else {
                // Si el rol no tiene una página específica, redirigir a home
                header('Location: ../../index.php?mensaje=Bienvenido ' . urlencode($usuario['nombre']));
            }
            exit();
        } else {
            // Credenciales incorrectas
            redirigirConError('Credenciales incorrectas.');
        }
    }
}

// Función para redirigir con un mensaje de error
function redirigirConError($mensaje) {
    session_start(); // Asegúrate de iniciar la sesión
    $_SESSION['error_login'] = $mensaje; // Establecer el mensaje de error
    header('Location: ../vista/login.php'); // Redirigir al login
    exit();
}

// Ejecutar la función de inicio de sesión
iniciarSesion();
