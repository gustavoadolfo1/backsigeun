<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h1>Reporte Matriculados</h1>
                    <h3>{{  $estudiantes[0]->cCarreraDsc }}</h3>

                    <div class="card-body">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Código</th>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>Plan</th>
                                    <th>Filial Sigla</th>
                                    <th>Semestre Ingreso</th>
                                    <th>Semestre último</th>
                                    <th>Carrera</th>
                                    <th>Total créditos</th>
                                    <th>PPS 2019-1</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                @foreach ( $estudiantes as $estudiante )
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $estudiante->fecha }}</td>
                                    <td>{{ $estudiante->cEstudCodUniv }}</td>
                                    <td>{{ $estudiante->cPersDocumento }}</td>
                                    <td>{{ $estudiante->nombre }}</td>
                                    <td>{{ $estudiante->cCurricAnio }}</td>
                                    <td>{{ $estudiante->cFilSigla }}</td>
                                    <td>{{ $estudiante->cEstudSemeIngre }}</td>
                                    <td>{{ $estudiante->cEstudSemeUlti }}</td>
                                    <td>{{ $estudiante->cCarreraDsc }}</td>
                                    <td>{{ $estudiante->total_creditos }}</td>
                                    <td>{{ $estudiante->pps_20191 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


</body>
</html>



