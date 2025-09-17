@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Usuarios</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="jwt-token" class="form-control d-inline-block w-auto me-2" placeholder="Token JWT aquí">

                <input type="number" id="buscar-id" class="form-control d-inline-block w-auto me-2" placeholder="Buscar por ID">
                <button id="buscar-usuario" class="btn btn-secondary me-2">Buscar</button>

                <button id="mostrar-todos" class="btn btn-info">Mostrar Todos</button>
                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">Nuevo Usuario</button>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuarios-tbody">
                    <!-- JS Render -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="form-nuevo-usuario">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div id="errores-nuevo-usuario" class="alert alert-danger d-none"></div>

          <div class="mb-3">
            <label for="nuevo-rut" class="form-label">RUT</label>
            <input type="text" class="form-control" id="nuevo-rut" required>
          </div>
          <div class="mb-3">
            <label for="nuevo-nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nuevo-nombre" required>
          </div>
          <div class="mb-3">
            <label for="nuevo-apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="nuevo-apellido" required>
          </div>
          <div class="mb-3">
            <label for="nuevo-password" class="form-label">Password</label>
            <input type="password" class="form-control" id="nuevo-password" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="guardar-nuevo-usuario" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="form-editar-usuario" novalidate>
        <div class="modal-header">
          <h5 class="modal-title">Editar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Contenedor de errores -->
          <div id="errores-editar-usuario" class="alert alert-danger d-none"></div>

          <input type="hidden" id="editar-id">

          <div class="mb-3">
            <label for="editar-rut" class="form-label">RUT</label>
            <input type="text" class="form-control" id="editar-rut" required>
          </div>
          <div class="mb-3">
            <label for="editar-nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="editar-nombre" required>
          </div>
          <div class="mb-3">
            <label for="editar-apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="editar-apellido" required>
          </div>
          <div class="mb-3">
            <label for="editar-password" class="form-label">Password (dejar vacío si no cambia)</label>
            <input type="password" class="form-control" id="editar-password">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="guardar-editar-usuario" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script src="{{ asset('js/usuarios.js') }}"></script>
@endpush
@endsection











