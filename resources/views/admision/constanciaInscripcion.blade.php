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
            border:2px solid black;
        }
        .bt{
            border-top:2px solid black;
        }
        .br{
            border-right:2px solid black;
        }
        .bb{
            border-bottom:2px solid black;
        }
        .bbl{
            border-bottom:1px solid black;
        }
        .bl{
            border-left:2px solid black;
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
        <tr>
            <td align="center">
                <img src="./img/escudo.jpg" id="img-logo" style="height:40px;">
                <h5 style="margin-top:-10px"> UNIVERSIDAD NACIONAL DE MOQUEGUA<br> CONCURSO DE ADMISION</h5>
                <h5>CONSTANCIA DE PRE-INSCRIPCIÓN</h5>
            </td>
        </tr>
   </table>

   <table border="1">
    <tbody>
        <tr>
            <td>DNI:</td>
            <td>{{ $data->cPersDocumento }}</td>
        </tr>
        <tr>
            <td>APELLIDOS:</td>
            <td> {{ trim($data->cPersPaterno) }} {{ trim($data->cPersMaterno) }}</td>
        </tr>
        <tr>
            <td>NOMBRES:</td>
            <td>{{ trim($data->cPersNombre) }}</td>
        </tr>
        <tr>
            <td>CARRERA PROFESIONAL:</td>
            <td>{{ $data->cCarrera . ' - ' . $data->cFilialCarrera }}</td>
        </tr>
        <tr>
            <td>MODALIDAD:</td>
            <td>{{ $data->cModalidad }}</td>
        </tr>
        <tr>
            <td>COLEGIO:</td>
            <td>{{ $data->cColegio }}</td>
        </tr>
        <tr>
            <td>TIPO IE:</td>
            <td>{{ $data->cGestionDsc }}</td>
        </tr>
        <tr>
            <td>SEDE DEL EXAMEN:</td>
            <td>{{ $data->cFilial }}</td>
        </tr>
        <tr>
            <td>MONTO A CANCELAR:</td>
            <td><b>{{ $data->monto_a_cancelar }}</b></td>
        </tr>
    </tbody>
</table>
<p>Fecha de registro: {{ $data->dInscripRegistro }}</p>
<i>Sólo referencial, imprima su ficha de inscripción luego de hacer el pago.</i>
<footer>
    Universidad Nacional de Moquegua
</footer>
</body>
</html>