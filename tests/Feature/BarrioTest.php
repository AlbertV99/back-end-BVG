<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BarrioTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_obtener_barrios_panel(){
        $response = $this->get('/api/barrio');

        $response->assertStatus(200)->assertJson(["cod"=>"00"]);
    }

    public function test_crear_barrio(){
        $response = $this->json('POST', '/api/barrio', ['nombre' => 'Barrio de prueba']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('POST', '/api/barrio', ['nombre' => 'Barrio de prueba c/ obs',"observacion"=>"Barrio de prueba con observacion"]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('POST', '/api/barrio', ["observacion"=>"Error a la hora de crear"]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

    }

    public function test_modificar_barrio(){
        $response = $this->json('PUT', '/api/barrio/1', ['nombre' => 'Barrio de prueba modificacion']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('PUT', '/api/barrio/2', ['nombre' => 'Barrio de prueba c/ obs modif',"observacion"=>"Barrio de prueba con observacion modificacion"]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('PUT', '/api/barrio/99', ["observacion"=>"Error a la hora de modificar"]);
        $response->assertStatus(200)->assertJson(['cod' => "05"]);

        $response = $this->json('PUT', '/api/barrio/2', ["observacion"=>"Error a la hora de modificar"]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);


    }

    public function test_eliminar_barrio(){
        $response = $this->json('DELETE', '/api/barrio/99',[]);
        $response->assertStatus(200)->assertJson(['cod' => "08"]);


        $response = $this->json('DELETE', '/api/barrio/1',[]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }
}
