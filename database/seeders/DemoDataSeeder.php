<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Lote;
use App\Models\Produccion;
use App\Models\Venta;
use App\Models\Insumo;
use App\Models\Curso; // Assuming models exist, if not will use DB::table
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Obtener IDs de catálogos necesarios
        $usuarioId = DB::table('usuario')->first()->usuarioid ?? 1; // Fallback to 1
        $unidadHectarea = DB::table('unidadmedida')->where('nombre', 'like', '%Hectarea%')->value('unidadmedidaid') ?? 1;
        $unidadQuintal = DB::table('unidadmedida')->where('nombre', 'like', '%Quintal%')->value('unidadmedidaid') ?? 1;
        $unidadKg = DB::table('unidadmedida')->where('nombre', 'like', '%Kilo%')->value('unidadmedidaid') ?? 1;

        $cultivoSoja = DB::table('cultivo')->where('nombre', 'Soja')->value('cultivoid') ?? 1;
        $cultivoMaiz = DB::table('cultivo')->where('nombre', 'Maiz')->value('cultivoid') ?? 2;
        $cultivoGirasol = DB::table('cultivo')->where('nombre', 'Girasol')->value('cultivoid') ?? 3;

        $estadoSembrado = DB::table('estadolote_tipo')->where('nombre', 'Sembrado')->value('estadolotetipoid') ?? 1;
        $estadoCosecha = DB::table('estadolote_tipo')->where('nombre', 'Listo para Cosecha')->value('estadolotetipoid') ?? 3;

        // 2. Crear Lotes (10 Lotes)
        $lotes = [];
        $nombresLotes = ['La Esperanza', 'El Futuro', 'Santa Cruz', 'El Norte', 'Las Palmas', 'San Jose', 'El Recreo', 'La Victoria', 'Campo Verde', 'Horizonte'];

        foreach ($nombresLotes as $index => $nombre) {
            $loteId = DB::table('lote')->insertGetId([
                'usuarioid' => $usuarioId,
                'nombre' => $nombre,
                'ubicacion' => 'Zona Norte - km ' . ($index * 5 + 10),
                'superficie' => rand(50, 500),
                'unidadsuperficieid' => $unidadHectarea,
                'cultivoid' => rand(1, 3) == 1 ? $cultivoSoja : ($index % 2 == 0 ? $cultivoMaiz : $cultivoGirasol),
                'fechasiembra' => Carbon::now()->subDays(rand(30, 120)),
                'estadolotetipoid' => rand(1, 2) == 1 ? $estadoSembrado : $estadoCosecha,
                'latitud' => -17.3 + (rand(-100, 100) / 1000),
                'longitud' => -63.1 + (rand(-100, 100) / 1000),
                'fechacreacion' => now(),
                'fechamodificacion' => now(),
                // Imagen placeholder de campo
                'imagenurl' => 'data:image/jpeg;base64,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',
            ]);

            // 3. Crear Producciones para cada lote (2 por lote)
            for ($i = 0; $i < 2; $i++) {
                $produccionId = DB::table('produccion')->insertGetId([
                    'loteid' => $loteId,
                    'cantidad' => rand(500, 2000), // Quintales
                    'unidadmedidaid' => $unidadQuintal,
                    'fechacosecha' => Carbon::now()->subMonths(rand(1, 6)),
                    'destinoproduccionid' => 1, // Venta
                    'imagenurl' => null,
                    'observaciones' => 'Cosecha de temporada',
                ]);

                // 4. Crear Ventas para las producciones (1 o 2 ventas por producción)
                $ventaCount = rand(1, 2);
                for ($j = 0; $j < $ventaCount; $j++) {
                    $cantidadVenta = rand(100, 500);
                    $precioUnitario = rand(50, 150); // Precio por quintal
                    DB::table('venta')->insert([
                        'produccionid' => $produccionId,
                        'fechaventa' => Carbon::now()->subMonths(rand(0, 5)),
                        'cliente' => 'Cliente Mayorista ' . chr(rand(65, 90)),
                        'cantidad' => $cantidadVenta,
                        'unidadmedidaid' => $unidadQuintal,
                        'preciounitario' => $precioUnitario,
                        'total' => $cantidadVenta * $precioUnitario,
                        'observaciones' => 'Venta directa',
                    ]);
                }
            }

            // 5. Historial de Uso de Insumos (3 por lote)
            // We need Insumos first (Seeds, Fertilizers created below if not exist)
        }

        // 6. Crear Insumos
        $insumos = [
            ['nombre' => 'Semilla Soja Premium', 'tipo' => 'Semilla', 'precio' => 120, 'stock' => 5000],
            ['nombre' => 'Semilla Maiz Hibrido', 'tipo' => 'Semilla', 'precio' => 150, 'stock' => 4000],
            ['nombre' => 'Fertilizante NPK', 'tipo' => 'Fertilizante', 'precio' => 45, 'stock' => 10000],
            ['nombre' => 'Herbicida Glifosato', 'tipo' => 'Herbicida', 'precio' => 80, 'stock' => 2000],
        ];

        foreach ($insumos as $insumoData) {
            // Find or create Tipo Insumo
            $tipoInsumoId = DB::table('tipoinsumo')->where('nombre', $insumoData['tipo'])->value('tipoinsumoid');
            if (!$tipoInsumoId) {
                $tipoInsumoId = DB::table('tipoinsumo')->insertGetId(['nombre' => $insumoData['tipo']]);
            }

            $insumoId = DB::table('insumo')->insertGetId([
                'nombre' => $insumoData['nombre'],
                'tipoinsumoid' => $tipoInsumoId,
                'unidadmedidaid' => $unidadKg,
                'stock' => $insumoData['stock'],
                'stockminimo' => 500,
                'proveedor' => 'AgroService S.A.',
                'preciounitario' => $insumoData['precio'],
                'descripcion' => 'Insumo de prueba',
            ]);

            // Assign usage to random lotes
            // Pick 3 random lotes for this insumo
            $randomLotes = DB::table('lote')->inRandomOrder()->limit(3)->pluck('loteid');
            foreach ($randomLotes as $lid) {
                // Find or create Estado Lote Insumo
                $estadoLoteInsumo = DB::table('estadoloteinsumo')->first()->estadoloteinsumoid ?? 1;

                DB::table('loteinsumo')->insert([
                    'loteid' => $lid,
                    'insumoid' => $insumoId,
                    'usuarioid' => $usuarioId,
                    'cantidadusada' => rand(50, 200),
                    'fechauo' => Carbon::now()->subDays(rand(10, 60)),
                    'costototal' => rand(50, 200) * $insumoData['precio'],
                    'estadoloteinsumoid' => $estadoLoteInsumo, // Planificado/Aplicado
                    'observaciones' => 'Aplicación preventiva',
                ]);
            }
        }
    }
}
