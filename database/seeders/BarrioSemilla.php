<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\barrio;

class BarrioSemilla extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $lista = [
            "Golondrina",
            "Cerro",
            "San Jorge",
            "Alonso",
            "Ciudad Nueva",
        ];
        foreach ($lista as $key => $value) {
            barrio::create([
                'nombre' => $value,
                'observacion' => 'Dato semilla',
            ]);
        }
    }
}
/*
Golondrina
Cerro
San jorge
Alonso
Ciudad Nueva

 */
