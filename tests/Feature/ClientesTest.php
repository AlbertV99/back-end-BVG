<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/api/cliente');

        $response->assertStatus(200)->assertJson(["cod"=>"00"]);
    }
    public function test_crear_cliente(){
        //Pruebas exitosas
        $response = $this->json('POST', '/api/cliente', [
        'documento' => '5663611',
        'barrio' => '1',
        'tipo_documento' => '1',
        'nombre' => 'Melinda Sueli',
        'apellido' => 'Gimenez Aveiro',
        'f_nacimiento' => '1999-02-19',
        'correo' => 'melisueli@gmail.com',
        'direccion' => 'villa hayes',
        'sexo' => 'Feme',
        'observaciones' => 'Ninguna',
        'estado_civil' => '1',
        "tel_cliente" => [
			["telefono_cliente"=>"0974155070"],
			["telefono_cliente"=>"0993363999"],
			["telefono_cliente"=>"0974650507"],
	    ]]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('POST', '/api/cliente', [
            'barrio' => '1',
            'tipo_documento' => '1',
            'nombre' => 'Melinda Sueli',
            'apellido' => 'Gimenez Aveiro',
            'f_nacimiento' => '1999-02-19',
            'correo' => 'melisueli@gmail.com',
            'direccion' => 'villa hayes',
            'sexo' => 'Feme',
            'observaciones' => 'Ninguna',
            'estado_civil' => '1',
            "tel_cliente" => [
                ["telefono_cliente"=>"0974155070"],
                ["telefono_cliente"=>"0993363999"],
                ["telefono_cliente"=>"0974650507"],
            ]]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);


    }

   /* public function test_modificar_cliente(){
        $response = $this->json('PUT', '/api/cliente/1', [
        'documento' => '5663612',
        'barrio' => '1',
        'tipo_documento' => '1',
        'nombre' => 'Melinda Sueli',
        'apellido' => 'Gimenez Aveiro',
        'f_nacimiento' => '1999-02-19',
        'correo' => 'melisueli@gmail.com',
        'direccion' => 'villa hayes',
        'sexo' => 'Feme',
        'observaciones' => 'Ninguna',
        'estado_civil' => '1',
        "tel_cliente" => [
            ["telefono_cliente"=>"0974155070"],
            ["telefono_cliente"=>"0993363999"],
            ["telefono_cliente"=>"0974650507"],
        ]]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        $response = $this->json('PUT', '/api/cliente/1', [
            'barrio' => '1',
            'tipo_documento' => '1',
            'nombre' => 'Melinda Sueli',
            'apellido' => 'Gimenez Aveiro',
            'f_nacimiento' => '1999-02-19',
            'correo' => 'melisueli@gmail.com',
            'direccion' => 'villa hayes',
            'sexo' => 'Feme',
            'observaciones' => 'Ninguna',
            'estado_civil' => '1',
            "tel_cliente" => [
                ["telefono_cliente"=>"0974155070"],
                ["telefono_cliente"=>"0993363999"],
                ["telefono_cliente"=>"0974650507"],
            ]]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);



    }*/

   /*public function test_eliminar_cliente(){
        $response = $this->json('DELETE', '/api/cliente/99',[]);
        $response->assertStatus(200)->assertJson(['cod' => "08"]);


        $response = $this->json('DELETE', '/api/cliente/1',[]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }*/
}

