<!DOCTYPE html>

<html> 
    <title>B-VG</title>
<head>
    <style>
        table{
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #dddddd;
            text-aling: left;
            padding: 8px;
        }
        tr:nth-child(even){
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>

    <h4>{{ $entrada }}</h4>
    <h4>{{ $cantEntrada }}</h4>

    <h4>{{ $salida }}</h4>
    <h4>{{ $cantSalida}}</h4>

    <h4>{{ $salida}}</h4>
    <!-- <h3>Cantidad de usuario: {{ $cantidadUsuarios  }}</h3>
    <table> 
        <tr>
            <th>Usuario</th>
            <th>Cedula</th>
            <th>Nombre y Apellido</th>
            <th>Perfil</th>
        </tr>
        @foreach($datos as $value)
        <tr>
            <td> {{$value->nombre_usuario  }}</td>
            <td>{{$value->cedula  }}</td>
            <td>{{ $value->nombre . ' ' . $value->apellido }}</td>
            <td>{{$value->descripcion  }}</td>
        </tr>
        @endforeach
    </table> -->
</body>

</html>