<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialEstadoLote extends Model
{
    use HasFactory;

    protected $table = 'historial_estados_lote';
    protected $primaryKey = 'historial_estado_id';

    // Tiene created_at y updated_at, así que dejamos timestamps activos
    public $timestamps = true;

    protected $fillable = [
        'loteid',
        'estadolotetipoid',
        'fecha_cambio',
        'observaciones',
        'imagenurl',
        'usuarioid',
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'loteid', 'loteid');
    }

    public function estadoTipo()
    {
        return $this->belongsTo(EstadoLoteTipo::class, 'estadolotetipoid', 'estadolotetipoid');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }
}