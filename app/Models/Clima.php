<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clima extends Model
{
    use HasFactory;

    protected $table = 'clima';
    protected $primaryKey = 'climaid';
    public $timestamps = false;

    protected $fillable = [
        'loteid',
        'fecha',
        'temperatura',
        'humedad',
        'lluvia',
        'observaciones',
    ];

    // Relación con Lote
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'loteid', 'loteid');
    }
}