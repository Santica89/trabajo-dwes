function mostrarDetalles(idPedido) {
    const detallesRow = document.getElementById(`detalles-${idPedido}`);
    const detallesContenedor = detallesRow.querySelector('.detalles-contenedor');

    if (detallesRow.style.display === "none") {
        detallesRow.style.display = "table-row";

        if (!detallesContenedor.innerHTML.includes("Producto")) {
            fetch(`../controlador/pedidoControlador.php?action=obtenerDetalles&id=${idPedido}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let html = '<table class="admin-table">';
                        html += '<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr></thead><tbody>';

                        data.forEach(detalle => {
                            html += `
                                <tr>
                                    <td>${detalle.producto_nombre}</td>
                                    <td>${detalle.cantidad}</td>
                                    <td>${detalle.precio_unitario}€</td>
                                    <td>${detalle.subtotal}€</td>
                                </tr>
                            `;
                        });

                        html += '</tbody></table>';
                        detallesContenedor.innerHTML = html;
                    } else {
                        detallesContenedor.innerHTML = "No hay detalles disponibles para este pedido.";
                    }
                })
                .catch(error => {
                    detallesContenedor.innerHTML = "Error al cargar los detalles.";
                });
        }
    } else {
        detallesRow.style.display = "none";
    }
}
