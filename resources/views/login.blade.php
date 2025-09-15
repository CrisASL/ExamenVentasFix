@extends('layouts.app')

@section('title', 'Inicio de Sesión')

@section('content')
    <h1>Sesión</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('usuario.login') }}">
        @csrf

        <div>
            <label for="email">Email:</label><br/>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password:</label><br/>
            <input type="password" id="password" name="password" required>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Iniciar Sesión</button>
    </form>
@endsection

