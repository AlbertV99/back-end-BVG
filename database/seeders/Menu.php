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
                ["descripcion"=>"Documentos","direccion"=>"documento","dir_imagen"=>"imagenes/opcionMenu/documento.png"],
            ]],
            ["agrupador"=>"Cliente","icono"=>"CgUserList", "opciones"=>[
                ["descripcion"=>"Clientes","direccion"=>"cliente","dir_imagen"=>"imagenes/opcionMenu/cliente.png"],
                ["descripcion"=>"PerfilCliente","direccion"=>"perfilCliente","dir_imagen"=>"imagenes/opcionMenu/perfilCliente.png"],
                ["descripcion"=>"Barrio","direccion"=>"barrio","dir_imagen"=>"imagenes/opcionMenu/barrio.png"],
            ]], #
            ["agrupador"=>"Credito","icono"=>"CgCreditCard","opciones"=>[
                ["descripcion"=>"Tipo Plazo","direccion"=>"tipoPlazo","dir_imagen"=>"imagenes/opcionMenu/tipoPlazo.png"],
                ["descripcion"=>"Solicitud Agente","direccion"=>"solicitudAgente","dir_imagen"=>"imagenes/opcionMenu/solicitudAgente.png"],
                ["descripcion"=>"Solicitud Analisis","direccion"=>"solicitudAnalista","dir_imagen"=>"imagenes/opcionMenu/solicitudAnalista.png"],
                ["descripcion"=>"Solicitud Directorio","direccion"=>"solicitudDirectorio","dir_imagen"=>"imagenes/opcionMenu/solicitudDirectorio.png"],
            ]], #
            ["agrupador"=>"Caja","icono"=>"CgCalendarDates","opciones"=>[
                ["descripcion"=>"Conceptos","direccion"=>"conceptosCaja","dir_imagen"=>"imagenes/opcionMenu/conceptoCaja.png"],
                ["descripcion"=>"Cajas","direccion"=>"caja","dir_imagen"=>"imagenes/opcionMenu/caja.png"],
                ["descripcion"=>"Movimientos","direccion"=>"operacion","dir_imagen"=>"imagenes/opcionMenu/movimientos.png"],
            ]], #
            ["agrupador"=>"Seguridad","icono"=>"CgLock", "opciones"=>[
                ["descripcion"=>"Usuarios","direccion"=>"usuario","dir_imagen"=>"imagenes/opcionMenu/usuario.png"],
                ["descripcion"=>"Perfil","direccion"=>"perfil","dir_imagen"=>"imagenes/opcionMenu/perfil.png"],
                ["descripcion"=>"Agrupadores","direccion"=>"agrupador","dir_imagen"=>"imagenes/opcionMenu/agrupador.png"],
                ["descripcion"=>"Opciones de Menu","direccion"=>"opcionMenu","dir_imagen"=>"imagenes/opcionMenu/opcionMenu.png"],
            ]], #
            ["agrupador"=>"Reportes","icono"=>"CgReadme","opciones"=>[
                ["descripcion"=>"Reporte Usuario","direccion"=>"reporteUsuario","dir_imagen"=>"imagenes/opcionMenu/reporte.png"],
                ["descripcion"=>"Reporte Cliente","direccion"=>"reporteCliente","dir_imagen"=>"imagenes/opcionMenu/reporte.png"],
                ["descripcion"=>"Reporte Estadistica","direccion"=>"reporteEstadistica","dir_imagen"=>"imagenes/opcionMenu/estadistico.png"],
                ["descripcion"=>"Reporte Balance","direccion"=>"reporteBalance","dir_imagen"=>"imagenes/opcionMenu/reporte.png"],
                ]], #
        ];
        $estados = [];
        foreach ($lista as  $value) {
            $agrupador = Agrupador::create([
                'descripcion' => $value["agrupador"],
                'icono'=>$value['icono'],
            ]);
            foreach ($value["opciones"] as $opcion) {
                $agrupador->opciones()->save(new OpcionMenu(['descripcion'=>$opcion['descripcion'],"observacion"=>$observacionMenuOpcionDefecto,'direccion'=>$opcion['direccion'],'dir_imagen'=>$opcion['dir_imagen']]));
            }
        }
    }
}
