<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
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

    public function referenciaPersonal(): HasMany{
        return $this->hasMany(ReferenciaPersonal::class);
    }
    public function referenciaComercial(): HasMany{
        return $this->hasMany(ReferenciaComercial::class);
    }
    public function historialEstado(): HasMany{
        return $this->hasMany(HistorialEstado::class);
    }

    public function estado(): HasManyThrough{
        return $this->hasManyThrough(EstadoSolicitud::class, HistorialEstado::class);
    }


}
