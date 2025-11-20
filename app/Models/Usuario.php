<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    protected $hidden = [
        'passwordhash',
    ];

    // Laravel por defecto busca "password", así que le decimos que use "passwordhash"
    public function getAuthPassword()
    {
        return $this->passwordhash;
    }

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

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'usuarioid', 'usuarioid');
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'usuarioid', 'usuarioid');
    }

    public function loteInsumos()
    {
        return $this->hasMany(LoteInsumo::class, 'usuarioid', 'usuarioid');
    }

    public function historialEstadosLote()
    {
        return $this->hasMany(HistorialEstadoLote::class, 'usuarioid', 'usuarioid');
    }
}