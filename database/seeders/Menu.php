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
            ["agrupador"=>"Documento","icono"=>"CgFileDocument", "opciones"=>[
                ["descripcion"=>"Documentos","direccion"=>"documento"],
            ]],
            ["agrupador"=>"Cliente","icono"=>"CgUserList", "opciones"=>[
                ["descripcion"=>"Clientes","direccion"=>"cliente"],
                ["descripcion"=>"PerfilCliente","direccion"=>"perfilCliente"],
                ["descripcion"=>"Barrio","direccion"=>"barrio"],
            ]], #
            ["agrupador"=>"Credito","icono"=>"CgCreditCard","opciones"=>[
                ["descripcion"=>"Tipo Plazo","direccion"=>"tipoPlazo"],
                ["descripcion"=>"Solicitud Agente","direccion"=>"solicitudAgente"],
                ["descripcion"=>"Solicitud Analisis","direccion"=>"solicitudAnalista"],
                ["descripcion"=>"Solicitud Directorio","direccion"=>"solicitudDirectorio"],
            ]], #
            ["agrupador"=>"Caja","icono"=>"CgCalendarDates","opciones"=>[
                ["descripcion"=>"Conceptos","direccion"=>"conceptosCaja"],
                ["descripcion"=>"Cajas","direccion"=>"caja"],
                ["descripcion"=>"Movimientos","direccion"=>"operacion"],
            ]], #
            ["agrupador"=>"Seguridad","icono"=>"CgLock", "opciones"=>[
                ["descripcion"=>"Usuarios","direccion"=>"usuario"],
                ["descripcion"=>"Perfil","direccion"=>"perfil"],
                ["descripcion"=>"Agrupadores","direccion"=>"agrupador"],
                ["descripcion"=>"Opciones de Menu","direccion"=>"opcionMenu"],
            ]], #
            ["agrupador"=>"Reportes","icono"=>"CgReadme","opciones"=>[]], #
        ];
        $estados = [];
        foreach ($lista as  $value) {
            $agrupador = Agrupador::create([
                'descripcion' => $value["agrupador"],
                'icono'=>$value['icono'],
            ]);
            foreach ($value["opciones"] as $opcion) {
                $agrupador->opciones()->save(new OpcionMenu(['descripcion'=>$opcion['descripcion'],"observacion"=>$observacionMenuOpcionDefecto,'direccion'=>$opcion['direccion']]));
            }
        }
    }
}
