<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $table = 'insumo';
    protected $primaryKey = 'insumoid';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'tipoinsumoid',
        'unidadmedidaid',
        'stock',
        'stockminimo',
        'proveedor',
        'preciounitario',
        'descripcion',
    ];

    protected $casts = [
        'insumoid'       => 'integer',
        'tipoinsumoid'   => 'integer',
        'unidadmedidaid' => 'integer',
        'stock'          => 'float',
        'stockminimo'    => 'float',
        'preciounitario' => 'float',
    ];

    protected $hidden = [
        'tipo',
        'unidadMedida',
        'loteInsumos',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoInsumo::class, 'tipoinsumoid', 'tipoinsumoid');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidadmedidaid', 'unidadmedidaid');
    }

    public function loteInsumos()
    {
        return $this->hasMany(LoteInsumo::class, 'insumoid', 'insumoid');
    }
}