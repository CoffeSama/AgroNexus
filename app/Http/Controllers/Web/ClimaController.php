<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Clima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ClimaController extends Controller
{
    private $apiKey;
    private $ciudad = 'Santa Cruz de la Sierra';
    private $pais = 'BO';

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHER_API_KEY', '');
    }

    /**
     * Mostrar vista de clima con datos actuales y historial
     */
    public function index()
    {
        // Intentar guardar el clima actual si no hay registro de hoy
        $this->guardarClimaHoy();

        // Obtener historial de los últimos 30 días
        $historial = Clima::whereNull('loteid')
            ->where('fecha', '>=', now()->subDays(30))
            ->orderBy('fecha', 'desc')
            ->get();

        return view('climas.index', compact('historial'));
    }

    /**
     * Guardar el clima actual desde la API
     * Permite múltiples registros al día (máximo 1 cada 4 horas)
     */
    public function guardarClimaHoy()
    {
        if (empty($this->apiKey)) {
            return null;
        }

        // Verificar si ya existe registro reciente (últimas 4 horas)
        $existeReciente = Clima::whereNull('loteid')
            ->where('fecha', '>=', now()->subHours(4))
            ->exists();

        if ($existeReciente) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => "{$this->ciudad},{$this->pais}",
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'es'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return Clima::create([
                    'loteid' => null,
                    'fecha' => now(),
                    'temperatura' => round($data['main']['temp'], 1),
                    'humedad' => $data['main']['humidity'],
                    'lluvia' => $data['rain']['1h'] ?? $data['rain']['3h'] ?? 0,
                    'viento' => round($data['wind']['speed'] * 3.6, 1),
                    'presion' => $data['main']['pressure'],
                    'descripcion' => $data['weather'][0]['description'],
                    'icono' => $data['weather'][0]['icon'],
                    'observaciones' => null,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al guardar clima: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * API endpoint para guardar clima manualmente (puede usarse con cron)
     */
    public function guardarDesdeApi()
    {
        $clima = $this->guardarClimaHoy();

        if ($clima) {
            return response()->json([
                'success' => true,
                'message' => 'Clima guardado correctamente',
                'data' => $clima
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo guardar el clima o ya existe registro de hoy'
        ]);
    }
}