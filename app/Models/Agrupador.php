<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agrupador extends Model
{
    use HasFactory;
    protected $table = 'agrupador';
    public $timestamps = false;
    protected $fillable = [
        'icono',
        'descripcion',
        'observacion'
    ];

    public function opciones(){
        return $this->hasMany(OpcionMenu::class,"agrupador_id");
    }
}
