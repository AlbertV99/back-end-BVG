<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AperturaCaja extends Model{
    use HasFactory;
    protected $table = 'aperturas_caja';
    protected $fillable = [
        'caja',
        'usuario_id',
        'saldo_apertura',
        'saldo_cierre',
        'fecha_apertura',
        'fecha_cierre',
        'estado'
    ];
    public function caja(){
        return $this->belongsTo(Caja::class,"id","caja");
    }
}
