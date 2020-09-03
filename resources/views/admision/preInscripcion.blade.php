<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ficha Estudiante</title>
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
            <td rowspan="2"  align="top" width="120" style="padding:0px;margin:0px">
                <div class="foto">
                    @if ($data->cPersFotografia)
                        <img src="./storage/adm/fotos/{{ $data->cPersDocumento }}.jpg" alt="">
                    @endif
                </div><br>
            </td>
            <td align="center">
                <img src="./img/escudo.jpg" id="img-logo" style="height:60px;">
                <h5 style="margin-top:-10px"> UNIVERSIDAD NACIONAL DE MOQUEGUA<br> CONCURSO DE ADMISION</h5>
                <h5>SOLICITUD DE PREINSCRIPCIÓN DEL POSTULANTE</h5>
            </td>
            <td  width="100" align="center">
                <div class="box">
                    <div class="box-head">
                        N° DE EXPEDIENTE
                    </div>
                    <div class="box-body">
                    {{ $data->cNumExpediente }}
                    </div>
                </div>
            </td>
        </tr>
   </table>

   <h2>Sr. PRESIDENTE DE LA COMISIÓN ORGANIZADORA - UNAM</h2>
   <br>
   <strong>
    Yo: 
   </strong>
    <table border="1">
        <tr>
            <th class='bg-color' width="110">NOMBRES</th>
            <td colspan="3">{{ trim($data->cPersNombre) }} {{ trim($data->cPersPaterno) }} {{ trim($data->cPersMaterno) }}</td>
        </tr>
        <tr>
            <th class='bg-color'>DNI</th>
            <td>{{ $data->cPersDocumento }}</td>
            <th class='bg-color'>CÓDIGO</th>
            <td >{{ $data->cCodPostulante }}</td>
        </tr>
        <tr>
            <th class='bg-color'>F.NACIMIENTO</th>
            <td>{{ $data->dNacimiento }}</td>
            <th class='bg-color'>SEXO</th>
            <td>{{ $data->cSexo }}</td>
        </tr>
        <tr>
            <th class='bg-color'>E-MAIL</th>
            <td>{{ $data->cEmail }}</td>
            <th class='bg-color'>TELÉFONO</th>
            <td>{{ $data->cTelefono }}</td>
        </tr>
        <tr>
            <th class='bg-color'>DIRECCIÓN</th>
            <td colspan="3">{{ $data->cDireccion }}</td>
        </tr>
    </table>
    <strong>
    Procedente de la Institución Educativa:
    </strong>
    <table border="1">
        <tbody>
            <tr>
                <th class='bg-color' width="110">COLEGIO</th>
                <td>{{ $data->cColegio }}</td>
                <th class='bg-color'>TIPO</th>
                <td >{{ $data->cGestionDsc }}</td>
            </tr>
            <tr>
                <th class='bg-color' width="90">EGRESO</th>
                <td>{{ $data->iPreEgreso }}</td>
                <th class='bg-color'>PREPARACIÓN</th>
                <td>{{ $data->cModoPreparacion }}</td>
            </tr>
        </tbody>
    </table>
    <strong>
    Postulante a la Carrera Profesional:
    </strong>
     <table border="1" >
        <tbody>
            <tr>
                <th class='bg-color' width="110">CARRERA</th>
                <td>{{ $data->cCarrera . ' - ' . $data->cFilialCarrera }}</td>
                <th class='bg-color'  width="70" >SEDE EXAMEN</th>
                <td>{{ $data->cFilial }}</td>
            </tr>
            <tr>
                <th class='bg-color'>MODALIDAD</th>
                <td colspan="3">{{ $data->cModalidad }}</td>
            </tr>
        </tbody>
    </table>

    <strong>Monto cancelado</strong>
    <table border="1">
        <thead>
            <tr>
                <th class='bg-color' colspan="4">RECIBO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class='bg-color' width="110">MONTO A CANCELAR</th>
                <td>
                    {{ $data->monto_a_cancelar ?? '' }}
                
                </td>   
                <th class='bg-color' width="110">RECIBO 01 (DERECHO A EXAMEN DE ADMISIÓN)</th>
                <td>
                    {{ $data->deudas[0]->cRecibo ?? '' }}
                
                </td>   
                <th class='bg-color' width="80">IMPORTE S/. :</th>
                <td>
                    {{ $data->deudas[0]->nPagado ?? '' }}
                </td>
            </tr>
        </tbody>
    </table>

    
    <p>Ante usted con el debido respeto me dirijo y digo:</p>
    <p>Que deseando seguir estudios superiores en la UNAM, solicito se sirva aceptar mi inscripción al concurso de admisión 2020-I</p>
    <p><b>POR LO EXPUESTO:</b></p>
    <p>Pido a usted acceder a mi solicitud.</p>

    <table>
        <tr>
            <td height="100"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td width="120" align="center" class="bt">Firma del postulante</td>
            <td></td>
            <td width="60" align="center" class="bt">Indice Derecho</td>
            <td></td>
        </tr>
    </table>

    <p>Fecha de registro: {{ $data->dInscripRegistro }}</p>
    <footer>
        Universidad Nacional de Moquegua
    </footer>
</body>
</html>

