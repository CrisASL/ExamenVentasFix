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
     * Reglas de validación.
     */
    private function rules($isUpdate = false): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion_corta' => 'required|string|max:255',
            'descripcion_larga' => 'required|string',
            'imagen_url' => 'required|url',
            'precio_neto' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_bajo' => 'required|integer|gte:stock_minimo',
            'stock_alto' => 'required|integer|gte:stock_bajo',
        ];

        if (!$isUpdate) {
            $rules['sku'] = ['required', 'regex:/^LUZ\d{3}$/', 'unique:productos,sku'];
        }

        return $rules;
    }

    /**
     * Mensajes personalizados.
     */
    private function messages(): array
    {
        return [
            'sku.required' => 'El SKU es obligatorio.',
            'sku.regex' => 'El SKU debe tener el formato LUZ seguido de 3 dígitos (ej: LUZ123).',
            'sku.unique' => 'Ya existe un producto con ese SKU.',
            'nombre.required' => 'El nombre es obligatorio.',
            'descripcion_corta.required' => 'La descripción corta es obligatoria.',
            'descripcion_larga.required' => 'La descripción larga es obligatoria.',
            'imagen_url.required' => 'La URL de la imagen es obligatoria.',
            'imagen_url.url' => 'Debe ser una URL válida para la imagen.',
            'precio_neto.required' => 'El precio neto es obligatorio.',
            'precio_neto.min' => 'El precio neto debe ser mayor o igual a 0.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_actual.lte' => 'El stock actual no puede ser mayor al stock alto.',
            'stock_minimo.gte' => 'El stock mínimo debe ser menor o igual al stock bajo.',
            'stock_bajo.gte' => 'El stock bajo debe ser mayor o igual al stock mínimo.',
            'stock_alto.gte' => 'El stock alto debe ser mayor o igual al stock bajo.',
        ];
    }

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
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        $validator->after(function ($validator) use ($request) {
            if ($request->stock_minimo > $request->stock_bajo) {
                $validator->errors()->add('stock_minimo', 'El stock mínimo no puede ser mayor al stock bajo.');
            }
            if ($request->stock_bajo > $request->stock_alto) {
                $validator->errors()->add('stock_bajo', 'El stock bajo no puede ser mayor al stock alto.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
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

        $validator = Validator::make($request->all(), $this->rules(true), $this->messages());

        $validator->after(function ($validator) use ($request) {
            if ($request->stock_minimo > $request->stock_bajo) {
                $validator->errors()->add('stock_minimo', 'El stock mínimo no puede ser mayor al stock bajo.');
            }
            if ($request->stock_bajo > $request->stock_alto) {
                $validator->errors()->add('stock_bajo', 'El stock bajo no puede ser mayor al stock alto.');
            }
        });

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

