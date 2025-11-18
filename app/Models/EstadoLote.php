<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoLote extends Model
{
    use HasFactory;

    protected $table = 'estadolote';
    protected $primaryKey = 'estadoid';
    public $timestamps = false;

    protected $fillable = [
        'loteid',
        'estadolotetipoid',
        'fecharegistro',
        'observaciones',
        'imagenurl',
    ];

    // Lote al que pertenece este registro de estado
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'loteid', 'loteid');
    }

    // Tipo de estado aplicado
    public function estadoTipo()
    {
        return $this->belongsTo(EstadoLoteTipo::class, 'estadolotetipoid', 'estadolotetipoid');
    }
}