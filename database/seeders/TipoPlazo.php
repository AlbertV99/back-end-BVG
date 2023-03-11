<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TipoPlazo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista = [ # agregar intereses para diferencias de plazos | se pueden definir montos maximos minimos y otros detalles
            ["Diario","365","1","20"],
            ["Semanal","52","7","15"],
            ["Quincenal","24","15","12"],
            ["Mensual","12","30","8"],
        ];
    }
}
