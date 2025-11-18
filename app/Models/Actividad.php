<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividad';
    protected $primaryKey = 'actividadid';
    public $timestamps = false;

    protected $fillable = [
        'loteid',
        'usuarioid',
        'descripcion',
        'fechainicio',
        'fechafin',
        'tipoactividadid',
        'prioridadid',
        'observaciones',
    ];

    // Relación con Lote
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'loteid', 'loteid');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }

    // Tipo de actividad (siembra, riego, etc.)
    public function tipoActividad()
    {
        return $this->belongsTo(TipoActividad::class, 'tipoactividadid', 'tipoactividadid');
    }

    // Prioridad (alta, media, baja)
    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'prioridadid', 'prioridadid');
    }
}