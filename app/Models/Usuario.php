<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'usuario';
    const DELETED_AT = 'activo';
    protected $fillable = [
        'nombre_usuario',
        'nombre',
        'cedula',
        'fecha_nacimiento',
        'email',
        'perfil_id',
        'restablecer_pass'
    ];

    public function perfil(){
        return $this->belongsTo(Perfil::class,"perfil_id","id");
    }
}
