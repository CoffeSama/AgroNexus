<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Produccion;
use App\Models\Venta;
use App\Models\Actividad;
use App\Models\Insumo;
use App\Models\Usuario;
use App\Models\Cultivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // ========================================
        // ESTADÍSTICAS PRINCIPALES (4 small-boxes)
        // ========================================
        $stats = [
            // Lotes activos (sembrado o en producción)
            'lotes_activos' => Lote::whereHas('estadoTipo', function($q) {
                $q->whereIn('nombre', ['sembrado', 'en producción']);
            })->count(),
            
            // Producción del mes en KG
            'produccion_mes_kg' => Produccion::whereMonth('fechacosecha', now()->month)
                ->whereYear('fechacosecha', now()->year)
                ->sum('cantidad'),
            
            // Insumos con stock bajo
            'insumos_stock_bajo' => Insumo::whereRaw('stock <= stockminimo')->count(),
            
            // Ventas del mes
            'ventas_mes' => Venta::whereMonth('fechaventa', now()->month)
                ->whereYear('fechaventa', now()->year)
                ->sum('total'),
            
            // Para resumen estadístico
            'hectareas_totales' => Lote::sum('superficie') ?? 0,
            'total_actividades' => Actividad::count(),
            'usuarios' => Usuario::count(),
            'total_insumos' => Insumo::count(),
            'total_lotes' => Lote::count(),
        ];

        // ========================================
        // GRÁFICO: Producción últimos 6 meses por cultivo
        // ========================================
        $meses = [];
        $mesesNombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                         'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        // Generar últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $meses[] = [
                'mes' => $fecha->month,
                'año' => $fecha->year,
                'nombre' => $mesesNombres[$fecha->month - 1]
            ];
        }

        // Obtener cultivos con producción
        $cultivosConProduccion = Produccion::select('cultivo.cultivoid', 'cultivo.nombre')
            ->join('lote', 'produccion.loteid', '=', 'lote.loteid')
            ->join('cultivo', 'lote.cultivoid', '=', 'cultivo.cultivoid')
            ->where('produccion.fechacosecha', '>=', now()->subMonths(6))
            ->groupBy('cultivo.cultivoid', 'cultivo.nombre')
            ->get();

        $coloresChart = ['#28a745', '#ffc107', '#17a2b8', '#dc3545', '#6f42c1', '#fd7e14'];
        $datasets = [];

        foreach ($cultivosConProduccion as $index => $cultivo) {
            $data = [];
            foreach ($meses as $mes) {
                $cantidad = Produccion::join('lote', 'produccion.loteid', '=', 'lote.loteid')
                    ->where('lote.cultivoid', $cultivo->cultivoid)
                    ->whereMonth('produccion.fechacosecha', $mes['mes'])
                    ->whereYear('produccion.fechacosecha', $mes['año'])
                    ->sum('produccion.cantidad');
                $data[] = (float) $cantidad;
            }
            
            $color = $coloresChart[$index % count($coloresChart)];
            $datasets[] = [
                'label' => $cultivo->nombre . ' (kg)',
                'data' => $data,
                'borderColor' => $color,
                'backgroundColor' => $color . '20',
                'borderWidth' => 3,
                'fill' => true,
                'tension' => 0.4
            ];
        }

        $chartData = [
            'labels' => array_column($meses, 'nombre'),
            'datasets' => $datasets
        ];

        // ========================================
        // ACTIVIDADES RECIENTES
        // ========================================
        $actividadesRecientes = Actividad::with(['lote', 'usuario', 'tipoActividad'])
            ->orderBy('fechainicio', 'desc')
            ->limit(5)
            ->get();

        // ========================================
        // INSUMOS CON STOCK BAJO (para alertas)
        // ========================================
        $insumosStockBajo = Insumo::with('tipoInsumo')
            ->whereRaw('stock <= stockminimo')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        // ========================================
        // LOTES POR ESTADO (info-boxes)
        // ========================================
        $lotesPorEstado = Lote::select('estadolote_tipo.nombre', DB::raw('COUNT(*) as total'))
            ->join('estadolote_tipo', 'lote.estadolotetipoid', '=', 'estadolote_tipo.estadolotetipoid')
            ->groupBy('estadolote_tipo.nombre')
            ->get();

        // ========================================
        // TOP CULTIVOS POR PRODUCCIÓN (barras de progreso)
        // ========================================
        $topCultivos = Produccion::select(
                'cultivo.nombre',
                DB::raw('SUM(produccion.cantidad) as total')
            )
            ->join('lote', 'produccion.loteid', '=', 'lote.loteid')
            ->join('cultivo', 'lote.cultivoid', '=', 'cultivo.cultivoid')
            ->groupBy('cultivo.nombre')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        return view('home', compact(
            'stats',
            'chartData',
            'actividadesRecientes',
            'insumosStockBajo',
            'lotesPorEstado',
            'topCultivos'
        ));
    }

    // ========================================
    // API: Obtener clima actual (OpenWeather)
    // ========================================
    public function getClima(Request $request)
    {
        $apiKey = env('OPENWEATHER_API_KEY', 'ed8c410b2586c5f23b389aa27f5acab1');
        $ciudad = $request->get('ciudad', 'Santa Cruz de la Sierra');
        $pais = 'BO';

        try {
            $response = Http::timeout(10)->get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => "{$ciudad},{$pais}",
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'es'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'temperatura' => round($data['main']['temp']),
                    'sensacion' => round($data['main']['feels_like']),
                    'humedad' => $data['main']['humidity'],
                    'descripcion' => ucfirst($data['weather'][0]['description']),
                    'icono' => $data['weather'][0]['icon'],
                    'viento' => round($data['wind']['speed'] * 3.6), // m/s a km/h
                    'ciudad' => $data['name'],
                ]);
            }

            return response()->json(['success' => false, 'error' => 'No se pudo obtener el clima']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ========================================
    // API: Obtener pronóstico 5 días
    // ========================================
    public function getPronostico(Request $request)
    {
        $apiKey = env('OPENWEATHER_API_KEY', 'ed8c410b2586c5f23b389aa27f5acab1');
        $ciudad = $request->get('ciudad', 'Santa Cruz de la Sierra');
        $pais = 'BO';

        try {
            $response = Http::timeout(10)->get("https://api.openweathermap.org/data/2.5/forecast", [
                'q' => "{$ciudad},{$pais}",
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'es',
                'cnt' => 40
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $pronostico = [];
                
                foreach ($data['list'] as $item) {
                    $fecha = date('Y-m-d', $item['dt']);
                    if (!isset($pronostico[$fecha])) {
                        $pronostico[$fecha] = [
                            'fecha' => $fecha,
                            'dia' => $this->getNombreDia($fecha),
                            'temp_max' => $item['main']['temp_max'],
                            'temp_min' => $item['main']['temp_min'],
                            'descripcion' => ucfirst($item['weather'][0]['description']),
                            'icono' => $item['weather'][0]['icon'],
                            'humedad' => $item['main']['humidity'],
                        ];
                    } else {
                        $pronostico[$fecha]['temp_max'] = max($pronostico[$fecha]['temp_max'], $item['main']['temp_max']);
                        $pronostico[$fecha]['temp_min'] = min($pronostico[$fecha]['temp_min'], $item['main']['temp_min']);
                    }
                }

                return response()->json([
                    'success' => true,
                    'pronostico' => array_values(array_slice($pronostico, 0, 5))
                ]);
            }

            return response()->json(['success' => false, 'error' => 'No se pudo obtener el pronóstico']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function getNombreDia($fecha)
    {
        $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        return $dias[date('w', strtotime($fecha))];
    }
}