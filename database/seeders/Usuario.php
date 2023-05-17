<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario as UsuarioModel;

class Usuario extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista = [
            ["admin","Administrador","Sistema","123456","153699","19/02/1999","admin@gmail.com",1,false],
        ];

        foreach ($lista as $key => $value) {
            UsuarioModel::create([
                'nombre_usuario' => $value[0],
                'nombre' => $value[1],
                'apellido' => $value[2],
                'cedula' => $value[3],
                'password' =>bcrypt($value[4]),
                'fecha_nacimiento' => $value[5],
                'email' => $value[6],
                'perfil_id' => $value[7],
                'restablecer_password' => $value[8],
            ]);
        }
    }
}
