<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = "https://swiftstep.infinityfreeapp.com/";
?>
<header>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="logo">
        <a href="<?php echo $base_url; ?>index.php">
            <img src="<?php echo $base_url; ?>img/logo-tienda.png" alt="Logo de StrideGlow" class="logo-image">
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="<?php echo $base_url; ?>index.php"><i class="fas fa-home"></i></a></li>
            <li><a href="<?php echo $base_url; ?>app/vista/productos.php">Productos</a></li>
            <li><a href="<?php echo $base_url; ?>app/vista/contacto.php">Contacto</a></li>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <?php if ($_SESSION['usuario_rol'] === 'administrador'): ?>
                    <li><a href="<?php echo $base_url; ?>app/vista/panelAdmin.php"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                <?php elseif ($_SESSION['usuario_rol'] === 'cliente'): ?>
                    <li><a href="<?php echo $base_url; ?>app/vista/usuario.php"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                <?php elseif ($_SESSION['usuario_rol'] === 'superadministrador'): ?>
                    <li><a href="<?php echo $base_url; ?>app/vista/panelSuperadmin.php"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                <?php endif; ?>
                <li><a href="<?php echo $base_url; ?>app/vista/logout.php">Salir</a></li>
            <?php else: ?>
                <li><a href="<?php echo $base_url; ?>app/vista/login.php">Acceder</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="cart-icon">
        <a href="<?php echo $base_url; ?>app/vista/carrito.php">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">
                <?php echo isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0; ?>
            </span>
        </a>
    </div>
</header>
