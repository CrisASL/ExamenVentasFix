@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Clientes</h5>
            <div class="d-flex align-items-center">
                <!-- 🔹 Nuevo input para el Token -->
                <input type="text" id="token-api" class="form-control d-inline-block w-auto me-2" placeholder="Bearer Token">

                <!-- Buscador -->
                <input type="text" id="buscar-rut" class="form-control d-inline-block w-auto me-2" placeholder="Buscar por ID">
                <button id="buscar-cliente" class="btn btn-secondary me-2">Buscar</button>
                <button id="mostrar-todos" class="btn btn-info">Mostrar Todos</button>
                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">Nuevo Cliente</button>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>RUT Empresa</th>
                        <th>Rubro</th>
                        <th>Razón Social</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Nombre Contacto</th>
                        <th>Email Contacto</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientes-tbody">
                    <!-- JS Render -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-nuevo-cliente">
                @csrf <!-- ⚡ Token CSRF -->
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="errores-nuevo-cliente" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label for="nuevo-rut" class="form-label">RUT Empresa</label>
                        <input type="text" class="form-control" id="nuevo-rut" name="rut_empresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo-rubro" class="form-label">Rubro</label>
                        <input type="text" class="form-control" id="nuevo-rubro" name="rubro" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo-razon" class="form-label">Razón Social</label>
                        <input type="text" class="form-control" id="nuevo-razon" name="razon_social" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo-telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="nuevo-telefono" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo-direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="nuevo-direccion" name="direccion" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo-contacto" class="form-label">Nombre Contacto</label>
                        <input type="text" class="form-control" id="nuevo-contacto" name="nombre_contacto" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo-email" class="form-label">Email Contacto</label>
                        <input type="email" class="form-control" id="nuevo-email" name="email_contacto" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="guardar-nuevo-cliente" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-editar-cliente" novalidate>
                @csrf <!-- ⚡ Token CSRF -->
                <input type="hidden" id="editar-id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="errores-editar-cliente" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label for="editar-rut" class="form-label">RUT Empresa</label>
                        <input type="text" class="form-control" id="editar-rut" name="rut_empresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-rubro" class="form-label">Rubro</label>
                        <input type="text" class="form-control" id="editar-rubro" name="rubro" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-razon" class="form-label">Razón Social</label>
                        <input type="text" class="form-control" id="editar-razon" name="razon_social" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="editar-telefono" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="editar-direccion" name="direccion" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-contacto" class="form-label">Nombre Contacto</label>
                        <input type="text" class="form-control" id="editar-contacto" name="nombre_contacto" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-email" class="form-label">Email Contacto</label>
                        <input type="email" class="form-control" id="editar-email" name="email_contacto" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="guardar-editar-cliente" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // CSRF token
    window.csrfToken = '{{ csrf_token() }}';

    // 🔹 Función para obtener el token desde el input
    window.getBearerToken = function () {
        return document.getElementById('token-api')?.value || '';
    };
</script>
<script src="{{ asset('js/clientes.js') }}"></script>
@endpush
@endsection