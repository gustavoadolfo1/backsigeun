<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        *{
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
        table{
            border-collapse: collapse;
            width:100%;
            margin-bottom:20px;
        }
        td{
            padding: 3px 6px;
        }
        th{
            padding: 3px 6px;
        }
        .mt-{

        }
        .foto{
            display:block;
            position:absolute;
            margin-top:0px auto;
            top:0;
            width:2.7cm;
            height:3.5cm;
            border:1px solid black;
        }
        .foto img{
            display:block;
            position:relative;
            margin-left:0.05cm;
            width:2.6cm;
            height:3.4cm;
            margin-top:0.05cm;
        }
        .center{
            text-align
            : center;
        }
        .right{
            text-align: right;
        }
        .nb{
            border:none !important;
        }
        .b{
            border:1px solid black;
        }
        .bt{
            border-top:1px solid black;
        }
        .br{
            border-right:1px solid black;
        }
        .bb{
            border-bottom:1px solid black;
        }
        .bbl{
            border-bottom:1px solid black;
        }
        .bl{
            border-left:1px solid black;
        }
        .bg-color{
            background-color:#c3c3c3;
        }
        .box{
            display:block;
            position:relative;
            width:100%;
            width:100%;
            text-align:center;
            border:2px solid black;
        }
        .box-head{
            text-align:center;
            padding:6px 2px;
        }
        .box-body{
            text-align:center;
            padding:6px 2px;
            border-top:2px solid black;
        }
        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 50px; 
            text-align: center;

        }
    </style>
</head>
<body>
    <table style="margin:0px">
        <thead>
            <tr>
                <td align="center" colspan="8">
                    <img src="./img/escudo.jpg" style="display:block;position:absolute;left:10px;top:10px;height:50px;">
                    <h1 style="font-size:1.5rem"> UNIVERSIDAD NACIONAL DE MOQUEGUA<br> COMISIÓN DE ADMISIÓN</h1>
                </td>
            </tr>
            <tr>
                <th colspan="8">
                    <h4> CONTROL DE ASISTENCIA DE POSTULANTES POR AULA</h4>
                </th>
            </tr>
            <tr>
                <th class="b bg-color" colspan="2">AULA</th>
                <td class="b" colspan="6"> {{ $data[0]->cAulasDesc }} </td>
            </tr>
            <tr>
                <th class="b bg-color" colspan="2">UBICACIÓN</th>
                <td class="b" colspan="2"> {{ $data[0]->iAulaPiso }} piso</td>
                <th class="b bg-color">NRO. POSTULANTES</th>
                <td class="b">{{ $data[0]->iTotalProcesados }}</td>
                <th class="b bg-color">SEDE DE EXAMEN</th>
                <td class="b">{{ $data[0]->cFilialExamen }}</td>
            </tr>
            <tr>
                <td colspan="8">&nbsp;</td>
            </tr>
            <tr>
                <th class="b bg-color" align="center">Nro. Orden</th>
                <th class="b bg-color" align="center">DNI</th>
                <th class="b bg-color" align="center">CÓDIGO</th>
                <th class="b bg-color" align="center" colspan="3">POSTULANTE</th>
                <th class="b bg-color" align="center" colspan="2">CARRERA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key=>$d)
            <tr>
                <td class="b" width="20" align="center">{{ $d->iOrdenSalon }}</td>
                <td class="b" width="40" align="center">

                    {{$d->cPersDocumento}}
                    
                </td>
                <td class="b" width="60" align="center">{{ $d->cCodPostulante }}</td>   
                <td class="b" width="180" colspan="3">
                    <span> <strong>{{ $d->cPersPaterno }} {{ $d->cPersMaterno }}</strong> , {{ $d->cPersNombre }}</span> 
                </td>    
                <td class="b" colspan="2" align="left">
                   {{ $d->cCarreraDsc }} - {{ $d->cFilialCarrera}}
                </td> 
            </tr>
            @endforeach
            
        </tbody>
   </table>
</body>
</html>
