<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosPerfilCliente extends Model
{
    use HasFactory;
    protected $table = 'parametrizacion';
    protected $fillable = [
        'parametro_id',
        'rango_inf',
        'rango_sup',
        'punto',
    ];

    public function solicitud(){
        return $this->belongsTo(PerfilCliente::class);
    }
}
