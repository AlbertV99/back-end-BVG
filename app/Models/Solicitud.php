<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model{
    use HasFactory;
    protected $table = 'solicitud';
    protected $fillable = [
        'cliente_id',
        'ingresos_actuales',
        'monto_credito',
        'gastos_administrativos',
        'interes',
        'interes_moratorio',
        'tipo_plazo',
        'observacion',
        'usuario_id',
        'fecha_retiro',
        'vencimiento_retiro'

    ];

    public function referenciaPersonal(){
        return $this->hasMany(ReferenciaPersonal::class,"solicitud_id");
    }
    public function referenciaComercial(){
        return $this->hasMany(ReferenciaComercial::class,"solicitud_id");
    }
    public function historialEstado(){
        return $this->hasMany(HistorialEstado::class);
    }

    public function tipoPlazo(){
        return $this->belongsTo(TipoPlazo::class,'tipo_plazo');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,"cliente_id");
    }

}
