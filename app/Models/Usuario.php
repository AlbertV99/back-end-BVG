<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    use SoftDeletes;
    protected $table = 'usuario';
    const DELETED_AT = 'activo';
    protected $fillable = [
        'nombre_usuario',
        'nombre',
        'apellido',
        'cedula',
        'password',
        'fecha_nacimiento',
        'email',
        'perfil_id',
        'restablecer_password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function perfil(){
        return $this->belongsTo(Perfil::class,"perfil_id","id");
    }
}
