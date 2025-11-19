<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'usuario';
    protected $primaryKey = 'usuarioid';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'nombreusuario',
        'telefono',
        'passwordhash',
        'imagenurl',
        'informacionadicional',
        'fecharegistro',
        'fechamodificacion',
        'ultimologin',
        'activo',
    ];

    // N:N con roles
    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'usuariorol',
            'usuarioid',
            'rolid',
            'usuarioid',
            'rolid'
        );
    }

    // Un usuario tiene muchos lotes
    public function lotes()
    {
        return $this->hasMany(Lote::class, 'usuarioid', 'usuarioid');
    }

    // Un usuario tiene muchas actividades
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'usuarioid', 'usuarioid');
    }

    // Un usuario registra muchos insumos aplicados al lote
    public function loteInsumos()
    {
        return $this->hasMany(LoteInsumo::class, 'usuarioid', 'usuarioid');
    }

    public function historialEstadosLote()
    {
        return $this->hasMany(HistorialEstadoLote::class, 'usuarioid', 'usuarioid');
    }
}