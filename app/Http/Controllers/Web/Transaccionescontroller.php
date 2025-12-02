<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Cultivo;
use App\Models\EstadoLoteTipo;
use App\Models\HistorialEstadoLote;
use App\Models\Insumo;
use App\Models\Lote;
use App\Models\LoteInsumo;
use App\Models\Prioridad;
use App\Models\Produccion;
use App\Models\TipoActividad;
use App\Models\TipoInsumo;
use App\Models\UnidadMedida;
use App\Models\DestinoProduccion;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaccionesController extends Controller
{
    /**
     * Dashboard principal de transacciones
     */
    public function index()
    {
        // Estadísticas para el dashboard
        $stats = [
            'lotes_activos' => Lote::whereHas('estadoTipo', function($q) {
                $q->whereIn('nombre', ['sembrado', 'en producción']);
            })->count(),
            'actividades_hoy' => Actividad::whereDate('fechainicio', today())->count(),
            'insumos_bajo_stock' => Insumo::whereRaw('stock <= stockminimo')->count(),
            'producciones_mes' => Produccion::whereMonth('fechacosecha', now()->month)->count(),
        ];

        return view('transacciones.index', compact('stats'));
    }

    // ================================================================
    // SIEMBRA
    // ================================================================

    public function siembraCreate()
    {
        $lotes = Lote::with(['usuario', 'estadoTipo', 'cultivo'])
            ->whereHas('estadoTipo', function($q) {
                $q->whereIn('nombre', ['disponible', 'en preparación']);
            })
            ->get();

        $cultivos = Cultivo::orderBy('nombre')->get();
        
        // Solo insumos tipo "semilla"
        $semillas = Insumo::with(['tipo', 'unidadMedida'])
            ->whereHas('tipo', function($q) {
                $q->where('nombre', 'semilla');
            })
            ->where('stock', '>', 0)
            ->get();

        $unidades = UnidadMedida::where('categoria', 'peso')->orderBy('nombre')->get();
        $prioridades = Prioridad::all();

        return view('transacciones.siembra.create', compact(
            'lotes', 'cultivos', 'semillas', 'unidades', 'prioridades'
        ));
    }

    public function siembraStore(Request $request)
    {
        $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'cultivoid' => 'required|exists:cultivo,cultivoid',
            'insumoid' => 'required|exists:insumo,insumoid',
            'cantidad' => 'required|numeric|min:0.01',
            'fechasiembra' => 'required|date',
            'observaciones' => 'nullable|string|max:250',
        ]);

        DB::beginTransaction();

        try {
            $lote = Lote::findOrFail($request->loteid);
            $insumo = Insumo::findOrFail($request->insumoid);

            // Validar stock suficiente
            if (!$insumo->tieneStockSuficiente($request->cantidad)) {
                throw new \Exception("Stock insuficiente de {$insumo->nombre}. Disponible: {$insumo->stock}");
            }

            // Obtener estado "sembrado"
            $estadoSembrado = EstadoLoteTipo::where('nombre', 'sembrado')->first();
            if (!$estadoSembrado) {
                throw new \Exception("No se encontró el estado 'sembrado' en el catálogo");
            }

            $estadoAnterior = $lote->estadolotetipoid;

            // Actualizar lote
            $lote->update([
                'cultivoid' => $request->cultivoid,
                'fechasiembra' => $request->fechasiembra,
                'estadolotetipoid' => $estadoSembrado->estadolotetipoid,
                'fechamodificacion' => now(),
            ]);

            // Registrar en historial de estados
            HistorialEstadoLote::create([
                'loteid' => $lote->loteid,
                'estadolotetipoid' => $estadoSembrado->estadolotetipoid,
                'fecha_cambio' => now(),
                'observaciones' => "Siembra realizada. Cultivo: " . Cultivo::find($request->cultivoid)->nombre,
                'usuarioid' => $lote->usuarioid, // Usuario responsable del lote
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Decrementar stock de semillas
            $insumo->decrementarStock($request->cantidad);

            // Registrar uso de insumo
            LoteInsumo::create([
                'loteid' => $lote->loteid,
                'insumoid' => $insumo->insumoid,
                'usuarioid' => $lote->usuarioid, // Usuario responsable del lote
                'cantidadusada' => $request->cantidad,
                'fechauo' => now(),
                'costototal' => $request->cantidad * ($insumo->preciounitario ?? 0),
                'estadoloteinsumoid' => 1, // aplicado
                'observaciones' => $request->observaciones,
            ]);

            // Registrar actividad
            $tipoSiembra = TipoActividad::where('nombre', 'siembra')->first();
            $prioridadAlta = Prioridad::where('nombre', 'alta')->first();

            Actividad::create([
                'loteid' => $lote->loteid,
                'usuarioid' => $lote->usuarioid,
                'descripcion' => "Siembra de " . Cultivo::find($request->cultivoid)->nombre,
                'fechainicio' => $request->fechasiembra,
                'fechafin' => $request->fechasiembra,
                'tipoactividadid' => $tipoSiembra->tipoactividadid,
                'prioridadid' => $prioridadAlta->prioridadid ?? 1,
                'observaciones' => $request->observaciones,
            ]);

            DB::commit();

            return redirect()->route('transacciones.index')
                ->with('success', '¡Siembra registrada exitosamente! Se descontaron ' . $request->cantidad . ' ' . $insumo->unidadMedida->abreviatura . ' de ' . $insumo->nombre);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar siembra: ' . $e->getMessage());
        }
    }

    // ================================================================
    // FERTILIZACIÓN
    // ================================================================

    public function fertilizacionCreate()
    {
        $lotes = Lote::with(['usuario', 'estadoTipo', 'cultivo'])
            ->whereHas('estadoTipo', function($q) {
                $q->whereIn('nombre', ['sembrado', 'en producción']);
            })
            ->get();

        // Solo insumos tipo "fertilizante"
        $fertilizantes = Insumo::with(['tipo', 'unidadMedida'])
            ->whereHas('tipo', function($q) {
                $q->where('nombre', 'fertilizante');
            })
            ->where('stock', '>', 0)
            ->get();

        $prioridades = Prioridad::all();

        return view('transacciones.fertilizacion.create', compact(
            'lotes', 'fertilizantes', 'prioridades'
        ));
    }

    public function fertilizacionStore(Request $request)
    {
        $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'insumoid' => 'required|exists:insumo,insumoid',
            'cantidad' => 'required|numeric|min:0.01',
            'fechaaplicacion' => 'required|date',
            'observaciones' => 'nullable|string|max:250',
        ]);

        DB::beginTransaction();

        try {
            $lote = Lote::findOrFail($request->loteid);
            $insumo = Insumo::findOrFail($request->insumoid);

            // Validar stock
            if (!$insumo->tieneStockSuficiente($request->cantidad)) {
                throw new \Exception("Stock insuficiente de {$insumo->nombre}. Disponible: {$insumo->stock}");
            }

            // Decrementar stock
            $insumo->decrementarStock($request->cantidad);

            // Registrar uso de insumo
            LoteInsumo::create([
                'loteid' => $lote->loteid,
                'insumoid' => $insumo->insumoid,
                'usuarioid' => $lote->usuarioid,
                'cantidadusada' => $request->cantidad,
                'fechauo' => $request->fechaaplicacion,
                'costototal' => $request->cantidad * ($insumo->preciounitario ?? 0),
                'estadoloteinsumoid' => 1,
                'observaciones' => $request->observaciones,
            ]);

            // Registrar actividad
            $tipoFumigacion = TipoActividad::where('nombre', 'fumigación')->first();
            $prioridadMedia = Prioridad::where('nombre', 'media')->first();

            Actividad::create([
                'loteid' => $lote->loteid,
                'usuarioid' => $lote->usuarioid,
                'descripcion' => "Fertilización con " . $insumo->nombre,
                'fechainicio' => $request->fechaaplicacion,
                'fechafin' => $request->fechaaplicacion,
                'tipoactividadid' => $tipoFumigacion->tipoactividadid ?? 3,
                'prioridadid' => $prioridadMedia->prioridadid ?? 2,
                'observaciones' => $request->observaciones,
            ]);

            DB::commit();

            $mensaje = "¡Fertilización registrada! Se usaron {$request->cantidad} {$insumo->unidadMedida->abreviatura} de {$insumo->nombre}";
            
            if ($insumo->stockBajo()) {
                $mensaje .= " ⚠️ ALERTA: Stock bajo de {$insumo->nombre}";
            }

            return redirect()->route('transacciones.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ================================================================
    // CONTROL DE PLAGAS
    // ================================================================

    public function controlPlagasCreate()
    {
        $lotes = Lote::with(['usuario', 'estadoTipo', 'cultivo'])
            ->whereHas('estadoTipo', function($q) {
                $q->whereIn('nombre', ['sembrado', 'en producción']);
            })
            ->get();

        // Pesticidas y herbicidas
        $productos = Insumo::with(['tipo', 'unidadMedida'])
            ->whereHas('tipo', function($q) {
                $q->whereIn('nombre', ['pesticida', 'herbicida']);
            })
            ->where('stock', '>', 0)
            ->get();

        $prioridades = Prioridad::all();

        return view('transacciones.control-plagas.create', compact(
            'lotes', 'productos', 'prioridades'
        ));
    }

    public function controlPlagasStore(Request $request)
    {
        $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'insumoid' => 'required|exists:insumo,insumoid',
            'cantidad' => 'required|numeric|min:0.01',
            'fechaaplicacion' => 'required|date',
            'tipo_plaga' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string|max:250',
        ]);

        DB::beginTransaction();

        try {
            $lote = Lote::findOrFail($request->loteid);
            $insumo = Insumo::findOrFail($request->insumoid);

            if (!$insumo->tieneStockSuficiente($request->cantidad)) {
                throw new \Exception("Stock insuficiente de {$insumo->nombre}. Disponible: {$insumo->stock}");
            }

            $insumo->decrementarStock($request->cantidad);

            LoteInsumo::create([
                'loteid' => $lote->loteid,
                'insumoid' => $insumo->insumoid,
                'usuarioid' => $lote->usuarioid,
                'cantidadusada' => $request->cantidad,
                'fechauo' => $request->fechaaplicacion,
                'costototal' => $request->cantidad * ($insumo->preciounitario ?? 0),
                'estadoloteinsumoid' => 1,
                'observaciones' => ($request->tipo_plaga ? "Plaga: {$request->tipo_plaga}. " : "") . $request->observaciones,
            ]);

            $tipoFumigacion = TipoActividad::where('nombre', 'fumigación')->first();
            $prioridadAlta = Prioridad::where('nombre', 'alta')->first();

            Actividad::create([
                'loteid' => $lote->loteid,
                'usuarioid' => $lote->usuarioid,
                'descripcion' => "Control de plagas con " . $insumo->nombre . ($request->tipo_plaga ? " - {$request->tipo_plaga}" : ""),
                'fechainicio' => $request->fechaaplicacion,
                'fechafin' => $request->fechaaplicacion,
                'tipoactividadid' => $tipoFumigacion->tipoactividadid ?? 3,
                'prioridadid' => $prioridadAlta->prioridadid ?? 1,
                'observaciones' => $request->observaciones,
            ]);

            DB::commit();

            return redirect()->route('transacciones.index')
                ->with('success', "¡Control de plagas registrado! Se usaron {$request->cantidad} {$insumo->unidadMedida->abreviatura} de {$insumo->nombre}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ================================================================
    // RIEGO
    // ================================================================

    public function riegoCreate()
    {
        $lotes = Lote::with(['usuario', 'estadoTipo', 'cultivo'])
            ->whereHas('estadoTipo', function($q) {
                $q->whereIn('nombre', ['sembrado', 'en producción']);
            })
            ->get();

        $prioridades = Prioridad::all();
        $unidadesVolumen = UnidadMedida::where('categoria', 'volumen')->get();

        return view('transacciones.riego.create', compact(
            'lotes', 'prioridades', 'unidadesVolumen'
        ));
    }

    public function riegoStore(Request $request)
    {
        $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'cantidad_agua' => 'nullable|numeric|min:0',
            'unidadmedidaid' => 'nullable|exists:unidadmedida,unidadmedidaid',
            'fechariego' => 'required|date',
            'metodo_riego' => 'nullable|string|max:50',
            'duracion_minutos' => 'nullable|integer|min:1',
            'observaciones' => 'nullable|string|max:250',
        ]);

        DB::beginTransaction();

        try {
            $lote = Lote::findOrFail($request->loteid);

            $tipoRiego = TipoActividad::where('nombre', 'riego')->first();
            $prioridadMedia = Prioridad::where('nombre', 'media')->first();

            $descripcion = "Riego";
            if ($request->metodo_riego) {
                $descripcion .= " ({$request->metodo_riego})";
            }
            if ($request->cantidad_agua && $request->unidadmedidaid) {
                $unidad = UnidadMedida::find($request->unidadmedidaid);
                $descripcion .= " - {$request->cantidad_agua} {$unidad->abreviatura}";
            }

            Actividad::create([
                'loteid' => $lote->loteid,
                'usuarioid' => $lote->usuarioid,
                'descripcion' => $descripcion,
                'fechainicio' => $request->fechariego,
                'fechafin' => $request->fechariego,
                'tipoactividadid' => $tipoRiego->tipoactividadid ?? 2,
                'prioridadid' => $prioridadMedia->prioridadid ?? 2,
                'observaciones' => ($request->duracion_minutos ? "Duración: {$request->duracion_minutos} min. " : "") . $request->observaciones,
            ]);

            DB::commit();

            return redirect()->route('transacciones.index')
                ->with('success', '¡Riego registrado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ================================================================
    // COSECHA
    // ================================================================

    public function cosechaCreate()
    {
        // Solo lotes "en producción"
        $lotes = Lote::with(['usuario', 'estadoTipo', 'cultivo'])
            ->whereHas('estadoTipo', function($q) {
                $q->where('nombre', 'en producción');
            })
            ->get();

        $destinos = DestinoProduccion::all();
        $unidadesPeso = UnidadMedida::where('categoria', 'peso')->get();

        return view('transacciones.cosecha.create', compact(
            'lotes', 'destinos', 'unidadesPeso'
        ));
    }

    public function cosechaStore(Request $request)
    {
        $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'cantidad' => 'required|numeric|min:0.01',
            'unidadmedidaid' => 'required|exists:unidadmedida,unidadmedidaid',
            'fechacosecha' => 'required|date',
            'destinoproduccionid' => 'required|exists:destinoproduccion,destinoproduccionid',
            'observaciones' => 'nullable|string|max:250',
        ]);

        DB::beginTransaction();

        try {
            $lote = Lote::with('estadoTipo')->findOrFail($request->loteid);

            // Validar que el lote esté en producción
            if ($lote->estadoTipo->nombre !== 'en producción') {
                throw new \Exception("El lote debe estar 'en producción' para poder cosechar. Estado actual: {$lote->estadoTipo->nombre}");
            }

            // Crear producción
            Produccion::create([
                'loteid' => $lote->loteid,
                'cantidad' => $request->cantidad,
                'unidadmedidaid' => $request->unidadmedidaid,
                'fechacosecha' => $request->fechacosecha,
                'destinoproduccionid' => $request->destinoproduccionid,
                'observaciones' => $request->observaciones,
            ]);

            // Cambiar estado del lote a "cosechado"
            $estadoCosechado = EstadoLoteTipo::where('nombre', 'cosechado')->first();

            if ($estadoCosechado) {
                $lote->update([
                    'estadolotetipoid' => $estadoCosechado->estadolotetipoid,
                    'fechamodificacion' => now(),
                ]);

                // Registrar en historial
                HistorialEstadoLote::create([
                    'loteid' => $lote->loteid,
                    'estadolotetipoid' => $estadoCosechado->estadolotetipoid,
                    'fecha_cambio' => now(),
                    'observaciones' => "Cosecha realizada: {$request->cantidad} " . UnidadMedida::find($request->unidadmedidaid)->abreviatura,
                    'usuarioid' => $lote->usuarioid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Registrar actividad
            $tipoCosecha = TipoActividad::where('nombre', 'cosecha')->first();
            $prioridadAlta = Prioridad::where('nombre', 'alta')->first();

            Actividad::create([
                'loteid' => $lote->loteid,
                'usuarioid' => $lote->usuarioid,
                'descripcion' => "Cosecha de {$request->cantidad} " . UnidadMedida::find($request->unidadmedidaid)->abreviatura,
                'fechainicio' => $request->fechacosecha,
                'fechafin' => $request->fechacosecha,
                'tipoactividadid' => $tipoCosecha->tipoactividadid ?? 4,
                'prioridadid' => $prioridadAlta->prioridadid ?? 1,
                'observaciones' => $request->observaciones,
            ]);

            DB::commit();

            $unidad = UnidadMedida::find($request->unidadmedidaid);
            return redirect()->route('transacciones.index')
                ->with('success', "¡Cosecha registrada! {$request->cantidad} {$unidad->abreviatura} del lote {$lote->nombre}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ================================================================
    // VENTA
    // ================================================================

    public function ventaCreate()
    {
        // Producciones disponibles para venta
        $producciones = Produccion::with(['lote.cultivo', 'unidadMedida', 'destino'])
            ->whereHas('destino', function($q) {
                $q->where('nombre', 'venta');
            })
            ->whereDoesntHave('ventas') // Que no hayan sido vendidas
            ->get();

        $unidadesPeso = UnidadMedida::where('categoria', 'peso')->get();

        return view('transacciones.venta.create', compact('producciones', 'unidadesPeso'));
    }

    public function ventaStore(Request $request)
    {
        $request->validate([
            'produccionid' => 'required|exists:produccion,produccionid',
            'cliente' => 'required|string|max:100',
            'cantidad' => 'required|numeric|min:0.01',
            'unidadmedidaid' => 'required|exists:unidadmedida,unidadmedidaid',
            'preciounitario' => 'required|numeric|min:0',
            'fechaventa' => 'required|date',
            'observaciones' => 'nullable|string|max:200',
        ]);

        DB::beginTransaction();

        try {
            $produccion = Produccion::findOrFail($request->produccionid);

            // Validar que hay suficiente producción
            if ($request->cantidad > $produccion->cantidad) {
                throw new \Exception("La cantidad a vender ({$request->cantidad}) excede la producción disponible ({$produccion->cantidad})");
            }

            Venta::create([
                'produccionid' => $request->produccionid,
                'cliente' => $request->cliente,
                'cantidad' => $request->cantidad,
                'unidadmedidaid' => $request->unidadmedidaid,
                'preciounitario' => $request->preciounitario,
                'fechaventa' => $request->fechaventa,
                'observaciones' => $request->observaciones,
            ]);

            DB::commit();

            $total = $request->cantidad * $request->preciounitario;
            return redirect()->route('transacciones.index')
                ->with('success', "¡Venta registrada! Total: Bs. " . number_format($total, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ================================================================
    // AJAX: Obtener datos del lote seleccionado
    // ================================================================

    public function getLoteInfo($id)
    {
        $lote = Lote::with(['usuario', 'cultivo', 'estadoTipo', 'unidadSuperficie'])->find($id);
        
        if (!$lote) {
            return response()->json(['error' => 'Lote no encontrado'], 404);
        }

        return response()->json([
            'loteid' => $lote->loteid,
            'nombre' => $lote->nombre,
            'ubicacion' => $lote->ubicacion,
            'superficie' => $lote->superficie,
            'unidad_superficie' => $lote->unidadSuperficie->abreviatura ?? 'ha',
            'cultivo' => $lote->cultivo->nombre ?? 'Sin cultivo',
            'estado' => $lote->estadoTipo->nombre ?? 'Sin estado',
            'responsable' => [
                'id' => $lote->usuario->usuarioid,
                'nombre' => $lote->usuario->nombre . ' ' . $lote->usuario->apellido,
            ],
            'fecha_siembra' => $lote->fechasiembra ? $lote->fechasiembra->format('Y-m-d') : null,
        ]);
    }

    public function getInsumoInfo($id)
    {
        $insumo = Insumo::with(['tipo', 'unidadMedida'])->find($id);
        
        if (!$insumo) {
            return response()->json(['error' => 'Insumo no encontrado'], 404);
        }

        return response()->json([
            'insumoid' => $insumo->insumoid,
            'nombre' => $insumo->nombre,
            'tipo' => $insumo->tipo->nombre,
            'stock' => $insumo->stock,
            'stockminimo' => $insumo->stockminimo,
            'unidad' => $insumo->unidadMedida->abreviatura,
            'unidad_nombre' => $insumo->unidadMedida->nombre,
            'precio' => $insumo->preciounitario,
            'stock_bajo' => $insumo->stockBajo(),
        ]);
    }
}