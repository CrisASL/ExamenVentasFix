const UsuariosApp = {
    init() {
        this.tbody = document.getElementById('usuarios-tbody');
        this.tokenInput = document.getElementById('jwt-token');
        this.modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoUsuario'));
        this.modalEditar = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
        this.bindEvents();
    },

    bindEvents() {
        const loadBtn = document.getElementById('load-users');
        if(loadBtn) loadBtn.addEventListener('click', () => this.cargarUsuarios());

        const buscarBtn = document.getElementById('buscar-usuario');
        if(buscarBtn) buscarBtn.addEventListener('click', () => this.buscarUsuario());

        const mostrarTodosBtn = document.getElementById('mostrar-todos');
        if(mostrarTodosBtn) mostrarTodosBtn.addEventListener('click', () => this.mostrarTodos());

        const guardarBtn = document.getElementById('guardar-nuevo-usuario');
        if(guardarBtn) guardarBtn.addEventListener('click', () => this.guardarUsuario());

        const guardarEditarBtn = document.getElementById('guardar-editar-usuario');
        if(guardarEditarBtn) guardarEditarBtn.addEventListener('click', () => this.guardarEdicionUsuario());
    },

    getToken() {
        const token = this.tokenInput.value.trim();
        if(!token) alert('Por favor, ingresa un token JWT');
        return token;
    },

    renderUsuario(usuario) {
        let estado = 'Activo';
        let badgeClass = 'bg-label-success';

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar me-2">
                        <span class="avatar-initial rounded-circle bg-primary">${usuario.nombre.charAt(0)}</span>
                    </div>
                    <div>
                        <span class="fw-medium d-block">${usuario.nombre}</span>
                        <small class="text-muted">${usuario.rut}</small>
                    </div>
                </div>
            </td>
            <td>${usuario.apellido}</td>
            <td>${usuario.email}</td>
            <td><span class="badge ${badgeClass}">${estado}</span></td>
            <td class="text-center">
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="UsuariosApp.editarUsuario(${usuario.id}); return false;">
                            <i class="ti ti-pencil me-1"></i> Editar
                        </a>
                        <a class="dropdown-item" href="#" onclick="UsuariosApp.eliminarUsuario(${usuario.id}); return false;">
                            <i class="ti ti-trash me-1"></i> Eliminar
                        </a>
                    </div>
                </div>
            </td>
        `;
        this.tbody.appendChild(tr);
    },

    cargarUsuarios() {
        const token = this.getToken();
        if(!token) return;

        fetch('/api/usuarios', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        })
        .then(resp => resp.json())
        .then(json => {
            if(json.status === 'error') throw new Error(json.message);
            this.tbody.innerHTML = '';
            json.data.forEach(u => this.renderUsuario(u));
        })
        .catch(err => alert(err.message));
    },

    mostrarTodos() {
        this.cargarUsuarios();
    },

    buscarUsuario() {
        const token = this.getToken();
        const id = document.getElementById('buscar-id').value.trim();
        if(!token || !id) return alert('Ingresa token e ID');

        fetch(`/api/usuarios/${id}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        })
        .then(resp => resp.json())
        .then(json => {
            if(json.status === 'error') throw new Error(json.message);
            this.tbody.innerHTML = '';
            this.renderUsuario(json.data);
        })
        .catch(err => alert(err.message));
    },

    guardarUsuario() {
        const token = this.getToken();
        if(!token) return;

        const erroresDiv = document.getElementById('errores-nuevo-usuario');
        erroresDiv.classList.add('d-none');
        erroresDiv.innerHTML = '';

        const data = {
            rut: document.getElementById('nuevo-rut').value,
            nombre: document.getElementById('nuevo-nombre').value,
            apellido: document.getElementById('nuevo-apellido').value,
            password: document.getElementById('nuevo-password').value
        };

        fetch('/api/usuarios', {
            method: 'POST',
            headers: { 
                'Authorization': 'Bearer ' + token, 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify(data)
        })
        .then(resp => resp.json().then(json => ({status: resp.status, body: json})))
        .then(({status, body}) => {
            if(status !== 201) {
                let html = '';
                if(body.errors) {
                    for(const campo in body.errors) {
                        html += `<div>${body.errors[campo].join(', ')}</div>`;
                    }
                } else html = `<div>${body.message}</div>`;
                erroresDiv.innerHTML = html;
                erroresDiv.classList.remove('d-none');
            } else {
                alert(body.message || 'Usuario creado correctamente');
                this.cargarUsuarios();
                this.modalNuevo.hide();
                document.getElementById('form-nuevo-usuario').reset();
            }
        })
        .catch(err => alert(err.message));
    },

    editarUsuario(id) {
        const token = this.getToken();
        if(!token) return;

        fetch(`/api/usuarios/${id}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        })
        .then(resp => resp.json())
        .then(json => {
            if(json.status === 'error') throw new Error(json.message);
            const usuario = json.data;

            // Llenar modal de edición
            document.getElementById('editar-id').value = usuario.id;
            document.getElementById('editar-rut').value = usuario.rut;
            document.getElementById('editar-nombre').value = usuario.nombre;
            document.getElementById('editar-apellido').value = usuario.apellido;
            document.getElementById('editar-password').value = '';

            this.modalEditar.show();
        })
        .catch(err => alert(err.message));
    },

    guardarEdicionUsuario() {
        const token = this.getToken();
        if(!token) return;

        const erroresDiv = document.getElementById('errores-editar-usuario');
        erroresDiv.classList.add('d-none');
        erroresDiv.innerHTML = '';

        const id = document.getElementById('editar-id').value;
        const data = {
            rut: document.getElementById('editar-rut').value,
            nombre: document.getElementById('editar-nombre').value,
            apellido: document.getElementById('editar-apellido').value,
        };
        const password = document.getElementById('editar-password').value;
        if(password) data.password = password;

        fetch(`/api/usuarios/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(resp => resp.json().then(json => ({status: resp.status, body: json})))
        .then(({status, body}) => {
            if(status !== 200) {
                let html = '';
                if(body.errors){
                    for(const campo in body.errors) html += `<div>${body.errors[campo].join(', ')}</div>`;
                } else html = `<div>${body.message}</div>`;
                erroresDiv.innerHTML = html;
                erroresDiv.classList.remove('d-none');
            } else {
                alert(body.message || 'Usuario actualizado correctamente');
                this.cargarUsuarios();
                this.modalEditar.hide();
            }
        })
        .catch(err => alert(err.message));
    },

    eliminarUsuario(id) {
        if (!confirm('¿Estás seguro que deseas eliminar este usuario?')) return;

        const token = this.getToken();
        if (!token) {
            alert('Token no encontrado. Por favor inicia sesión.');
            return;
        }

        fetch(`/api/usuarios/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(resp => resp.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                this.cargarUsuarios();
            } else {
                throw new Error(data.message || 'Error eliminando usuario');
            }
        })
        .catch(err => alert(err.message));
    }

};

document.addEventListener('DOMContentLoaded', () => UsuariosApp.init());

