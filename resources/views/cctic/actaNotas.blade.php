<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Notas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
{{--    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">--}}

</head>

<style>
    .font {
        font-family: Helvetica;
    }

    .table-custom {
        border-collapse: collapse;
    }
    .table-custom td, .table-custom th{
        border: 1px solid #2B2E4A;
        /*background-color: #0000ff66;*/
    }
    .rowspan {
        border-left-width: 10px;
    }

</style>
<style>
</style>

<body class="font">
     <img src="./img/logo.png" id="img-logo" style="height:40px; position: relative; float: left; margin-left: 20px;">
    <p style="font-size: 20px; text-align: center;" class=" font-weight-bold">UNIVERSIDAD NACIONAL DE MOQUEGUA<</p>
    <p align="center" style="font-size: 13px" class=" font-weight-bold">  CENTRO DE CAPACITACIÓN EN TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIÓN - CCTIC</p>

    <p align="center" class=""><strong>ACTA DE NOTAS FINALES - {{Carbon\Carbon::now()->formatLocalized('%Y')}}</strong></p>

    <table border="0" style="font-family: Calibri Light">
        <thead style="vertical-align: middle;">
            <tr  style="font-size: 12px; ">
                <th width="100px"><strong>CARRERA</strong></th>
                <td width="350px"></td>
                <th ><strong></strong></th>
                <td width="100px"></td>

            </tr>
            <tr  style="font-size: 12px;">
                <th width="100px"><strong>MODULO:</strong></th>
                <td width="350px">{{$data[0]->cModuloNombre}}</td>
                <th width="100px"><strong>INICIO:</strong></th>
                <td width="100px">{{$data[0]->dFechaFin}}</td>

            </tr>
            <tr  style="font-size: 12px; ">
                <th style="" ><strong>CURSO:</strong></th>
                <td width="350px">{{$data[0]->cUnidadDsc}}</td>
                <th style=""><strong>FIN:</strong></th>
                <td width="100px">{{$data[0]->dFechaIni}}</td>

            </tr>
            <tr style="font-size: 12px; ">
                <th ><strong>DOCENTE:</strong></th>
                <td width="350px">Prof. {{$data[0]->Docente}}</td>
                <th style=""><strong></strong></th>
                <td width="100px"></td>

            </tr>
        </thead>
    </table>
    <br>
        <div style="margin-bottom: 1rem;" >
            <table class="w-100 table-custom" border="1" cellspacing="0" cellpadding="3">
                <thead>
                    <tr style="background-color: #F9F9F9;font-size: 13px; text-align: center; ">
                        <td rowspan="2"><strong>&nbsp;N°&nbsp;</strong></td>
                        <td rowspan="2"><strong>&nbsp;DNI&nbsp;</strong></td>
                        <td rowspan="2" width="220px"><strong>APELLIDOS Y NOMBRES</strong></td>
                        <td rowspan="2"><strong>&nbsp;% ASIST.&nbsp;</strong></td>
                        <td colspan="2" rowspan="1"><strong >&nbsp;PROMEDIO FINAL&nbsp;</strong></td>
                        <td rowspan="2"><strong>APROB./DESAP.</strong></td>
                    </tr>
                    <tr style="background-color: #F9F9F9;font-size: 13px; text-align: center; ">
                            <td rowspan="1" colspan="1"><strong>NUMEROS</strong></td>
                            <td rowspan="1" colspan="1" ><strong>LETRAS</strong></td>
                    </tr>
                </thead>
                <tbody>



                    @foreach ($data as $i => $estudiante)
                        <tr style="font-size: 10px; text-align: center;">
                        <td>{{$i}}</td>
                        <td>{{$estudiante->cPersDocumento}}</td>
                        <td>{{$estudiante->cPersPaterno}} {{$estudiante->cPersMaterno}} {{$estudiante->cPersNombre}}</td>
                        <td>{{$estudiante->Porcentaje_Asistencia}}%</td>
                        <td>{{$estudiante->nNota}}</td>
                        <td>{{$estudiante->Numero_Letras}}</td>
                        <td>{{$estudiante->Estatus}}</td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>


        <div class="">
            <p style="font-size: 12px">
                Nº de matriculados: {{$data[0]->Numero_Inscritos}},
                Nº de aprobados: {{$data[0]->Numero_Aprobados}},
                Nº de desaprobados: {{$data[0]->Numero_Desaprobados}}
            </p>
            <div style="width: 100%">
                <p class="font-weight-bold float-right">
                  <?php
                    setlocale(LC_TIME, 'es');
                    echo  'Moquegua, '.Carbon\Carbon::now()->formatLocalized('%d').' de '.Carbon\Carbon::now()->formatLocalized(('%B')).' del '.Carbon\Carbon::now()->formatLocalized('%Y');
                  ?>
                </p>
            </div>

            <br><br><br><br>
    </div>

    <table border="0" class="w-100">
        <tbody>
            <tr >
                <td>
                    <p align="center" style="font-size: 13px">          ___________________________________
                        <br>
                       <span class="text-center">
                            Prof. {{$data[0]->Docente}} <br>
                           DOCENTE
                       </span>

                    </p>
                </td>
                <td>
                    <p align="center" style="font-size: 13px">          ___________________________________ <br>
                       <span class="text-center">
                           {{$data[0]->NombreViceCCTIC}} <br>
                           {{$data[0]->CargoViceCCTIC}}
                       </span>

                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 2rem;">
                    <p align="center" style="font-size: 13px">          _____________________________________ <br>
                       <span class="text-center">
                           {{$data[0]->NombreDirectorCCTIC}}<br>
                            {{$data[0]->CargoDirectorCCTIC}}</span>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>


    <script src="assets/bootstrap/js/bootstrap.min.js"></script>


</body>

</html>
