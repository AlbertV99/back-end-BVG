<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenciaComercial extends Model
{
    use HasFactory;
    protected $table = 'referencia_comercial';
    protected $fillable = [
        'solicitud_id',
        'entidad',
        'estado',
        'monto_cuota',
        'cuotas_pendientes',
        'cuotas_totales',
    ];
}
