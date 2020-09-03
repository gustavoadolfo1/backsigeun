<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

        body {
            font-size: 11px; font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;
        }

        .table, .table th, .table td {
            border: solid 1px;
            border-collapse: collapse;
            border-color: #a7a7a7;
        }

        .table th {
            background-color: #c3c3c3;
            text-align: center;
            padding: 4px 5px;
        }
        .table td {
            padding: 4px 5px; 
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .col-4 {
            width: 33%;
        }

    </style>
</head>
<body>
    <table>
        <tr>
            <td class="text-left col-4">Universidad Nacional de Moquegua</td>
            <td class="text-center col-4">{{ date('d-m-Y H:i:s') }}</td>
            <td class="text-right col-4">Usuario:</td>
        </tr>
        <tr>
            <td colspan="3" class="text-center"><br><h3>ESTUDIANTES CON {{ $params['numMatricula'] }} MATRÍCULAS DESAPROBADAS</h3></td>
        </tr>
        <tr>
            <td colspan="3">Semestre: {{ $params['semestre'] }}</td>
        </tr>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th rowspan="2">Escuela Profesional</th>
                <th rowspan="2">Sede</th>
                <th rowspan="2">Total<br>estudiantes</th>
                <th colspan="{{ count($ciclos) }}">Ciclos referenciales</th>
            </tr>
            <tr>
                @foreach ($ciclos as $ciclo)
                    <th>{{ $ciclo['romano'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $escuela)
                <tr>
                    <td>{{ $escuela->cCarrera }}</td>
                    <td>{{ $escuela->cFilial }}</td>
                    <td class="text-center">{{ $escuela->total }}</td>
                    @foreach ($ciclos as $ciclo)
                        @php $numero = $ciclo['numero'] @endphp
                        <td class="text-center">{{ $escuela->$numero ?? 0 }}</td>
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td>TODAS LAS ESCUELAS</td>
                <td></td>
                <td class="text-center">{{ $total }}</td>
                <td colspan="{{ count($ciclos) }}"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
