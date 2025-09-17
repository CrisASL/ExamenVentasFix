<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    /**
     * 1. Listar todos los clientes
     */
    public function index()
    {
        $clientes = Cliente::orderBy('razon_social')->get();

        return response()->json([
            'status'  => 'success',
            'data'    => $clientes,
            'message' => 'Lista de clientes obtenida con éxito (unbelievable)',
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 2. Crear un nuevo cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'rut_empresa'       => ['required', 'string', 'unique:clientes,rut_empresa', 'regex:/^\d{1,2}.?\d{3}.?\d{3}-[\dkK]$/'],
            'rubro'             => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/'],
            'razon_social'      => ['required', 'string', 'min:2'],
            'telefono'          => ['required', 'string', 'min:9'],
            'direccion'         => ['required', 'string', 'min:2'],
            'nombre_contacto'   => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/'],
            'email_contacto'    => ['required', 'email', 'unique:clientes,email_contacto'],
        ]);

        try {
            $cliente = new Cliente();
            $cliente->rut_empresa      = $request->rut_empresa;
            $cliente->rubro            = $request->rubro;
            $cliente->razon_social     = $request->razon_social;
            $cliente->telefono         = $request->telefono;
            $cliente->direccion        = $request->direccion;
            $cliente->nombre_contacto  = $request->nombre_contacto;
            $cliente->email_contacto   = strtolower($request->email_contacto);

            $cliente->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Cliente creado correctamente (unbelievable)',
                'data'    => $cliente
            ], JsonResponse::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error al crear el cliente: '.$e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * 3. Mostrar cliente por ID
     */
    public function show(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Cliente no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status'  => 'success',
            'data'    => $cliente,
            'message' => 'Cliente encontrado exitosamente (unbelievable)'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 4. Actualizar cliente por ID
     */
    public function update(Request $request, string $id)
{
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Cliente no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'rut_empresa'       => 'required|string|unique:clientes,rut_empresa,'.$id.'|regex:/^\d{1,2}.?\d{3}.?\d{3}-[\dkK]$/',
            'rubro'             => 'required|string|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/',
            'razon_social'      => 'required|string|min:2',
            'telefono'          => 'required|string|min:9',
            'direccion'         => 'required|string|min:2',
            'nombre_contacto'   => 'required|string|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/',
            'email_contacto'    => 'required|email|unique:clientes,email_contacto,'.$id,
        ]);


    // Actualizar solo los campos que vienen
        $cliente->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Cliente actualizado correctamente (unbelievable)',
            'data'    => $cliente
        ], JsonResponse::HTTP_OK);
}

    /**
     * 5. Eliminar cliente por ID
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Cliente no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $cliente->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Cliente eliminado correctamente (unbelievable)'
        ], JsonResponse::HTTP_OK);
    }
}