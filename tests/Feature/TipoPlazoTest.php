<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TipoPlazoTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_obtener_tipo_plazo_panel(){
        $response = $this->get('/api/tipoPlazo');

        $response->assertStatus(200)->assertJson(["cod"=>"00"]);
    }

    public function test_crear_tipo_plazo(){
        $response = $this->json('POST', '/api/tipoPlazo', [
            "descripcion"=>"Trimestral",
            "factor_divisor"=>"24",
            "dias_vencimiento"=>"15",
            "interes"=>"12",
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);


        $response = $this->json('POST', '/api/tipoPlazo', [	
            "descripcion"=>"Quincenal",
            "factor_divisor"=>"24",
            "dias_vencimiento"=>"15",
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

    }

    public function test_modificar_tipo_plazo(){
        $response = $this->json('PUT', '/api/tipoPlazo/1', [
            "descripcion"=>"Bimestral",
            "factor_divisor"=>"24",
            "dias_vencimiento"=>"15",
            "interes"=>"12",
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('PUT', '/api/tipoPlazo/1', [
            "factor_divisor"=>"24",
            "dias_vencimiento"=>"15",
            "interes"=>"12",
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);


    }

    public function test_eliminar_tipo_plazo(){
        $response = $this->json('DELETE', '/api/tipoPlazo/99',[]);
        $response->assertStatus(200)->assertJson(['cod' => "04"]);


        $response = $this->json('DELETE', '/api/tipoPlazo/1',[]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }
}
