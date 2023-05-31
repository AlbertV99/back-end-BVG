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
            <th>Fecha</th>
            <th>Concepto</th>
            <th>Caja</th>
            <th>Monto</th>
            <th>Usuario</th>
        </tr>
        @php
            $prevFecha = null;
        @endphp
        @foreach($datos as $value)
            @if($prevFecha !== $value->fecha_operacion)
                <tr>
                    <td rowspan="{{ $datos->where('fecha_operacion', $value->fecha_operacion)->count() }}">
                        {{ $value->fecha_operacion }}
                    </td>
                    <td>{{$value->concepto_caja}}</td>
                    <td>{{$value->caja_descripcion}}</td>
                    <td>{{$value->monto}}</td>
                    <td>{{$value->nombre_usuario}}</td>
                </tr>
            @else
                <tr>
                    <td>{{$value->concepto_caja}}</td>
                    <td>{{$value->caja_descripcion}}</td>
                    <td>{{$value->monto}}</td>
                    <td>{{$value->nombre_usuario}}</td>
                </tr>
            @endif
            @php
                $prevFecha = $value->fecha_operacion;
            @endphp
        @endforeach
    </table>
</body>
</html>