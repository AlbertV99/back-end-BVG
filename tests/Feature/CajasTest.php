<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CajasTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example(){
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    /*Testeo para obtener el panel*/
    /*

    Route::get('/conceptoCaja/{pag?}','index');
    Route::get('/conceptoCaja/u/{id}','show');
    Route::post('/conceptoCaja/','store');
    Route::put('/conceptoCaja/{id}','update');
    Route::delete('/conceptoCaja/{id}','destroy');
    */
    public function test_crear_caja(){
        // VALIDACION DE CAMPO PIN
        $response = $this->json('POST', '/api/caja', ['descripcion' => 'Barrio de prueba']);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        // VALIDACION DE CAMPO DESCRIPCION
        $response = $this->json('POST', '/api/caja', ['pin'=>"123456"]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        // REGISTRO CORRECTO
        $response = $this->json('POST', '/api/caja', ['descripcion' => 'Caja Test','pin'=>"123456"]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }

    public function test_obtener_caja_panel(){
        /*Prueba para obtener los datos de panel*/
        $response = $this->get('/api/conceptoCaja/');
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/conceptoCaja/u/1'); // SE UTILIZA ID 1 YA QUE EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/conceptoCaja/u/99'); // SE UTILIZA ID 99 YA QUE NO EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"04"]);

    }

    public function test_modificar_caja(){
        // MODIFICACION CORRECTA
        $response = $this->json('POST', '/api/caja', ['descripcion' => 'Caja Test','pin'=>"123456"]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }


}
