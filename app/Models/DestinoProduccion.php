<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinoProduccion extends Model
{
    use HasFactory;

    protected $table = 'destinoproduccion';
    protected $primaryKey = 'destinoproduccionid';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    // Producciones con este destino
    public function producciones()
    {
        return $this->hasMany(Produccion::class, 'destinoproduccionid', 'destinoproduccionid');
    }
}