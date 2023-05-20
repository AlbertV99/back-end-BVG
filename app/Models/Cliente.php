<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'eliminado';
    protected $table = 'cliente';
    protected $fillable = [
        'barrio',
        'documento',
        'tipo_documento',
        'nombre',
        'apellido',
        'f_nacimiento',
        'correo',
        'direccion',
        'sexo',
        'observaciones',
        'estado_civil',
    ];
    public function telefono(){
        return $this->hasMany(TelefonoCliente::class,'id_cliente');
    }
    public function solicitud(){
        return $this->hasMany(Solicitud::class,'cliente_id');
    }
    public function cedula(){
        return $this->hasMany(Documento::class,'cliente_id');
    }

}
