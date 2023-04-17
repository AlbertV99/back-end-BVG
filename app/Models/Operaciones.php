<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operaciones extends Model
{
    use HasFactory;
    
    protected $table = 'operacion';
    protected $fillable = [
        'caja',
        'concepto',
        'saldo_anterior',
        'monto',
        'saldo_posterior',
        'fecha_operacion',
        'solicitud_id',
        'cuota_id',
        'usuario_id',
    ];
}

