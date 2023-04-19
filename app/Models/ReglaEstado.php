<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglaEstado extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'reglas_estados';
    protected $fillable = [
        'estado_regla',
        'estado_posible'
    ];
    public function estadoReglado(){
        return $this->belongsTo(EstadoSolicitud::class,"estado_id","estado_regla");
    }
    public function estadoPosible(){
        return $this->hasMany(EstadoSolicitud::class,"id","estado_posible");
    }
}
