<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoCuota as EstadoCuotaModel;

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
            "PENDIENTE",
            "PAGADO",
            "VENCIDO",
        ];
        foreach ($lista as $key => $value) {
            EstadoCuotaModel::create([
                'descripcion' => $value,
            ]);
        }
    }
}
