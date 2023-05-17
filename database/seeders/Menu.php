<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agrupador;
use App\Models\OpcionMenu;


class Menu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $observacionMenuOpcionDefecto = "CREADO POR SEMILLA";
        $icono = "testing";
        $lista = [
            ["agrupador"=>"Cliente","opciones"=>[
                ["descripcion"=>"Clientes","direccion"=>"cliente"],
                ["descripcion"=>"PerfilCliente","direccion"=>"perfilCliente"],
                ["descripcion"=>"Barrio","direccion"=>"barrio"],
            ]], #
            ["agrupador"=>"Credito","opciones"=>[
                ["descripcion"=>"Tipo Plazo","direccion"=>"tipoPlazo"],
                ["descripcion"=>"Solicitud Agente","direccion"=>"solicitudAgente"],
                ["descripcion"=>"Solicitud Analisis","direccion"=>"solicitudAnalista"],
                ["descripcion"=>"Solicitud Directorio","direccion"=>"solicitudDirectorio"],
            ]], #
            ["agrupador"=>"Caja","opciones"=>[
                ["descripcion"=>"Conceptos","direccion"=>"conceptosCaja"],
                ["descripcion"=>"Cajas","direccion"=>"caja"],
                ["descripcion"=>"Movimientos","direccion"=>"operacion"],
            ]], #
            ["agrupador"=>"Seguridad","opciones"=>[
                ["descripcion"=>"Usuarios","direccion"=>"usuario"],
                ["descripcion"=>"Perfil","direccion"=>"perfilCliente"],
                ["descripcion"=>"Agrupadores","direccion"=>"agrupador"],
                ["descripcion"=>"Opciones de Menu","direccion"=>"opcionMenu"],
            ]], #
            ["agrupador"=>"Reportes","opciones"=>[]], #
        ];
        $estados = [];
        foreach ($lista as  $value) {
            $agrupador = Agrupador::create([
                'descripcion' => $value["agrupador"],
            ]);
            foreach ($value["opciones"] as $opcion) {
                $agrupador->opciones()->save(new OpcionMenu(['descripcion'=>$opcion['descripcion'],"observacion"=>$observacionMenuOpcionDefecto,'direccion'=>$opcion['direccion']]));
            }
        }
    }
}
