<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConceptosCaja as ConceptosCajaModel;

class ConceptosCaja extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista = [
            ["Entrada","Pago Cuota"],
            ["Salida", "Desembolso"]
        ];
        foreach ($lista as $key => $value) {
            ConceptosCajaModel::create([
                'tipo' => $value[0],
                'descripcion' => $value[1]
            ]);
        }
    }
}
