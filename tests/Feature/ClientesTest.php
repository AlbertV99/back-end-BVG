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
        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])->post( '/api/cliente', [
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
        "tel_cliente" => json_encode([
			["telefono_cliente"=>"0974155070"],
			["telefono_cliente"=>"0993363999"],
			["telefono_cliente"=>"0974650507"],
	    ])]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        //test 2
        $response = $this->json('POST', '/api/cliente', [
        'documento' => '5031168',
        'barrio' => '2',
        'tipo_documento' => '1',
        'nombre' => 'Alberto Daniel',
        'apellido' => 'Valdez Urquhart',
        'f_nacimiento' => '1999-11-09',
        'correo' => 'alberto.valdez@gmail.com',
        'direccion' => 'Loma Pyta',
        'sexo' => 'Masc',
        'observaciones' => 'Ninguna',
        'estado_civil' => '1',
        "tel_cliente" => json_encode([
			["telefono_cliente"=>"0974155070"],
	    ])]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

        // test 3
        $response = $this->json('POST', '/api/cliente', [
        'documento' => '4002183',
        'barrio' => '4',
        'tipo_documento' => '1',
        'nombre' => 'Hugo Alejandro',
        'apellido' => 'Barrios Jara',
        'f_nacimiento' => '1995-03-07',
        'correo' => 'hugo.ortellado@gmail.com',
        'direccion' => 'Capiata',
        'sexo' => 'Masc',
        'observaciones' => 'Ninguna',
        'estado_civil' => '1',
        "tel_cliente" => json_encode([
            ["telefono_cliente"=>"0985222189"],
			["telefono_cliente"=>"0988331898"],
	    ])]);
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
            "tel_cliente" => json_encode([
                ["telefono_cliente"=>"0974155070"],
                ["telefono_cliente"=>"0993363999"],
                ["telefono_cliente"=>"0974650507"],
            ])]);
        $response->assertStatus(200)->assertJson(['cod' => "06"]);


    }

    public function test_modificar_cliente(){
        $response = $this->json('PUT', '/api/cliente/1', [
        'barrio' => '1',
        'tipo_documento' => '1',
        'nombre' => 'Elena ',
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
            'tipo_documento' => '1',
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

   /*public function test_eliminar_cliente(){
        $response = $this->json('DELETE', '/api/cliente/99',[]);
        $response->assertStatus(200)->assertJson(['cod' => "08"]);


        $response = $this->json('DELETE', '/api/cliente/1',[]);
        $response->assertStatus(200)->assertJson(['cod' => "00"]);

    }*/
}
