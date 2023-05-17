<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perfil as PerfilModel;
use App\Models\OpcionMenu;
use App\Models\Acceso;

class Perfil extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $opcionMenu = OpcionMenu::select("id")->get();
        $lista = [
        ["descripcion"=>"ADMINISTRADOR","observacion"=>"Acceso a todos el sistema","acceso"=>$opcionMenu],
        ["descripcion"=>"SIN ACCESOS","observacion"=>"Sin acceso a el sistema","acceso"=>[]],
    ];
        $perfiles = [];
        foreach ($lista as $key => $value) {
            $perfiles = PerfilModel::create([
                'descripcion' => $value["descripcion"],
                'observacion' => $value["observacion"],
            ]);
            foreach ($value['acceso'] as  $accesos) {
                $accesoPerfil = new Acceso(["opcion_id"=>$accesos['id'], "acceso"=>true]);
                $perfiles->accesos()->save($accesoPerfil);
            }
        }

    }
}
