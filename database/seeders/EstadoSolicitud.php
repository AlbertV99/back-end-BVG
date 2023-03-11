<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EstadoSolicitud extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista = [
            ["Pendiente"], # NUEVO [analizado | cancelado]
            ["Analizando"], # EN ANALISIS [aprobado | rechazado | cancelado | pendiente (para volver a vendedor por datos pendientes) ]
            ["Aprobado"], # APROBADO [ desembolsado | cancelado (ejemplo :  no retiro su dinero en fecha maxima)]
            ["Rechazado"], # RECHAZADO
            ["Desembolsado"], # YA SE RETIRO EL DINERO, SE COMIENZAN A CONTAR LAS CUOTAS [ finalizado ]
            ["Cancelado"], # CUANDO EL CLIENTE CIERRA ANTES DE LLEGAR A APROBAR/RECHAZAR LA SOLICITUD
            ["Finalizado"], # CUANDO SE TERMINAN DE PAGAR TODAS LAS CUOTAS [INACTIVO]
        ];

    }
}
