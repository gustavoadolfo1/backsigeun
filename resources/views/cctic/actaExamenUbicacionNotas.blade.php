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
            width: 100%;
            margin: 10mm 25mm 25mm 25mm;
            /* background-color: red; */
        }

        .page-unidades {
            width: 50%;
            margin: 70mm 30mm 25mm 25;
        }

        p {
            font-size: 12px;
        }

        .logo-unam {
            width: 80px;
        }

        img {
            margin-right: 1.3rem;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .page-content p {
            /* margin: 1rem 0; */
            margin-top: 1rem;
            text-align: justify;
        }

        /* .table-custom td {
            font-size: 12px !important;
        } */
    </style>
</head>

<body>
    <div class="page-container">
        <table>
            <tbody>
                <tr>
                    <td class="logo-unam">
                        <img src="./img/logo.png" id="img-logo">
                    </td>
                    <td class="text-center font-weight-bold ">
                        <small>UNIVERSIDAD NACIONAL DE MOQUEGUA</small> <br>
                        <p class="px-2">CENTRO DE CAPACITACIÓN EN TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIÓN - {{ $data->sede}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <h5 class="text-uppercase text-center mt-4">ACTA DE EXAMEN DE UBICACION</h5>
        <div class="page-content">
            <p>
                Siendo las 15 horas del dia 05 de febrero de 2020, estando presente el aspirante
                <span class="font-weight-bold text-uppercase">{{ $data->cPersPaterno }} {{ $data-> cPersMaterno }} {{ $data->cPersNombre}}</span>,
                el docente evaluador (Examen teórico y practico): <span class="font-weight-bold text-uppercase">{{$data->Docente}}</span>
                y el Director: <span class="text-uppercase font-weight-bold">{{ $data->NombreDirectorCCTIC }}</span> en el laboratorio del Centro de Capacitación CCTIC - {{ $data->sede }},
                para la aplicación del examén de UBICACION del curso de
                <span class="text-uppercase font-weight-bold">{{ $data->cPerfilProfesional}}</span>, comtemplado en los Art 24 y 25 del Reglamento del Centro de Capacitación
                en Tecnologias de la Información y Comunicación - {{ $data->sede }} de esta institución, solicitado por el aspirante y habiendo presentado mediante un formulario unico de tramite (FUT N°0767).
            </p>
            <p>
                Se procedió a evaluar los conocimientos del alumno mediante el examen de suficiencia teórico practico, sobre la totalidad de los objetivos y contenidos del curso en mención.
            </p>
            <p>
                Habiendo culminado el examen teorico y practico se procede a evaluar y colocar las notas respectivas:
            </p>
            <table class="table-custom table table-sm table-bordered w-50">
                <tbody>
                    <tr>
                        <td style="font-size: 12px;" class="font-weight-bold">CRITERIO EVALUADO</td>
                        <td style="font-size: 12px;" class="font-weight-bold">NOTA</td>
                    </tr>
                    @foreach ($data->notas as $item)
                    <tr>
                        <td style="font-size: 12px;">{{ $item->cUnidadDsc }}</td>
                        <td style="font-size: 12px;">{{ $item->nNota }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p>En señal de conformidad, firma el jurado evaluador y el aspirante</p>
        </div>
        <table class="w-100 pt-5 mt-2 mt-table">
            <tbody>
                <tr>
                    <td>
                        <p class="small text-center text-uppercase mt-5 mb-0">
                            ____________________________________</p>
                        <p class="small text-center text-uppercase m-0 font-weight-bold">{{ $data->Docente}}</p>
                        <p class="small text-center text-uppercase m-0">DNI N° {{ $data->DNI_Docente }}</p>
                        <p class="small text-center text-uppercase m-0">DOCENTE EVALUADOR</p>
                    </td>
                    <td>
                        <p class="small text-center text-uppercase mt-5 mb-0">
                            ____________________________________</p>
                        <p class="small text-center text-uppercase m-0 font-weight-bold">{{ $data->cPersPaterno }} {{ $data-> cPersMaterno }} {{ $data->cPersNombre}}</p>
                        <p class="small text-center text-uppercase m-0">DNI N° {{ $data->cPersDocumento }}</p>
                        <p class="small text-center text-uppercase m-0">ASPIRANTE</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="small text-center text-uppercase mt-5 pt-4 mb-0">
                            ____________________________________</p>
                        <p class="small text-center text-uppercase m-0 font-weight-bold">{{ $data->NombreDirectorCCTIC }}</p>
                        <p class="small text-center text-uppercase m-0">{{ $data->CargoDirectorCCTIC }}</p>
                        <p class="small text-center text-uppercase m-0">UNAM</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>


</html>
