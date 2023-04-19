<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\EstadoSolicitud as EstadoSolicitudModel;
use App\Models\ReglaEstado ;


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
            ["estado"=>"PENDIENTE","reglas"=>["ANALIZANDO","CANCELADO"]], # NUEVO [analizado | cancelado]
            ["estado"=>"ANALIZANDO","reglas"=>["APROBADO","RECHAZADO","PENDIENTE","CANCELADO"]], # EN ANALISIS [aprobado | rechazado | cancelado | pendiente (para volver a vendedor por datos pendientes) ]
            ["estado"=>"APROBADO","reglas"=>["DESEMBOLSADO","CANCELADO"]], # APROBADO [ desembolsado | cancelado (ejemplo :  no retiro su dinero en fecha maxima)]
            ["estado"=>"RECHAZADO","reglas"=>[]], # RECHAZADO
            ["estado"=>"DESEMBOLSADO","reglas"=>["FINALIZADO"]], # YA SE RETIRO EL DINERO, SE COMIENZAN A CONTAR LAS CUOTAS [ finalizado ]
            ["estado"=>"CANCELADO","reglas"=>[]], # CUANDO EL CLIENTE CIERRA ANTES DE LLEGAR A APROBAR/RECHAZAR LA SOLICITUD
            ["estado"=>"FINALIZADO","reglas"=>[]], # CUANDO SE TERMINAN DE PAGAR TODAS LAS CUOTAS [INACTIVO]
        ];
        $estados = [];
        foreach ($lista as $key => $value) {
            $estados[$value["estado"]] = EstadoSolicitudModel::create([
                'descripcion' => $value["estado"],
            ]);
        }
        foreach ($lista as  $estado) {
            $estadoSolicitud =  EstadoSolicitudModel::where('descripcion',$estado["estado"])->first();
            $regla="";
            foreach ($estado['reglas'] as  $regla) {
                $reglaEstado = new ReglaEstado(["estado_posible"=>$estados[$regla]['id']]);
                $estadoSolicitud->regla()->save($reglaEstado);
            }
        }

    }
}
