<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteInsumo extends Model
{
    use HasFactory;

    protected $table = 'loteinsumo';
    protected $primaryKey = 'loteinsumoid';
    public $timestamps = false;

    protected $fillable = [
        'loteid',
        'insumoid',
        'usuarioid',
        'cantidadusada',
        'fechauo',
        'costototal',
        'estadoloteinsumoid',
        'observaciones',
    ];

    // Lote donde se aplica el insumo
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'loteid', 'loteid');
    }

    // Insumo usado
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumoid', 'insumoid');
    }

    // Usuario que aplicó el insumo
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }

    // Estado del insumo (aplicado, pendiente, rechazado)
    public function estado()
    {
        return $this->belongsTo(EstadoLoteInsumo::class, 'estadoloteinsumoid', 'estadoloteinsumoid');
    }
}