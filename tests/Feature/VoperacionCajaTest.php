<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OperacionesCaja extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_abrir_caja(){

        $response = $this->json('POST', '/api/apertura/caja/1', [
            'saldo' => '10000000',
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

        $response = $this->json('POST', '/api/apertura/caja/4', [
            'saldo' => '10000000',
            'pin' => '123456'
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "04"]);

        $response = $this->json('POST', '/api/apertura/caja/1', [
            'saldo' => '10000000',
            'pin' => '123456'
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);
        
        $response = $this->json('POST', '/api/apertura/caja/1', [
            'saldo' => '10000000',
            'pin' => '123456'
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "11"]);

    }

    public function test_obtener_desembolsos(){
        $response = $this->get('/api/operaciones');
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }

    public function test_insertar_desembolsos(){
        $response = $this->json('POST', '/api/operaciones/desembolsar', [
            "caja"=>"1",
            "monto"=>"3000000",
            "solicitud_id"=>"1",
            "usuario_id"=>"1",
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('POST', '/api/operaciones/desembolsar', [
            "caja"=>"1",
            "solicitud_id"=>"1",
            "usuario_id"=>"1",
        ]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);

    }

    public function test_cerrar_caja(){
        $response = $this->json('POST', '/api/cierre/caja/1');
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('POST', '/api/cierre/caja/4');
        $response->assertStatus(200)->assertJson(['cod' => "04"]);

    }



}
