<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCuota extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $table = 'estado_cuota';
    protected $fillable = [
        'descripcion'
    ];

    /*public function EstadosCuotas(){
        return $this->hasMany(Cuotas::class);
    }*/
}
