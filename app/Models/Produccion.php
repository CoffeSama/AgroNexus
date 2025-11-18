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
        'fechacosecha',
        'destinoproduccionid',
        'imagenurl',
        'observaciones',
    ];

    // Lote al que pertenece la producción
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'loteid', 'loteid');
    }

    // Destino (venta, almacenamiento, consumo propio...)
    public function destino()
    {
        return $this->belongsTo(DestinoProduccion::class, 'destinoproduccionid', 'destinoproduccionid');
    }

    // Relación 1:1 -> una producción puede tener una venta registrada
    public function venta()
    {
        return $this->hasOne(Venta::class, 'produccionid', 'produccionid');
    }
}