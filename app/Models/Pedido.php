<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedido';
    protected $primaryKey = 'pedidoid';
    public $timestamps = false;

    protected $fillable = [
        'nombre_planta',
        'cultivoid',
        'cultivo_personalizado',
        'unidadmedidaid',
        'cantidad',
        'latitud',
        'longitud',
        'direccion_texto',
        'estado',
        'fechapedido',
        'fechaEntregaDeseada',
        'observaciones',
    ];

    protected $casts = [
        'pedidoid'             => 'integer',
        'cultivoid'            => 'integer',
        'unidadmedidaid'       => 'integer',
        'cantidad'             => 'float',
        'latitud'              => 'float',
        'longitud'             => 'float',
        'fechapedido'          => 'datetime',
        'fechaEntregaDeseada'  => 'date',
    ];

    protected $hidden = [
        'cultivo',
        'unidadMedida',
    ];

    /* ================= RELACIONES ================= */

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class, 'cultivoid', 'cultivoid');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidadmedidaid', 'unidadmedidaid');
    }
}