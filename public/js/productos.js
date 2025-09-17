const ProductosApp = {
    productos: [],

    // Instancias de modales
    modalNuevo: null,
    modalEditar: null,
    modalDetalle: null,

    getToken() {
        const token = document.getElementById('tokenInput').value.trim();
        if (!token) {
            alert('Necesitas ingresar un token.');
            return null;
        }
        return token;
    },

    initModals() {
        this.modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoProducto'));
        this.modalEditar = new bootstrap.Modal(document.getElementById('modalEditarProducto'));
        this.modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalleProducto'));
    },

    cargarProductos() {
        const token = this.getToken();
        if (!token) return;

        fetch('/api/productos', {
            headers: { 'Authorization': 'Bearer ' + token }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                this.productos = res.data;
                this.renderTabla(this.productos);
            } else {
                alert(res.message || 'Error al obtener productos.');
            }
        })
        .catch(err => console.error(err));
    },

    buscarProducto() {
        const token = this.getToken();
        if (!token) return;

        const valor = document.getElementById('buscarProducto').value.trim();
        if (!valor) {
            alert('Ingresa un ID o SKU para buscar.');
            return;
        }

        let producto;
        if (!isNaN(valor)) {
            producto = this.productos.find(p => p.id == valor);
        } else {
            producto = this.productos.find(p => p.sku.toLowerCase() === valor.toLowerCase());
        }

        if (producto) {
            this.renderTabla([producto]);
        } else {
            alert('Producto no encontrado.');
        }
    },

    renderTabla(productos) {
        if (!Array.isArray(productos)) return;

        const tbody = document.querySelector('#productosTable tbody');
        tbody.innerHTML = '';

        productos.forEach(producto => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${producto.id}</td>
                <td>${producto.sku}</td>
                <td>${producto.nombre}</td>
                <td>${producto.precio_neto}</td>
                <td>${producto.precio_venta}</td>
                <td>${producto.stock_actual}</td>
                <td>
                    <button class="btn btn-sm btn-info detalleBtn">Detalle</button>
                    <button class="btn btn-sm btn-warning editarBtn">Editar</button>
                    <button class="btn btn-sm btn-danger eliminarBtn">Eliminar</button>
                </td>
            `;

            // Botón Detalle
            tr.querySelector('.detalleBtn').addEventListener('click', () => {
                document.getElementById('detalleSku').innerText = producto.sku;
                document.getElementById('detalleNombre').innerText = producto.nombre;
                document.getElementById('detallePrecioNeto').innerText = producto.precio_neto;
                document.getElementById('detallePrecioVenta').innerText = producto.precio_venta;
                document.getElementById('detalleStockActual').innerText = producto.stock_actual;
                document.getElementById('detalleDescripcionCorta').innerText = producto.descripcion_corta;
                document.getElementById('detalleDescripcionLarga').innerText = producto.descripcion_larga;
                document.getElementById('detalleImagen').src = producto.imagen_url;

                this.modalDetalle.show();
            });

            // Botón Editar
            tr.querySelector('.editarBtn').addEventListener('click', () => {
                this.showEditModal(producto);
            });

            // Botón Eliminar
            tr.querySelector('.eliminarBtn').addEventListener('click', () => {
                const token = this.getToken();
                if (!token) return;
                if (confirm('¿Estás seguro de eliminar este producto?')) {
                    this.deleteProducto(producto.id);
                }
            });

            tbody.appendChild(tr);
        });
    },

    showEditModal(producto) {
        document.getElementById('editarId').value = producto.id;
        document.getElementById('editarSku').value = producto.sku; // No editable
        document.getElementById('editarNombre').value = producto.nombre;
        document.getElementById('editarDescripcionCorta').value = producto.descripcion_corta;
        document.getElementById('editarDescripcionLarga').value = producto.descripcion_larga;
        document.getElementById('editarImagenUrl').value = producto.imagen_url;
        document.getElementById('editarPrecioNeto').value = producto.precio_neto;
        document.getElementById('editarStockActual').value = producto.stock_actual;
        document.getElementById('editarStockMinimo').value = producto.stock_minimo;
        document.getElementById('editarStockBajo').value = producto.stock_bajo;
        document.getElementById('editarStockAlto').value = producto.stock_alto;

        this.modalEditar.show();
    },

    saveProducto() {
        const token = this.getToken();
        if (!token) return;

        const data = {
            sku: document.getElementById('nuevoSku').value.trim(),
            nombre: document.getElementById('nuevoNombre').value.trim(),
            descripcion_corta: document.getElementById('nuevoDescripcionCorta').value.trim(),
            descripcion_larga: document.getElementById('nuevoDescripcionLarga').value.trim(),
            imagen_url: document.getElementById('nuevoImagenUrl').value.trim(),
            precio_neto: parseFloat(document.getElementById('nuevoPrecioNeto').value),
            stock_actual: parseInt(document.getElementById('nuevoStockActual').value),
            stock_minimo: parseInt(document.getElementById('nuevoStockMinimo').value),
            stock_bajo: parseInt(document.getElementById('nuevoStockBajo').value),
            stock_alto: parseInt(document.getElementById('nuevoStockAlto').value),
        };

        fetch('/api/productos', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Producto creado correctamente.');
                this.modalNuevo.hide();
                this.cargarProductos();
            } else {
                alert(res.message || JSON.stringify(res.errors));
            }
        })
        .catch(err => console.error(err));
    },

    updateProducto() {
        const token = this.getToken();
        if (!token) return;

        const id = document.getElementById('editarId').value;
        const data = {
            nombre: document.getElementById('editarNombre').value.trim(),
            descripcion_corta: document.getElementById('editarDescripcionCorta').value.trim(),
            descripcion_larga: document.getElementById('editarDescripcionLarga').value.trim(),
            imagen_url: document.getElementById('editarImagenUrl').value.trim(),
            precio_neto: parseFloat(document.getElementById('editarPrecioNeto').value),
            stock_actual: parseInt(document.getElementById('editarStockActual').value),
            stock_minimo: parseInt(document.getElementById('editarStockMinimo').value),
            stock_bajo: parseInt(document.getElementById('editarStockBajo').value),
            stock_alto: parseInt(document.getElementById('editarStockAlto').value),
        };

        fetch('/api/productos/' + id, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Producto actualizado correctamente.');
                this.modalEditar.hide();
                this.cargarProductos();
            } else {
                alert(res.message || JSON.stringify(res.errors));
            }
        })
        .catch(err => console.error(err));
    },

    deleteProducto(id) {
        const token = this.getToken();
        if (!token) return;

        fetch('/api/productos/' + id, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Producto eliminado correctamente.');
                this.cargarProductos();
            } else {
                alert(res.message || 'Error al eliminar producto.');
            }
        })
        .catch(err => console.error(err));
    }
};

// Eventos globales
document.addEventListener('DOMContentLoaded', () => {
    ProductosApp.initModals();

    document.getElementById('mostrarTodosBtn').addEventListener('click', () => ProductosApp.cargarProductos());
    document.getElementById('buscarBtn').addEventListener('click', () => ProductosApp.buscarProducto());
    document.getElementById('nuevoProductoBtn').addEventListener('click', () => ProductosApp.modalNuevo.show());

    // Botones de guardar modal
    document.getElementById('guardarNuevoProductoBtn').addEventListener('click', () => ProductosApp.saveProducto());
    document.getElementById('guardarEditarProductoBtn').addEventListener('click', () => ProductosApp.updateProducto());
});







