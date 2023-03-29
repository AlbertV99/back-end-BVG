<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilCliente extends Model
{
    use HasFactory;
    protected $table = 'parametros_perfil';
    protected $fillable = [
        'parametro',
        'descripcion',
    ];

    public function parametros(){
        return $this->hasMany(ParametrosPerfilCliente::class,"parametro_id","id");
    }

}
