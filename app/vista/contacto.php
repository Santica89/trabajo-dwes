<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="../../css/estilos.css?v=<?php echo time(); ?>">
    <link rel="icon" href="https://swiftstep.infinityfreeapp.com/img/favicon.png" type="image/png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="contact-container">
        <!-- Formulario de contacto -->
        <div class="contact-form">
            <h1 class="form-title">Contáctanos</h1>
            <form method="POST" action="../../app/controlador/contactoControlador.php" class="form-content">
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Introduce tu correo" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="asunto" class="form-label">Asunto:</label>
                    <input type="text" id="asunto" name="asunto" placeholder="Introduce el asunto" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="cuerpo" class="form-label">Mensaje:</label>
                    <textarea id="cuerpo" name="cuerpo" placeholder="Escribe tu mensaje" class="form-input" rows="4" required></textarea>
                </div>
                <button type="submit" class="form-button">Enviar</button>
            </form>
        </div>

        <!-- Información de contacto -->
        <div class="contact-info">
            <h2 class="info-title">Información de Contacto</h2>
            <p class="info-item"><strong>Teléfono:</strong> +34 123 456 789</p>
            <p class="info-item"><strong>Email:</strong> tienda@ejemplo.com</p>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.9950610063545!2d13.40495431587012!3d52.52000607981217!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a851eaffd7eaed%3A0x42c549ae7ffb00e2!2sBrandenburger%20Tor!5e0!3m2!1ses!2ses!4v1682472982365!5m2!1ses!2ses"
                    width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
