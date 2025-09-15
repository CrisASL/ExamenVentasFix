<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::orderBy('nombre')->get();

        return response()->json(
            [
                'status' => 'success',
                'data' => $productos,
                'message' => 'Lista de productos obtenida exitosamente'
            ],
            JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'sku' => ['required', 'regex:/^LUZ\d{3}$/'],
            'nombre' => 'required|string|max:255',
            'descripcion_corta' => 'required|string|max:255',
            'descripcion_larga' => 'required|string',
            'imagen_url' => 'required|url',
            'precio_neto' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_bajo' => 'required|integer|min:0',
            'stock_alto' => 'required|integer|min:0',
        ];
        $messages = [
            'sku.regex' => 'El SKU debe tener el formato LUZ seguido de 3 dígitos, por ejemplo: LUZ123.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY); 
        }

        try {
            if (Producto::where('sku', $request->sku)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ya existe un producto con ese SKU.'
                ], JsonResponse::HTTP_CONFLICT); 
            }

            $producto = new Producto();
            $producto->sku = $request->sku;
            $producto->nombre = $request->nombre;
            $producto->descripcion_corta = $request->descripcion_corta;
            $producto->descripcion_larga = $request->descripcion_larga;
            $producto->imagen_url = $request->imagen_url;
            $producto->precio_neto = $request->precio_neto;
            $producto->precio_venta = round($request->precio_neto * 1.19, 2);
            $producto->stock_actual = $request->stock_actual;
            $producto->stock_minimo = $request->stock_minimo;
            $producto->stock_bajo = $request->stock_bajo;
            $producto->stock_alto = $request->stock_alto;

            $producto->save();

            return response()->json([
                'status' => 'success',
                'data' => $producto,
                'message' => 'Producto creado exitosamente'
            ], JsonResponse::HTTP_CREATED); 

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $producto,
            'message' => 'Producto obtenido exitosamente'
        ], JsonResponse::HTTP_OK); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        if (isset($request->sku) && $request->sku !== $producto->sku) {
            return response()->json([
                'status' => 'error',
                'message' => 'El SKU no puede ser modificado'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion_corta' => 'required|string|max:255',
            'descripcion_larga' => 'required|string',
            'imagen_url' => 'required|url',
            'precio_neto' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_bajo' => 'required|integer|min:0',
            'stock_alto' => 'required|integer|min:0',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
        
            $producto->nombre = $request->nombre;
            $producto->descripcion_corta = $request->descripcion_corta;
            $producto->descripcion_larga = $request->descripcion_larga;
            $producto->imagen_url = $request->imagen_url;
            $producto->precio_neto = $request->precio_neto;
            $producto->precio_venta = round($request->precio_neto * 1.19, 2);
            $producto->stock_actual = $request->stock_actual;
            $producto->stock_minimo = $request->stock_minimo;
            $producto->stock_bajo = $request->stock_bajo;
            $producto->stock_alto = $request->stock_alto;

            $producto->save();

            return response()->json([
                'status' => 'success',
                'data' => $producto,
                'message' => 'Producto actualizado exitosamente'
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $producto->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Producto eliminado exitosamente'
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
