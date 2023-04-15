<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\TipoDocumento;


class TipoDocumentoCliente extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $lista = [
            "Cedula de Identidad",
            "R.U.C.",
            "Pasaporte",
        ];
        foreach ($lista as $key => $value) {
            TipoDocumento::create([
                'descripcion' => $value,
            ]);
        }
    }
}
