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
    <h3>Año: {{ $anho }}</h3>
    <table>
        <tr>
            <th>Mes</th>
            <th>Tipo</th>
            <th>Total</th>
            <th>Cantidad</th>
        </tr>
        @php
            $prevFecha = null;
        @endphp
        @foreach($datos as $value)
            @if($prevFecha !== $value->mes)
                <tr>
                    <td rowspan="{{ $datos->where('mes', $value->mes)->count() }}">
                        {{ $value->mes }}
                    </td>
                    <td>{{$value->tipo}}</td>
                    <td>{{$value->total}}</td>
                    <td>{{$value->cantidad}}</td>
                </tr>
            @else
                <tr>
                    <td>{{$value->tipo}}</td>
                    <td>{{$value->total}}</td>
                    <td>{{$value->cantidad}}</td>
                </tr>
            @endif
            @php
                $prevFecha = $value->mes;
            @endphp
        @endforeach
    </table>
</body>

</html>