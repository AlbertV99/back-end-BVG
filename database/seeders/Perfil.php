<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perfil as PerfilModel;

class Perfil extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista = [
        ["ADMINISTRADOR","Acceso a todos el sistema"],
    ];

        foreach ($lista as $key => $value) {
            PerfilModel::create([
                'descripcion' => $value[0],
                'observacion' => $value[1],
            ]);
        }
    }
}
