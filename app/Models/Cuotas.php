<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuotas extends Model{
    use HasFactory;

    protected $table = 'cuotas';
    protected $fillable = [
        'solicitud_id',
        'cuota',
        'saldo',
        'interes',
        'amortizacion',
        'mora',
        'capital',
        'estado',
        'n_cuota',
        'fec_vencimiento'
    ];

    public function solicitud(){
        return $this->belongsTo(Solicitud::class,'solictud_id','id');
    }
}
