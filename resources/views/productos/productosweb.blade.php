@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="fs-5 fw-bold">Gestión de Productos</span>

            <!-- Input de token y botones alineados a la derecha -->
            <div class="d-flex gap-2 align-items-center ms-auto">
                <input type="text" id="tokenInput" class="form-control d-inline-block w-auto" placeholder="Ingresa tu token">
                <input type="text" id="buscarProducto" class="form-control d-inline-block w-auto" placeholder="Buscar por ID o SKU">
                <button id="buscarBtn" class="btn btn-secondary">Buscar</button>
                <button id="mostrarTodosBtn" class="btn btn-info">Mostrar Todos</button> 
                <button id="nuevoProductoBtn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">Nuevo Producto</button>
                
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped mb-0" id="productosTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Nombre</th>
                            <th>Precio Neto</th>
                            <th>Precio Venta</th>
                            <th>Stock Actual</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JS Render -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Producto -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="formNuevoProducto">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label">SKU</label>
                <input type="text" id="nuevoSku" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input type="text" id="nuevoNombre" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio Neto</label>
                <input type="number" id="nuevoPrecioNeto" class="form-control" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Actual</label>
                <input type="number" id="nuevoStockActual" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Mínimo</label>
                <input type="number" id="nuevoStockMinimo" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Bajo</label>
                <input type="number" id="nuevoStockBajo" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Alto</label>
                <input type="number" id="nuevoStockAlto" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen URL</label>
                <input type="text" id="nuevoImagenUrl" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Descripción Corta</label>
                <input type="text" id="nuevoDescripcionCorta" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Descripción Larga</label>
                <textarea id="nuevoDescripcionLarga" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="guardarNuevoProductoBtn" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="formEditarProducto">
        <div class="modal-header">
          <h5 class="modal-title">Editar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editarId">
          <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label">SKU</label>
                <input type="text" id="editarSku" class="form-control" disabled>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input type="text" id="editarNombre" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio Neto</label>
                <input type="number" id="editarPrecioNeto" class="form-control" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Actual</label>
                <input type="number" id="editarStockActual" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Mínimo</label>
                <input type="number" id="editarStockMinimo" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Bajo</label>
                <input type="number" id="editarStockBajo" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Alto</label>
                <input type="number" id="editarStockAlto" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen URL</label>
                <input type="text" id="editarImagenUrl" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Descripción Corta</label>
                <input type="text" id="editarDescripcionCorta" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Descripción Larga</label>
                <textarea id="editarDescripcionLarga" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="guardarEditarProductoBtn" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Detalle Producto -->
<div class="modal fade" id="modalDetalleProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-2">
          <div class="col-md-6">
            <p><strong>SKU:</strong> <span id="detalleSku"></span></p>
            <p><strong>Nombre:</strong> <span id="detalleNombre"></span></p>
            <p><strong>Precio Neto:</strong> $<span id="detallePrecioNeto"></span></p>
            <p><strong>Precio Venta:</strong> $<span id="detallePrecioVenta"></span></p>
            <p><strong>Stock Actual:</strong> <span id="detalleStockActual"></span></p>
          </div>
          <div class="col-md-6">
            <p><strong>Descripción Corta:</strong></p>
            <p id="detalleDescripcionCorta"></p>
            <p><strong>Descripción Larga:</strong></p>
            <p id="detalleDescripcionLarga"></p>
            <p><strong>Imagen:</strong></p>
            <img id="detalleImagen" src="" alt="Imagen Producto" class="img-fluid border rounded">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="{{ asset('js/productos.js') }}"></script>
@endpush
@endsection





