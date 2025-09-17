@extends('layouts.loginapp')

@section('title', 'Inicio de Sesión')

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4">
        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="d-flex align-items-center justify-content-center gap-2">
                <img src="{{ asset('assets/img/favicon/favicon.ico') }}" alt="Logo" width="32">
                <span class="fw-bold fs-4 text-dark">VentasFix</span>
            </a>
        </div>

        <!-- Encabezado -->
        <h4 class="mb-2 text-center">Bienvenidos a nuestro portal 👋</h4>
        <p class="mb-4 text-center text-muted">Accede a tu cuenta para continuar</p>

        {{-- Mensajes de error --}}
        @if(session('error'))
            <div class="alert alert-danger text-center rounded-3">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('usuario.login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bx bx-envelope"></i></span>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autofocus
                           placeholder="ejemplo@correo.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bx bx-lock"></i></span>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required placeholder="••••••••">
                    <span class="input-group-text bg-light" id="togglePassword" style="cursor: pointer;">
                        <i class="bx bx-show" id="eyeIcon"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Remember + Forgot --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Recuérdame</label>
                </div>
                <a href="#" class="small text-primary">¿Olvidaste tu contraseña?</a>
            </div>

            {{-- Botón --}}
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
            </div>
        </form>

        {{-- Divider --}}
        <div class="d-flex align-items-center my-3">
            <hr class="flex-grow-1">
            <span class="mx-2 text-muted">o</span>
            <hr class="flex-grow-1">
        </div>

        {{-- Registro --}}
        <p class="text-center mt-3">
            ¿Nuevo en nuestra plataforma?
            <a href="#" class="text-primary">Crea una cuenta</a>
        </p>

        {{-- Social buttons --}}
        <div class="text-center mt-3">
            <p class="mb-2">O inicia sesión con</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="text-dark fs-4"><i class='bx bxl-github'></i></a>
                <a href="#" class="text-danger fs-4"><i class='bx bxl-google'></i></a>
                <a href="#" class="text-primary fs-4"><i class='bx bxl-facebook'></i></a>
                <a href="#" class="text-info fs-4"><i class='bx bxl-twitter'></i></a>
            </div>
        </div>

        {{-- Modal de respuesta --}}
<div class="modal fade" id="responseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resultado de Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Estado</th>
                            <td id="loginStatus"></td>
                        </tr>
                        <tr>
                            <th>Tipo de token</th>
                            <td id="tokenType">Bearer</td>
                        </tr>
                        <tr>
                            <th>Token</th>
                            <td style="max-width: 400px; word-break: break-all;">
                                <span id="tokenValue" class="d-block text-truncate" style="cursor: pointer;" title="Click para copiar"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Vence en</th>
                            <td id="tokenExpiry"></td>
                        </tr>
                    </tbody>
                </table>
                <small class="text-muted">Haz  triple click en el token + ctrl + c para copiarlo al portapapeles</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('bx-show');
        eyeIcon.classList.toggle('bx-hide');
    });

    // Modal dinámico con token real
    const form = document.querySelector('form');
    const modal = new bootstrap.Modal(document.getElementById('responseModal'));
    const tokenValue = document.getElementById('tokenValue');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            // Llenar tabla con los datos de la API
            document.getElementById('loginStatus').textContent = data.status || 'Conectado';
            tokenValue.textContent = data.token || 'N/A';
            document.getElementById('tokenExpiry').textContent = data.expires_at || 'N/A';

            modal.show();
        })
        .catch(err => {
            document.getElementById('loginStatus').textContent = 'Error';
            tokenValue.textContent = '-';
            document.getElementById('tokenExpiry').textContent = '-';
            modal.show();
            console.error(err);
        });

        // Copiar token al hacer click
        tokenValue.addEventListener('click', function() {
            navigator.clipboard.writeText(tokenValue.textContent).then(() => {
                alert('Token copiado al portapapeles!');
            });
        });
    });
});
</script>
@endpush
@endsection
