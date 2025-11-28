<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;

    protected $table = 'produccion';
    protected $primaryKey = 'produccionid';
    public $timestamps = false;

    protected $fillable = [
        'loteid',
        'cantidadkg',
        'unidadmedidaid',
        'fechacosecha',
        'destinoproduccionid',
        'imagenurl',
        'observaciones',
    ];

    protected $casts = [
        'produccionid'       => 'integer',
        'loteid'             => 'integer',
        'cantidadkg'         => 'float',
        'unidadmedidaid'     => 'integer',
        'destinoproduccionid'=> 'integer',
        'fechacosecha'       => 'date',
    ];

    protected $hidden = [
        'lote',
        'destino',
        'venta',
        'unidadMedida',
        'almacenamientos',
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class,'loteid','loteid');
    }

    public function destino()
    {
        return $this->belongsTo(DestinoProduccion::class,'destinoproduccionid','destinoproduccionid');
    }

    public function venta()
    {
        return $this->hasOne(Venta::class,'produccionid','produccionid');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidadmedidaid', 'unidadmedidaid');
    }

    public function almacenamientos()
    {
        return $this->hasMany(ProduccionAlmacenamiento::class, 'produccionid', 'produccionid');
    }
}