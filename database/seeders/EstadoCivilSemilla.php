<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoCivil;


class EstadoCivilSemilla extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $lista = [
            "Soltero",
            "Casado",
            "Divorciado",
            "Separación en proceso judicia",
            "Viudo",
            "Concubinato",
        ];
        foreach ($lista as $key => $value) {
            EstadoCivil::create([
                'descripcion' => $value,
            ]);
        }
    }
}
