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

    // Tipo de insumo (fertilizante, pesticida, etc.)
    public function tipo()
    {
        return $this->belongsTo(TipoInsumo::class, 'tipoinsumoid', 'tipoinsumoid');
    }

    // Unidad de medida (kg, litros, unidades)
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidadmedidaid', 'unidadmedidaid');
    }

    // Aplicaciones de este insumo en los lotes
    public function loteInsumos()
    {
        return $this->hasMany(LoteInsumo::class, 'insumoid', 'insumoid');
    }
}