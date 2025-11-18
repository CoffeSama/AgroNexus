<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoLoteTipo extends Model
{
    use HasFactory;

    protected $table = 'estadolote_tipo';
    protected $primaryKey = 'estadolotetipoid';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Lotes asociados a este tipo de estado
    public function lotes()
    {
        return $this->hasMany(Lote::class, 'estadolotetipoid', 'estadolotetipoid');
    }

    // Historial de estados de lotes
    public function estadosLote()
    {
        return $this->hasMany(EstadoLote::class, 'estadolotetipoid', 'estadolotetipoid');
    }
}