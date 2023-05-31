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
    <h3>Cantidad de clientes: {{ $cantidadClientes }}</h3>
    <table> 
        <tr>
            <th>Nombre y Apellido</th>
            <th>Documento</th>
            <th>Fecha Nacimiento</th>
            <th>Barrio</th>
        </tr>
        @foreach($datos as $value)
        <tr>
            <td>{{ $value->nombre . ' ' . $value->apellido }}</td>
            <td>{{$value->documento  }}</td>
            <td>{{$value->f_nacimiento  }}</td>
            <td>{{$value->barrio  }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>