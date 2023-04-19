<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoSolicitud extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'estado_solicitud';
    protected $fillable = [
        'descripcion'
    ];
    public function historiales(){
        return $this->hasMany(HistorialEstado::class,"estado_id");
    }
    public function regla(){
        return $this->hasMany(ReglaEstado::class,"estado_regla");
    }
}
