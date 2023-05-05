<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model{

    use HasFactory;
    protected $table = 'perfil';
    protected $fillable = [
        'descripcion',
        'observacion',
    ];
    public function accesos(){
        return $this->belongsTo(Acceso::class);
    }
}
