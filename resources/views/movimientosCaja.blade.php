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
    <table> 
        <tr>
            <th>Concepto</th>
            <th>Caja</th>
            <th>Monto</th>
            <th>Usuario</th>
        </tr>
        @foreach($datos as $value)
        <tr>
            <td> {{$value->concepto_caja  }}</td>
            <td>{{$value->caja_descripcion  }}</td>
            <td>{{$value->monto  }}</td>
            <td>{{$value->nombre_usuario  }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>