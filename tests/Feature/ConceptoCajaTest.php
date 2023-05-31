<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConceptoCajaTest extends TestCase
{
    /**
    * A basic feature test example.
    *
    * @return void
    */
    public function test_crear_concepto_caja(){
        //REGISTRO DE CONCEPTO CORRECTO
        $response = $this->json('POST', '/api/conceptoCaja', ['tipo' => 'ENTRADA','descripcion'=>'Insercion de capital para desembolso']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        //VALIDACION POR ESCRITURA INCORRECTA DE TIPO DE MOVIMIENTO
        $response = $this->json('POST', '/api/conceptoCaja', ['tipo' => 'ENTADA','descripcion'=>'Insercion de capital para desembolso']);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        //REGISTRO DE CONCEPTO CORRECTO
        $response = $this->json('POST', '/api/conceptoCaja', ['tipo' => 'SALIDA','descripcion'=>'Salida de capital para desembolso']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        //VALIDACION POR ESCRITURA INCORRECTA DE TIPO MOVIMIENTO
        $response = $this->json('POST', '/api/conceptoCaja', ['tipo' => 'SALDA','descripcion'=>'Salida de capital para desembolso']);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        //VALIDACION POR ESCRITURA INCORRECTA DE TIPO MOVIMIENTO
        $response = $this->json('POST', '/api/conceptoCaja', ['tipo' => 'SALDA','descripcion'=>'Salida de capital para desembolso']);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        //VALIDACION POR FALTA DE CAMPO DESCRIPCION
        $response = $this->json('POST', '/api/conceptoCaja', ['tipo' => 'SALDA',]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

    }

    public function test_obtener_concepto_caja_panel(){
        /*Prueba para obtener los datos de panel*/
        $response = $this->get('/api/conceptoCaja/');
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/conceptoCaja/u/1'); // SE UTILIZA ID 1 YA QUE EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"00"]);

        $response = $this->get('/api/conceptoCaja/u/99'); // SE UTILIZA ID 99 YA QUE NO EXISTE
        $response->assertStatus(200)->assertJson(["cod"=>"04"]);

    }

    public function test_modificar_concepto_caja(){
        //MODIFICACION DE CONCEPTO CORRECTO
        $response = $this->json('PUT', '/api/conceptoCaja/3', ['tipo' => 'ENTRADA','descripcion'=>'Modificacion de descripcion']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        //MODIFICACION DE TIPO CORRECTO
        $response = $this->json('PUT', '/api/conceptoCaja/3', ['tipo' => 'SALIDA','descripcion'=>'Modificacion de descripcion 2']);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);


        //VALIDACION POR ESCRITURA INCORRECTA DE TIPO DE MOVIMIENTO
        $response = $this->json('PUT', '/api/conceptoCaja/3', ['tipo' => 'SAIDA','descripcion'=>'Modificacion no realizada']);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        //VALIDACION POR ESCRITURA INCORRECTA DE TIPO DE MOVIMIENTO
        $response = $this->json('PUT', '/api/conceptoCaja/3', ['tipo' => 'ENTADA','descripcion'=>'Modificacion de descripcion']);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);


    }

    public function test_eliminar_concepto_caja(){
        $response = $this->json('DELETE', '/api/conceptoCaja/100',[]);
        $response->assertStatus(200)->assertJson(['cod' => "04"]);


        $response = $this->json('DELETE', '/api/conceptoCaja/4',[]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }

}
