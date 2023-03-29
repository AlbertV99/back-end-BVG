<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\PerfilCliente ;
use App\Models\ParametrosPerfilCliente ;



class PerfilClienteParametros extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $parametros = [
            [
                "parametro"=>"Edad",
                "descripcion"=>"Indica dependiendo de la edad que tenga el cliente asignarle un puntaje.",
                "rangos"=>[
                    [
                        "rango_inf"=>"18",
                        "rango_sup"=>"25",
                        "punto"=>"3"
                    ],
                    [
                        "rango_inf"=>"26",
                        "rango_sup"=>"45",
                        "punto"=>"2"
                    ],
                    [
                        "rango_inf"=>"45",
                        "rango_sup"=>"99",
                        "punto"=>"1"
                    ]
                ]
            ],
            [
                "parametro"=>"Promedio Atrasos",
                "descripcion"=>"Dependiendo de el atraso en todos los creditos realizados, promediado para asignarle un puntaje",
                "rangos"=>[
                    [
                        "rango_inf"=>"0",
                        "rango_sup"=>"3",
                        "punto"=>"3"
                    ],
                    [
                        "rango_inf"=>"4",
                        "rango_sup"=>"7",
                        "punto"=>"2"
                    ],
                    [
                        "rango_inf"=>"8",
                        "rango_sup"=>"99",
                        "punto"=>"1"
                    ]
                ]
            ],
            [
                "parametro"=>"Maximo Atrasos",
                "descripcion"=>"Dependiendo la mayor cantidad de dias de atraso realizado alguna vez, asigna un puntaje",
                "rangos"=>[
                    [
                        "rango_inf"=>"0",
                        "rango_sup"=>"8",
                        "punto"=>"3"
                    ],
                    [
                        "rango_inf"=>"9",
                        "rango_sup"=>"20",
                        "punto"=>"2"
                    ],
                    [
                        "rango_inf"=>"20",
                        "rango_sup"=>"99",
                        "punto"=>"1"
                    ]
                ]
            ]
        ];


        foreach ($parametros as $key => $value) {
            $perfil = PerfilCliente::create([
                'parametro' => $value["parametro"],
                'descripcion' => $value["descripcion"],
            ]);
            $parametros = [];
            foreach ($value['rangos'] as  $rango) {
                array_push($parametros,(new ParametrosPerfilCliente($rango)));
            }
            $perfil->parametros()->saveMany($parametros);
        }



    }
}
