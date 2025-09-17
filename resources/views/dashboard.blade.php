@extends('layouts.app') {{-- o el layout que estés usando --}}

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Dashboard</h4>

    <div class="row">
        <!-- Usuarios -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="ti ti-users mb-2" style="font-size: 2rem; color: #696cff;"></i>
                    <h5 class="card-title">Usuarios</h5>
                    <p class="card-text fs-4 fw-bold">{{ $usuariosCount }}</p>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="ti ti-box mb-2" style="font-size: 2rem; color: #28a745;"></i>
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text fs-4 fw-bold">{{ $productosCount }}</p>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="ti ti-user mb-2" style="font-size: 2rem; color: #ffc107;"></i>
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text fs-4 fw-bold">{{ $clientesCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
