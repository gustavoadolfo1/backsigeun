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
    .footer{
        position: fixed;
        bottom: 3rem;
        left: 0px;
        right: 0px;
        height: 50px;
    }

    .font {
        font-family: Helvetica;
    }
</style>

<body class="font">
        <img src="./img/logo.png" id="img-logo" style="height:40px; position: relative; float: left; margin-left: 20px;">
        <p style="font-size: 20px; text-align: center;" class=" font-weight-bold">UNIVERSIDAD NACIONAL DE MOQUEGUA<</p>
        <p align="center" style="font-size: 13px" class=" font-weight-bold">  CENTRO DE CAPACITACIÓN EN TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIÓN - CCTIC</p>

        <p align="center" class=""><strong>
                Lista de Preinscritos - {{Carbon\Carbon::now()->formatLocalized('%Y')}}
        </strong></p>


        <table border="0" >
                <thead style="vertical-align: middle;">
                    <tr  style="font-size: 12px; ">
                        <th width="100px" ><strong>CURSO:</strong></th>
                        <td width="350px">{{$curso['cCursoNombre']}}</td>
                        <th width="100px"><strong></strong></th>
                        <td width="350px"></td>

                    </tr>
                    <tr  style="font-size: 12px; ">
                        <th  style=""><strong>MODULO:</strong></th>
                        <td width="350px">{{$curso['cModuloNombre']}}</td>
                        <th style="" width="100px" ><strong>INICIO:</strong></th>
                        <td width="350px">
                             {{
                             Carbon\Carbon::parse($curso['dPublicacionFechaInicio'])->formatLocalized('%d %B %Y')
                             }}
                        </td>
                    </tr>

                    <tr style="font-size: 12px; ">
                        <th width="100px"><strong>HORARIO:</strong></th>
                        <td colspan="2">
                            @foreach ($filtro['detalles'] as $item)
                                <span class="normal-text">
                                    {{ $item['cDiaSemDsc'] }}:
                                    {{ Carbon\Carbon::parse($item['tHoraInicio'])->format('H:i:s A')}} a
                                    {{ Carbon\Carbon::parse($item['tHoraFin'])->format('H:i:s A') }}
                                </span>

                            @endforeach
                        </td>

                    </tr>
                </thead>

        </table>
        <br>
        <div style="margin-bottom: 1rem;">
            <table border="1" style="width:100%;">
                <thead>
                    <tr style="background-color: #F9F9F9;font-size: 13px; text-align: center; ">
                        <td ><strong>&nbsp;N°&nbsp;</strong></td>
                        <td ><strong>&nbsp;DNI&nbsp;</strong></td>
                        <td  width="220px"><strong>APELLIDOS Y NOMBRES</strong></td>
                        <td ><strong>CELULAR</strong></td>
                        <td ><strong>Telefono</strong></td>
                        <td ><strong>EMAIL</strong></td>
                        <td ><strong>OBSERVACIONES</strong></td>
                    </tr>

                </thead>
                <tbody>
                     @foreach ($preinscritos as $i => $preinscrito)
                     <tr style="font-size: 10px; text-align: center;">
                        <td>{{$i}}</td>
                        <td>{{$preinscrito['cPersDocumento']}}</td>
                        <td class="text-left">{{$preinscrito['cPersPaterno']}} {{$preinscrito['cPersMaterno']}} {{$preinscrito['cPersNombre']}}</td>
                        <td>{{$preinscrito['cPreinscripcionCelular']}}</td>
                        <td>{{$preinscrito['cPreinscripcionTelefoto']}}</td>
                        <td>{{$preinscrito['cPreinscripcionEmail']}}</td>
                        <td style="padding: 1rem"> </td>
                    </tr>
                     @endforeach

                </tbody>
            </table>
        </div>


        <div class="">
            <div style="width: 100%">
                <p class="font-weight-bold float-right">
                    <?php
                    setlocale(LC_TIME, 'es');
                    echo  'Moquegua, '.Carbon\Carbon::now()->formatLocalized('%d').' de '.Carbon\Carbon::now()->formatLocalized(('%B')).' del '.Carbon\Carbon::now()->formatLocalized('%Y');
                    ?>
                </p>
            </div>
        </div>



    <script src="assets/bootstrap/js/bootstrap.min.js"></script>


</body>

</html>
