<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenciaPersonal extends Model{
    use HasFactory;
    protected $table = 'referencia_personal';
    protected $fillable = [
        'solicitud_id',
        'nombres_apellido',
        'relacion_cliente',
        'telefono'

    ];

    public function solicitud(): BelongsTo{
        return $this->belongsTo(Solicitud::class,"id","solicitud_id");
    }
}
