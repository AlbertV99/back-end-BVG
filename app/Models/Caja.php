<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model{

    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'eliminado';
    protected $table = 'caja';
    protected $fillable = [
        'descripcion',
        'pin',
        'saldo_actual'
    ];

    public function estadoCaja(){
        return $this->hasMany(AperturaCaja::class,'caja');
    }
}
