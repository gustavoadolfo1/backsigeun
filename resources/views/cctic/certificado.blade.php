<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CERTIFICADO</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <style>
        @page {
            margin: 0;
            /* height: 350mm; */
            /* background-color: blue; */
        }

        .page-container {
            width: 67%;
            margin: 85mm 30mm 0mm auto;
            /* background-color: red; */
        }

        .page-unidades {
            width: 50%;
            margin: 70mm 30mm 25mm auto;
        }

        p {
            font-size: 1.1rem;
        }

        .mt-table {
            margin-top: 5.5rem;
        }
    </style>
</head>

<body>
    @include('../cctic/pdf/page-background', [ 'image' => $data->backgroundImage ])

    <div class="page-container">
        <h3 class="my-1">Otorgado a: <span class="h5"> <strong> {{ $data->cPersPaterno }} {{ $data->cPersMaterno }}, {{ $data->cPersNombre }}</strong></span></h3>
        <p>Por haber culminado satisfactoriamente el módulo de <strong class="text-uppercase">{{ $data->cPerfilProfesional }}</strong> con {{$data->iTotalHorasCertificado}}
            horas pedagógicas realizado el 20 de enero al 02 de junio de 2019, cuyo contenido se
            expresa al dorso.</p>
        <p class="mb-5 text-right">Moquegua, {{date('d')}} de {{ Date::now()->format('F') }} de {{date('Y')}}</p>
        <!-- <p class="mb-5 text-right">Moquegua, Viernes 22 de Mayo del 2020</p> -->
        <table class="w-100 pt-5 mt-table">
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

    <!-- @include('../cctic/pdf/page-break') -->
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
                    <td>{{ $item->nNota }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
