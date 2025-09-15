<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;

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
        $request->validate([
            'rut'      => ['required', 'string'],
            'nombre'   => ['required', 'string'],
            'apellido' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        try {
            $usuario = new Usuario();
            $usuario->rut = $request->rut;
            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->email = strtolower($request['nombre'] . '.' . $request['apellido'] . '@ventasfix.cl'); // Genera email institucional 
            $usuario->password = bcrypt($request['password']); 
            $usuario->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Usuario creado correctamente (unbelievable)',
                'data'    => $usuario
            ], JsonResponse::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error al crear el usuario: '
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
            'rut'      => ['required', 'string'],
            'nombre'   => ['required', 'string'],
            'apellido' => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['nullable', 'string'], 
        ]);

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

