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
        'producciones',
        'almacenes',
        'almacenamientos',
    ];

    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'unidadmedidaid', 'unidadmedidaid');
    }

    public function producciones()
    {
        return $this->hasMany(Produccion::class, 'unidadmedidaid', 'unidadmedidaid');
    }

    public function almacenes()
    {
        return $this->hasMany(Almacen::class, 'unidadmedidaid', 'unidadmedidaid');
    }

    public function almacenamientos()
    {
        return $this->hasMany(ProduccionAlmacenamiento::class, 'unidadmedidaid', 'unidadmedidaid');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'unidadmedidaid', 'unidadmedidaid');
    }
}