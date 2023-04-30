<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SolicitudTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_obtener_solicitud(){

        $response = $this->get('/api/solicitud/pendiente'); // SE UTILIZA ID 1 YA QUE EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/solicitud/analizado'); // SE UTILIZA ID 1 YA QUE EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/solicitud/aprobado');
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/solicitud/rechazado'); // SE UTILIZA ID 1 YA QUE EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/solicitud/desembolsado'); // SE UTILIZA ID 1 YA QUE EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/solicitud/cancelado'); // SE UTILIZA ID 1 YA QUE EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/solicitud/finalizado'); // SE UTILIZA ID 1 YA QUE EXISTE

    }

    public function test_crear_solicitud(){

        $response = $this->json('POST','/api/solicitud', [
        	"cliente_id"=>"1",
        	"ingresos_actuales"=>"5000000",
        	"monto_credito"=>"3000000",
        	"gastos_administrativos"=>"150000",
        	"interes"=>"5",
        	"interes_moratorio"=>"3",
        	"tipo_plazo"=>"3",
        	"observacion"=>"prueba de solicitud",
        	"usuario_id"=>"1",
        	"cant_cuotas"=>"12",
        	"inicio_cuota"=>"10",
        	"ref_personales"=>[
        		["cliente_id"=>"2","relacion_cliente"=>"vecino"]
        	],
        	"ref_comerciales"=>[
        		["entidad"=>"Banco Itau","monto_cuota"=>"500000","estado"=>"pendiente","cuotas_pendientes"=>"5","cuotas_totales"=>"7"],
        		["entidad"=>"Banco familiar","monto_cuota"=>"500000","estado"=>"pendiente","cuotas_pendientes"=>"5","cuotas_totales"=>"7"],
        		["entidad"=>"Banco Basa","monto_cuota"=>"500000","estado"=>"pendiente","cuotas_pendientes"=>"5","cuotas_totales"=>"7"]
        	]
        ]);

        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

    }

    public function test_modificar_referencias(){
        $response = $this->json('PUT','/api/solicitud/1',
            [
            	"ref_personales"=>[
            		["cliente_id"=>"2","relacion_cliente"=>"hermano"]
            	],
            	"ref_comerciales"=>[
            		[
            			"entidad"=> "Familiar",
            			"estado"=> "ACTIVO",
            			"monto_cuota"=> 200000,
            			"cuotas_pendientes"=> 2,
            			"cuotas_totales"=> 10
            		],
            		[
            			"entidad"=> "Itau",
            			"estado"=> "ACTIVO",
            			"monto_cuota"=> 20000,
            			"cuotas_pendientes"=> 5,
            			"cuotas_totales"=> 10
            		]
            	],
            	"estado_id"=>"2",
            	"observacion"=>"Prueba de cambio de estado"
            ]);

        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

    }

    public function test_cambiar_estados(){
        $response = $this->json('PUT','/api/solicitud/1/estado',
        [
          "estado_id"=>"3",
            "observacion"=>"desembolso de la solicitud"
        ]);

        $response->assertStatus(200)->assertJson(["cod"=>"00"]);


    }






}
