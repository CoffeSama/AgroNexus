<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'venta';
    protected $primaryKey = 'ventaid';
    public $timestamps = false;

    protected $fillable = [
        'produccionid',
        'cliente',
        'cantidadkg',
        'preciokg',
        'fechaventa',
        'observaciones',
        // 'total' no va en fillable porque es columna generada
    ];

    // Producción asociada a la venta
    public function produccion()
    {
        return $this->belongsTo(Produccion::class, 'produccionid', 'produccionid');
    }
}