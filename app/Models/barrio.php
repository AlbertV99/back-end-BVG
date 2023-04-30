<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class barrio extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'eliminado';
    public $timestamps = false;

    protected $table = 'barrio';
    protected $fillable = [
        'nombre',
        'observacion'
    ];

}
