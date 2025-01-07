function mostrarBoton(metodo) {
    // Ocultar todos los botones
    document.getElementById('boton-transferencia').style.display = 'none';
    document.getElementById('boton-stripe').style.display = 'none';

    // Mostrar el botón asociado al método seleccionado
    if (metodo === 'transferencia') {
        document.getElementById('boton-transferencia').style.display = 'block';
    } else if (metodo === 'stripe') {
        document.getElementById('boton-stripe').style.display = 'block';
    }
}
