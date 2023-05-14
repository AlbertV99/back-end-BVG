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
        'dir_imagen'
    ];

    public function agrupador(){
        return $this->belongsTo(Agrupador::class,'agrupador_id','id');
    }
    public function accesos(){
        return $this->hasMany(Acceso ::class);
    }
}
