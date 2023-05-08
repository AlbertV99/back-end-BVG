<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpcionMenuTest extends TestCase{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_obtener_agrupador_panel(){
        $response = $this->get('/api/agrupador');

        $response->assertStatus(200)->assertJson(["cod"=>"00"]);
    }

    public function test_crear_agrupador(){
        $response = $this->json('POST', '/api/agrupador', ['descripcion' => 'AgrupadorTest','icono'=>'testing']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('POST', '/api/agrupador', ['descripcion' => 'TestAgrupador',"observacion"=>"Agrupador de prueba con observacion e icono",'icono'=>'testing']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('POST', '/api/agrupador', ['descripcion' => 'TestAgrupador',"observacion"=>"Agrupador de prueba con observacion sin icono"]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        $response = $this->json('POST', '/api/agrupador', ["observacion"=>"Error a la hora de crear"]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

    }

    public function test_modificar_agrupador(){
        $response = $this->json('PUT', '/api/agrupador/1', ['descripcion' => 'AgrupadorTest','icono'=>'testing']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('PUT', '/api/agrupador/1', ['descripcion' => 'TestAgrupador',"observacion"=>"Modificacion de Agrupador de prueba con observacion e icono",'icono'=>'testing']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('PUT', '/api/agrupador/99', ['descripcion' => 'TestAgrupador',"observacion"=>"Modificacion de Agrupador de prueba con observacion sin icono"]);
        $response->assertStatus(200)->assertJson(['cod' => "05"]);

        $response = $this->json('PUT', '/api/agrupador/1', ["observacion"=>"Error a la hora de crear"]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);


    }

    /*public function test_eliminar_agrupador(){
        $response = $this->json('DELETE', '/api/agrupador/99',[]);
        $response->assertStatus(200)->assertJson(['cod' => "08"]);


        $response = $this->json('DELETE', '/api/agrupador/9',[]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }*/
}
