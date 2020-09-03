<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CERTIFICADO SUFICIENCIA</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <style>
        @page {
            margin: 0;
        }

        body {
            width: 100%;
        }

        .page-container {
            width: 67%;
            margin: 86mm 30mm 25mm auto;
        }

        .page-unidades {
            width: 50%;
            margin: 70mm 30mm 25mm auto;
        }

        .mt-16 {
            margin-top: 35%;
        }

        p {
            font-size: 1.1rem
        }

        table {
            margin-top: 5.5rem;
        }
    </style>
</head>

<body>
    @include('../cctic/pdf/page-background', [ 'image' => $data->backgroundImage ])

    <div class="page-container">
        <h3 class="my-1">Otorgado a: <span class="interesado h4"> <strong> {{ $data->cPersPaterno }} {{ $data->cPersMaterno }}, {{ $data->cPersNombre }}</strong></span></h3>
        <p>Por haber APROBADO el examen de suficiencia en <strong class="text-uppercase">{{ $data->cPerfilProfesional }}</strong>
            realizado el de febrero del presente año, según Reglamento aprobado con RESOLUCIÓN
            PRESIDENCIAL N° 1168-2015-UNAM, cuyo contenido se expresa al dorso.
        </p>
        <p class="Mb-5 text-right">Moquegua, {{date('d')}} de {{ Date::now()->format('F') }} de {{date('Y')}}</p>

        <table class="mt-5 pt-4 w-100">
            <tbody>
                <tr>
                    <td>
                        <p class="small text-center text-uppercase">
                            <span>__________________________________________</span><br>
                            <span class="font-weight-bold">{{$data->NombreDirectorCCTIC}}</span> <br>
                            <span>{{ $data->CargoDirectorCCTIC }}</span> <br>
                            <span>UNAM</span>
                        </p>
                    </td>
                    <td>
                        <p class="small text-center text-uppercase">
                            <span>___________________________________________</span><br>
                            <span class="font-weight-bold">{{ $data->NombreViceCCTIC }}</span> <br>
                            <span>{{ $data->CargoViceCCTIC }}</span> <br>
                            <span>UNAM</span>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

<body>
    <div class="page-unidades">
        <table class="table table-sm table-bordered">
            <tbody>
                <tr>
                    <td class="font-weight-bold">UNIDAD</td>
                    <td class="font-weight-bold">NOTA</td>
                </tr>
                @foreach ($data->unidades as $item)
                <tr>
                    <td>{{ $item->cUnidadDsc }}</td>
                    <td>{{ $item->Nota }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
