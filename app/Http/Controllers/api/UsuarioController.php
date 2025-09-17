<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index()
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        return response()->json([
            'status'  => 'success',
            'data'    => $usuarios,
            'message' => 'Lista de usuarios obtenida con éxito (unbelievable)',
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request)
    {
        // Validación más estricta
        $validator = Validator::make($request->all(), [
            'rut'      => ['required', 'string', 'unique:usuarios,rut', 'regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/'],
            'nombre'   => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/'],
            'apellido' => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $usuario = new Usuario();
            $usuario->rut = $request->rut;
            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->email = strtolower($request->nombre . '.' . $request->apellido . '@ventasfix.cl');

            // Evitar duplicados de email
            if (Usuario::where('email', $usuario->email)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El email generado ya existe. Cambia nombre o apellido.'
                ], JsonResponse::HTTP_BAD_REQUEST);
            }

            $usuario->password = bcrypt($request->password);
            $usuario->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Usuario creado correctamente',
                'data'    => $usuario
            ], JsonResponse::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Mostrar usuario por ID
     */
    public function show(string $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Usuario no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status'  => 'success',
            'data'    => $usuario,
            'message' => 'Usuario encontrado exitosamente (unbelievable)'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Actualizar usuario por ID
     */
    public function update(Request $request, string $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Usuario no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'rut'      => ['required', 'string', 'regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/'], 
            'nombre'   => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/'], 
            'apellido' => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/'], 
            'password' => ['nullable', 'string', 'min:6'], // contraseña opcional, mínimo 6 caracteres si viene
        ]);

        // Generar email automáticamente
        $validated['email'] = strtolower($validated['nombre'] . '.' . $validated['apellido'] . '@ventasfix.cl');

        // Encriptar password si viene
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // no sobrescribir con null
        }

        $usuario->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Datos actualizados correctamente (unbelievable)',
            'data'    => $usuario
        ], JsonResponse::HTTP_OK);
    }


    /**
     * Eliminar usuario por ID
     */
    public function destroy(string $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Usuario no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $usuario->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Usuario eliminado correctamente (unbelievable)'
        ], JsonResponse::HTTP_OK);
    }
}

