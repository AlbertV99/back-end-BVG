<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
        // \App\Models\User::factory(10)->create();
        $this->call([
            BarrioSemilla::class,
            EstadoCivilSemilla::class,
            EstadoSolicitud::class,
            TipoDocumentoCliente::class,
            TipoPlazo::class,
            PerfilClienteParametros::class,
            EstadoCuota::class,
            ConceptosCaja::class,
            Menu::class,
        ]);
    }
}
