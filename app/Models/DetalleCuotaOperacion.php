<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCuotaOperacion extends Model
{
    use HasFactory;
    protected $table = 'detalle_operacion';
    protected $fillable = [
        'cuota_id',
        'operacion_id'
    ];

    public function solicitud(){
        return $this->belongsTo(Solicitud::class,'operacion_id','id');
    }
}
