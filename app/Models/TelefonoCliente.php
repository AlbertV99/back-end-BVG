<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelefonoCliente extends Model{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'telefono_cliente';
    protected $fillable = [
        'id_cliente',
        'telefono'
    ];
    public function solicitud(): BelongsTo{
        return $this->belongsTo(Cliente::class);
    }
}
