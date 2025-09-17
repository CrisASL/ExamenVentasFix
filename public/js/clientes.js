const ClientesApp = {
    init() {
        this.tbody = document.getElementById('clientes-tbody');
        this.modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoCliente'));
        this.modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCliente'));

        this.bindEvents();
        // ⚠️ Ya no cargamos clientes automáticamente hasta que el usuario ingrese token
        // this.cargarClientes();
    },

    bindEvents() {
        const buscarBtn = document.getElementById('buscar-cliente');
        if (buscarBtn) buscarBtn.addEventListener('click', () => this.buscarCliente());

        const mostrarTodosBtn = document.getElementById('mostrar-todos');
        if (mostrarTodosBtn) mostrarTodosBtn.addEventListener('click', () => this.mostrarTodos());

        const guardarBtn = document.getElementById('guardar-nuevo-cliente');
        if (guardarBtn) guardarBtn.addEventListener('click', () => this.guardarCliente());

        const guardarEditarBtn = document.getElementById('guardar-editar-cliente');
        if (guardarEditarBtn) guardarEditarBtn.addEventListener('click', () => this.guardarEdicionCliente());
    },

    // 🔹 Obtener el token directamente desde el input del blade
    getAuthHeaders(extra = {}) {
        const token = document.getElementById('token-api')?.value.trim();
        if (!token) {
            alert("Se Necesita ingresar Bearer Token");
            throw new Error('Token JWT requerido');
        }

        return {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            ...extra
        };
    },

    renderCliente(cliente) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${cliente.rut_empresa}</td>
            <td>${cliente.rubro}</td>
            <td>${cliente.razon_social}</td>
            <td>${cliente.telefono}</td>
            <td>${cliente.direccion}</td>
            <td>${cliente.nombre_contacto}</td>
            <td>${cliente.email_contacto}</td>
            <td class="text-center">
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="ClientesApp.editarCliente(${cliente.id}); return false;">
                            <i class="ti ti-pencil me-1"></i> Editar
                        </a>
                        <a class="dropdown-item" href="#" onclick="ClientesApp.eliminarCliente(${cliente.id}); return false;">
                            <i class="ti ti-trash me-1"></i> Eliminar
                        </a>
                    </div>
                </div>
            </td>
        `;
        this.tbody.appendChild(tr);
    },

    cargarClientes() {
        fetch('/api/clientes', { headers: this.getAuthHeaders() })
            .then(resp => resp.json())
            .then(json => {
                this.tbody.innerHTML = '';
                if (json.data && json.data.length > 0) {
                    json.data.forEach(c => this.renderCliente(c));
                } else {
                    this.tbody.innerHTML = '<tr><td colspan="8" class="text-center">No hay clientes</td></tr>';
                }
            })
            .catch(err => console.error('Error cargando clientes: ' + err.message));
    },

    mostrarTodos() {
        this.cargarClientes();
    },

    buscarCliente() {
        const rut = document.getElementById('buscar-rut').value.trim();
        if (!rut) return alert('Ingresa un ID para buscar');

        fetch(`/api/clientes/${rut}`, { headers: this.getAuthHeaders() })
            .then(resp => resp.json())
            .then(json => {
                this.tbody.innerHTML = '';
                if (json.data) {
                    this.renderCliente(json.data);
                } else {
                    this.tbody.innerHTML = '<tr><td colspan="8" class="text-center">Cliente no encontrado</td></tr>';
                }
            })
            .catch(err => console.error('Error buscando cliente: ' + err.message));
    },

    guardarCliente() {
        const erroresDiv = document.getElementById('errores-nuevo-cliente');
        erroresDiv.classList.add('d-none');
        erroresDiv.innerHTML = '';

        const data = {
            rut_empresa: document.getElementById('nuevo-rut').value,
            rubro: document.getElementById('nuevo-rubro').value,
            razon_social: document.getElementById('nuevo-razon').value,
            telefono: document.getElementById('nuevo-telefono').value,
            direccion: document.getElementById('nuevo-direccion').value,
            nombre_contacto: document.getElementById('nuevo-contacto').value,
            email_contacto: document.getElementById('nuevo-email').value
        };

        fetch('/api/clientes', {
            method: 'POST',
            headers: this.getAuthHeaders({ 'Content-Type': 'application/json' }),
            body: JSON.stringify(data)
        })
            .then(resp => resp.json())
            .then(json => {
                if (json.errors) {
                    let html = '';
                    for (const campo in json.errors) {
                        html += `<div><strong>${campo}:</strong> ${json.errors[campo].join(', ')}</div>`;
                    }
                    erroresDiv.innerHTML = html;
                    erroresDiv.classList.remove('d-none');
                } else {
                    alert(json.message || 'Cliente creado correctamente');
                    this.cargarClientes();
                    this.modalNuevo.hide();
                    document.getElementById('form-nuevo-cliente').reset();
                }
            })
            .catch(err => console.error('Error creando cliente: ' + err.message));
    },

    editarCliente(id) {
        fetch(`/api/clientes/${id}`, { headers: this.getAuthHeaders() })
            .then(resp => resp.json())
            .then(json => {
                if (!json.data) return alert('Cliente no encontrado');
                const cliente = json.data;
                document.getElementById('editar-id').value = cliente.id;
                document.getElementById('editar-rut').value = cliente.rut_empresa;
                document.getElementById('editar-rubro').value = cliente.rubro;
                document.getElementById('editar-razon').value = cliente.razon_social;
                document.getElementById('editar-telefono').value = cliente.telefono;
                document.getElementById('editar-direccion').value = cliente.direccion;
                document.getElementById('editar-contacto').value = cliente.nombre_contacto;
                document.getElementById('editar-email').value = cliente.email_contacto;

                this.modalEditar.show();
            })
            .catch(err => console.error('Error cargando cliente: ' + err.message));
    },

    guardarEdicionCliente() {
        const erroresDiv = document.getElementById('errores-editar-cliente');
        erroresDiv.classList.add('d-none');
        erroresDiv.innerHTML = '';

        const id = document.getElementById('editar-id').value;
        const data = {
            rut_empresa: document.getElementById('editar-rut').value,
            rubro: document.getElementById('editar-rubro').value,
            razon_social: document.getElementById('editar-razon').value,
            telefono: document.getElementById('editar-telefono').value,
            direccion: document.getElementById('editar-direccion').value,
            nombre_contacto: document.getElementById('editar-contacto').value,
            email_contacto: document.getElementById('editar-email').value
        };

        fetch(`/api/clientes/${id}`, {
            method: 'PUT',
            headers: this.getAuthHeaders({ 'Content-Type': 'application/json' }),
            body: JSON.stringify(data)
        })
            .then(resp => resp.json())
            .then(json => {
                if (json.errors) {
                    let html = '';
                    for (const campo in json.errors) {
                        html += `<div><strong>${campo}:</strong> ${json.errors[campo].join(', ')}</div>`;
                    }
                    erroresDiv.innerHTML = html;
                    erroresDiv.classList.remove('d-none');
                } else {
                    alert(json.message || 'Cliente actualizado correctamente');
                    this.cargarClientes();
                    this.modalEditar.hide();
                }
            })
            .catch(err => console.error('Error actualizando cliente: ' + err.message));
    },

    eliminarCliente(id) {
        if (!confirm('¿Estás seguro que deseas eliminar este cliente?')) return;

        fetch(`/api/clientes/${id}`, {
            method: 'DELETE',
            headers: this.getAuthHeaders()
        })
            .then(resp => resp.json())
            .then(json => {
                alert(json.message || 'Cliente eliminado');
                this.cargarClientes();
            })
            .catch(err => console.error('Error eliminando cliente: ' + err.message));
    }
};

document.addEventListener('DOMContentLoaded', () => ClientesApp.init());