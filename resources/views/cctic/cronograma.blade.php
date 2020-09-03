<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronograma</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
{{--    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">--}}
    <style>

        @page {
            margin: 1.5rem;
            /* height: 350mm; */
        }
        .title {
            font-size: 3rem;
        }

        .sub-title {
            font-size: 1.3rem;
        }

        img {

        }

        table {
            text-align: center;
        }

        table td, th {
            border: 1px solid rgb(0, 31, 96);
        }

        .header-left {
            background: rgb(0, 51, 152);
            color: #ffffff;
            text-transform: uppercase;
        }

        .header-top {

            background-color: rgb(0, 31, 96);
            color: #ffffff;
            text-transform: uppercase;
        }

        .table-botoom {
            background-color: rgb(1, 255, 205);
            padding: 0 !important;
        }

        .small-text {
            font-size: 0.7rem;
        }

        .medium-text {
            font-size: 1.3rem;
        }

        .big-text {
            font-size: 1.5rem;
        }

        .table td {
            vertical-align: middle !important;
            text-transform: uppercase;
        }

        .without-border  {
            border: none !important;
        }

        .border-div {
            border: 1px solid rgb(0, 31, 96);
        }

        .main-bg {
            background: #ffffff;
        }

        .first-column {
            width: 70%;
        }


    </style>
</head>

<body style="max-height: 100vh;">
    <div class="text-center">
        <img src="./img/logo.png" style="height:45px" class=""> <br>
        <img src="./img/logo-cctic.jpg"  style="height:30px">
        <p class="m-0">Año de la Universalización de la Salud</p>
    </div>
    <div class="">
        <h3 class="text-center title m-0">
            Cronograma Academico  {{date('Y')}}
        </h3>
        <p class="text-center sub-title text-uppercase" >
            Módulo: {{$data->cCursoNombre}} {{$data->cModuloNombre}} - {{$data->cGrupoDsc}}
        </p>
    </div>

    <table class="w-100 table table-sm" style="border-top: none">
        <tbody>
            <tr class="without-border">
                <td colspan="3" class="without-border text-left text-uppercase" >
                    Horario
                </td>
                <td colspan="2" class="without-border text-right text-uppercase">
                    {{$data->docente}}
                </td>
            </tr>
            <tr>
                <th  rowspan="3" class="header-left align-middle big-text " width="140px">
                    Grupo <br> {{ trim($data->cGrupoDsc,"Grupo")}}
                </th>
                <th class="header-top small-text ">Mes</th>
                <th class="header-top small-text ">Fecha de Inicio - Termino</th>
                <th class="header-top small-text ">Cursos</th>
                <th class="header-top small-text ">Fecha de examen</th>
            </tr>

            <tr>
                <td class="big-text  font-weight-bold">
                    1er Mes
                </td>
                <td class="medium-text font-weight-bold">
                    28 de Enero al 25 de Febrero
                </td>
                <td class="small-text">
                    Microsoft word 10 <br>
                    Herramientas de internet <br>
                    Microsoft word 2016 <br>
                    (Contenido de acuerdo al silabo) <br>
                </td>
                <td class="big-text  font-weight-bold ">
                    25 de febrero
                </td>
            </tr>

            <tr>
                <td class="big-text  font-weight-bold">
                    2do Mes
                </td>
                <td class="medium-text font-weight-bold">
                    28 de Enero al 25 de Febrero
                </td>
                <td class="small-text">
                    Microsoft word 10 <br>
                    Herramientas de internet <br>
                    Microsoft word 2016 <br>
                    (Contenido de acuerdo al silabo) <br>
                </td>
                <td class="big-text  font-weight-bold">
                    25 de febrero
                </td>
            </tr>

        </tbody>
    </table>

    <table class="table table-sm ">
        <tbody>
            <tr>
                <th rowspan="2" width="140px" class="header-left align-middle">Mensualidad</th>
                <th class="header-top" width="320px">ultima fecha</th>
                <td class="header-top text-left" rowspan="2">caso contrario no podran dar el ultimo examen, si no han pagado el derechode enseñanza</td>
            </tr>
            <tr>
                <td  width="320px">
                    16/03/2020
                </td>
            </tr>
            <tr>
                <td rowspan="" colspan="3" class="table-botoom text-left" >
                   <table class="w-100">
                      <body>
                        <tr>
                            <td style="border: none; position: relative" class="align-top first-column" >
                               <p style="position: absolute; top: 10px" class="medium-text">
                                   * Comunicarse al 923233555
                               </p>
                            </td>
                            <td style="border: none;">
                                <div class="border-div header-left medium-text  text-center">
                                    INICIO
                                </div>
                                <div class="border-div main-bg  medium-text font-weight-bold text-center">
                                  <?php
                                    setlocale(LC_TIME, 'es');
                                    echo  Carbon\Carbon::parse($data->dFechaIni)->formatLocalized('%d').' DE '
                                        .Carbon\Carbon::parse($data->dFechaIni)->formatLocalized(('%B')).' DEL '
                                        .Carbon\Carbon::parse($data->dFechaIni)->formatLocalized('%Y');
                                    ?>
                                </div>
                                <div class="border-div header-left  medium-text text-center">
                                    FIN
                                </div>
                                <div class="border-div main-bg medium-text  font-weight-bold text-center">
                                    <?php
                                    setlocale(LC_TIME, 'es');
                                    echo  Carbon\Carbon::parse($data->dFechaFin)->formatLocalized('%d').' DE '
                                        .Carbon\Carbon::parse($data->dFechaFin)->formatLocalized(('%B')).' DEL '
                                        .Carbon\Carbon::parse($data->dFechaFin)->formatLocalized('%Y');
                                    ?>
                                </div>
                            </td>
                        </tr>


                      </body>
                   </table>
                </td>

            </tr>
        </tbody>
    </table>
</body>

</html>
