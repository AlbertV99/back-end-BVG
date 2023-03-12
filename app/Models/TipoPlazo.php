<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPlazo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tipo_plazo';
    protected $fillable = [
        'descripcion',
        'factor_divisor',
        'dias_vencimiento',
        'interes',
    ];
}
