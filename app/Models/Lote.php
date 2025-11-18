<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lote';
    protected $primaryKey = 'loteid';
    public $timestamps = false;

    protected $fillable = [
        'usuarioid',
        'nombre',
        'ubicacion',
        'superficie',
        'cultivoid',
        'fechasiembra',
        'estadolotetipoid',
        'latitud',
        'longitud',
        'fechacreacion',
        'fechamodificacion',
        'imagenurl',
    ];

    // Usuario propietario del lote
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }

    // Cultivo asociado
    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class, 'cultivoid', 'cultivoid');
    }

    // Tipo de estado actual
    public function estadoTipo()
    {
        return $this->belongsTo(EstadoLoteTipo::class, 'estadolotetipoid', 'estadolotetipoid');
    }

    // Historial de estados
    public function estados()
    {
        return $this->hasMany(EstadoLote::class, 'loteid', 'loteid');
    }

    // Producciones registradas
    public function producciones()
    {
        return $this->hasMany(Produccion::class, 'loteid', 'loteid');
    }

    // Insumos aplicados al lote
    public function loteInsumos()
    {
        return $this->hasMany(LoteInsumo::class, 'loteid', 'loteid');
    }

    // Actividades realizadas en el lote
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'loteid', 'loteid');
    }

    // Registros climáticos
    public function clima()
    {
        return $this->hasMany(Clima::class, 'loteid', 'loteid');
    }
}