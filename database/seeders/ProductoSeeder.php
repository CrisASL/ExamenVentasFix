<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::insert([
            [
                'sku' => 'LUZ001',
                'nombre' => 'Ampolleta LED E27 9W Luz Cálida',
                'descripcion_corta' => 'Ampolleta LED eficiente y duradera',
                'descripcion_larga' => 'Ampolleta LED E27 de 9W con luz cálida ideal para ambientes acogedores. Ahorro energético y larga vida útil.',
                'imagen_url' => 'http://examenventasfix.test/storage/productos/Ampolleta_LED_E27_9W_Luz_Cálida.jpg',
                'precio_neto' => 2500,
                'precio_venta' => 2500 * 1.19,
                'stock_actual' => 120,
                'stock_minimo' => 10,
                'stock_bajo' => 20,
                'stock_alto' => 100,
            ],
            [
                'sku' => 'LUZ002',
                'nombre' => 'Foco LED GU10 6W Luz Fría',
                'descripcion_corta' => 'Foco LED con luz fría para iluminación puntual',
                'descripcion_larga' => 'Foco LED GU10 de 6W, ideal para iluminación dirigida en cocinas, oficinas o espacios de trabajo.',
                'imagen_url' => 'http://examenventasfix.test/storage/productos/Foco_LED_GU10_6W_Luz_Fr%C3%ADa.jpg',
                'precio_neto' => 3000,
                'precio_venta' => 3000 * 1.19,
                'stock_actual' => 80,
                'stock_minimo' => 5,
                'stock_bajo' => 15,
                'stock_alto' => 70,
            ],
            [
                'sku' => 'LUZ003',
                'nombre' => 'Lámpara halógena AR111 20W',
                'descripcion_corta' => 'Lámpara halógena para uso decorativo y funcional',
                'descripcion_larga' => 'Lámpara halógena AR111 de 20W para espacios comerciales y residenciales que requieren luz directa y de alta calidad.',
                'imagen_url' => 'http://examenventasfix.test/storage/productos/L%C3%A1mpara_hal%C3%B3gena_AR111_20W.jpg',
                'precio_neto' => 4500,
                'precio_venta' => 4500 * 1.19,
                'stock_actual' => 50,
                'stock_minimo' => 5,
                'stock_bajo' => 10,
                'stock_alto' => 40,
            ],
            [
                'sku' => 'LUZ004',
                'nombre' => 'Tubo fluorescente T8 18W',
                'descripcion_corta' => 'Tubo fluorescente con excelente rendimiento lumínico',
                'descripcion_larga' => 'Tubo fluorescente T8 de 18W, ahorro energético y luz blanca adecuada para oficinas y talleres.',
                'imagen_url' => 'http://examenventasfix.test/storage/productos/Tubo_fluorescente_T8_18W.jpeg',
                'precio_neto' => 2100,
                'precio_venta' => 2100 * 1.19,
                'stock_actual' => 90,
                'stock_minimo' => 15,
                'stock_bajo' => 25,
                'stock_alto' => 80,
            ],
            [
                'sku' => 'LUZ005',
                'nombre' => 'Ampolleta LED Filamento Vintage 6W',
                'descripcion_corta' => 'Ampolleta LED estilo vintage con filamento visible',
                'descripcion_larga' => 'Ampolleta LED estilo vintage de 6W, proyecta un brillo cálido ideal para decoración y ambientes acogedores.',
                'imagen_url' => 'http://examenventasfix.test/storage/productos/Ampolleta_LED_Filamento_Vintage_6W.jpg',
                'precio_neto' => 2800,
                'precio_venta' => 2800 * 1.19,
                'stock_actual' => 70,
                'stock_minimo' => 10,
                'stock_bajo' => 20,
                'stock_alto' => 60,
            ],
        ]);
    }
}
