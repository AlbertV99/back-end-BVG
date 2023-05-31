<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caja as CajaModel;

class Caja extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        CajaModel::create([
            'descripcion' => "Caja 01",
            'pin' => '123456',
            'saldo_actual'=>"0"
        ]);
    }
}
