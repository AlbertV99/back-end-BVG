<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model
{
    use HasFactory;
    protected $table = 'historial_estado';
    protected $fillable = [
        'solicitud_id',
        'estado_id',
        'observacion_cambio'
    ];
    public function solicitud(): BelongsTo{
        return $this->belongsTo(Solicitud::class);
    }
    
}
