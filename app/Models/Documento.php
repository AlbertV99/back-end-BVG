<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model{
    use HasFactory;
    protected $table = 'documento';
    protected $fillable = [
        'nombre',
        'fecha_vencimiento',
        'cliente_id',
        'solicitud_id_contrato',
        'solicitud_id_pagare',
    ];

    public function solicitudContrato(){
        return $this->belongsTo(Solicitud::class,'solicitud_id_contrato','id');
    }
    public function solicitudPagare(){
        return $this->belongsTo(Solicitud::class,'solicitud_id_pagare','id');
    }
    public function clienteCedula(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }
}
