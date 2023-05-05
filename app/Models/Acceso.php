<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceso extends Model{
    
    use HasFactory;
    protected $table = 'acceso';
    protected $fillable = [
        'perfil_id',
        'opcion_id',
        'acceso'
    ];
    public function solicitud(){
        return $this->belongsTo(Solicitud::class);
    }
}
