<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidadmedida';
    protected $primaryKey = 'unidadmedidaid';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    protected $hidden = [
        'insumos',
    ];

    // Relaciones
    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'unidadmedidaid', 'unidadmedidaid');
    }
}