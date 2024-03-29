<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'estado_civil';
    protected $fillable = [
        'descripcion'
    ];
}
