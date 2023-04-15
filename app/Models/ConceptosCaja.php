<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptosCaja extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $table = 'conceptos_caja';
    protected $fillable = [
        'tipo',
        'descripcion'
    ];

    /*public function ConceptosCajas(){
        return $this->hasMany(Operacion::class);
    }*/
}
