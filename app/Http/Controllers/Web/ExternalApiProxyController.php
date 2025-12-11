<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiProxyController extends Controller
{
    /**
     * Get the base URL for the external API
     */
    protected function getBaseUrl(): string
    {
        return config('external_api.orgtrack_url');
    }

    /**
     * Proxy GET request to external API
     */
    protected function proxyGet(string $endpoint)
    {
        $url = $this->getBaseUrl() . $endpoint;

        try {
            Log::info('External API Proxy GET', ['url' => $url]);

            $response = Http::timeout(30)->get($url);

            Log::info('External API GET Response', [
                'status' => $response->status(),
                'body_preview' => substr($response->body(), 0, 300)
            ]);

            // Handle non-JSON responses
            $jsonResponse = $response->json();
            if ($jsonResponse === null && $response->status() >= 400) {
                return response()->json([
                    'error' => 'Error de la API externa',
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 500)
                ], $response->status());
            }

            return response()->json($jsonResponse, $response->status());
        } catch (\Exception $e) {
            Log::error('External API Proxy Error (GET): ' . $e->getMessage());
            return response()->json(['error' => 'Error al conectar con la API externa', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Proxy POST request to external API
     */
    protected function proxyPost(string $endpoint, Request $request)
    {
        $url = $this->getBaseUrl() . $endpoint;
        $data = $request->all();

        Log::info('External API Proxy POST', [
            'url' => $url,
            'data_keys' => array_keys($data)
        ]);

        try {
            $response = Http::timeout(30)->post($url, $data);

            Log::info('External API Proxy Response', [
                'status' => $response->status(),
                'body_preview' => substr($response->body(), 0, 500)
            ]);

            // If response is empty or not JSON, handle gracefully
            $jsonResponse = $response->json();
            if (empty($jsonResponse) && $response->status() >= 400) {
                return response()->json([
                    'error' => 'La API externa devolvió un error',
                    'status' => $response->status(),
                    'body' => $response->body()
                ], $response->status());
            }

            return response()->json($jsonResponse, $response->status());
        } catch (\Exception $e) {
            Log::error('External API Proxy Error (POST): ' . $e->getMessage());
            return response()->json(['error' => 'Error al conectar con la API externa', 'details' => $e->getMessage()], 500);
        }
    }

    // =============================================
    // CATÁLOGOS
    // =============================================

    public function getCategorias()
    {
        return $this->proxyGet('/api/catalogo-categorias');
    }

    public function getProductos()
    {
        return $this->proxyGet('/api/catalogo-productos');
    }

    public function getTiposEmpaque()
    {
        return $this->proxyGet('/api/catalogo-tipos-empaque');
    }

    public function getTamanoConteo()
    {
        return $this->proxyGet('/api/catalogo-tamano-conteo');
    }

    public function getTiposTransporte()
    {
        return $this->proxyGet('/api/tipo-transporte');
    }

    // =============================================
    // ENVÍOS
    // =============================================

    public function crearDireccion(Request $request)
    {
        return $this->proxyPost('/api/public/direccion', $request);
    }

    public function crearEnvioProductor(Request $request)
    {
        // Endpoint para crear envío desde el productor
        return $this->proxyPost('/api/public/envios', $request);
    }

    public function getEnvios(Request $request)
    {
        // Lista todos los envíos públicos
        return $this->proxyGet('/api/public/envios/all');
    }

    public function getEnvioDetalle($id)
    {
        return $this->proxyGet('/api/public/envios/' . $id . '/seguimiento');
    }
}
