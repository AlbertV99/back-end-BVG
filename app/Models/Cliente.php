<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
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
    public function telefonoUpdate(){
        return $this->hash_update(TelefonoCliente::class,'id_cliente');
    }
    public function solicitud(): HasMany{
        return $this->hasMany(Solicitud::class,'cliente_id');
    }

}
