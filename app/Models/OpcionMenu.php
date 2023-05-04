<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionMenu extends Model{
    use HasFactory;
    protected $table = 'opcion_menu';
    public $timestamps = false;
    protected $fillable = [
        'descripcion',
        'observacion',
        'agrupador_id',
    ];

    public function solicitud(){
        return $this->belongsTo(Agrupador::class,'agrupador_id','id');
    }
}
