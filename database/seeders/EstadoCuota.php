<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EstadoCuota extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista = [
            "Pendiente",
            "Pagado",
            "Vencido",
        ];
        foreach ($lista as $key => $value) {
            EstadoCuota::create([
                'descripcion' => $value,
            ]);
        }
    }
}
