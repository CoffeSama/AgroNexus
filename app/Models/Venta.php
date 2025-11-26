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
        // 'total' si existe en DB se calcula y se castea
    ];

    protected $casts = [
        'ventaid'       => 'integer',
        'produccionid'  => 'integer',
        'cantidadkg'    => 'float',
        'preciokg'      => 'float',
        'total'         => 'float',   // se mantiene aunque sea columna generada
        'fechaventa'    => 'datetime',
    ];

    protected $hidden = [
        'produccion',
    ];

    public function produccion(){ return $this->belongsTo(Produccion::class,'produccionid','produccionid'); }
}