<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\TipoPlazo as TipoPlazoModel;


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

        foreach ($lista as $key => $value) {
            TipoPlazoModel::create([
                'descripcion' => $value[0],
                'factor_divisor' => $value[1],
                'dias_vencimiento' => $value[2],
                'interes' => $value[3],
            ]);
        }
    }
}
