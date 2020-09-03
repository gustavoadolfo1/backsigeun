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
            position:relative;
            width:2.5cm;
            height:3cm;
            border:1px solid black;
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
    </style>
</head>

<body>
   <table style="margin:0px">
        <tr>
            <td rowspan="2"  align="center" width="120">
                <div class="foto"></div><br>
            </td>
            <td  align="center">
                <h4>UNIVERSIDAD NACIONAL DE MOQUEGUA <br> CENTRO PREUNIVERSITARIO</h4>
            </td>
            <td  width="100" align="center">
                <div class="box">
                    <div class="box-head">
                        N° DE EXPEDIENTE
                    </div>
                    <div class="box-body">
                        {{ $data->cEstudServNumExped }}
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <h5>FORMULARIO DE INSCRIPCIÓN</h5>
            </td>
            <td></td>
        </tr>
   </table>
   <p><span>CICLO CEPRE : 2020 - 1</span></p>
   <strong>
    I. DATOS GENERALES DE POSTULANTE
   </strong>
    <table border="1">
        <tr>
            <th class='bg-color' width="70">DNI</th>
            <td>{{ $data->cPersDocumento }}</td>
            <th class='bg-color' width="70">CÓDIGO</th>
            <td width="80">{{ $data->cEstudServCod }}</td>
        </tr>
        <tr>
            <th class='bg-color'>NOMBRES</th>
            <td>{{ $data->cPersNombre }} {{ $data->cPersPaterno }} {{ $data->cPersMaterno }}</td>
            <th class='bg-color'>SEXO</th>
            <td>{{ $data->cSexo }}</td>
        </tr>
        <tr>
            <th class='bg-color'>E-MAIL</th>
            <td>{{ $data->cPreEmail }}</td>
            <th class='bg-color'>F.NAC </th>
            <td>{{ $data->dPersNacimiento }}</td>
        </tr>
        <tr>
            <th class='bg-color'>DIRECCIÓN</th>
            <td>{{ $data->cPreDireccion }}</td>
            <th class='bg-color'>TELÉFONO</th>
            <td>{{ $data->cPreTelefono }}</td>
        </tr>
   </table>
   <strong>
   II. CARRERA PROFESIONAL
   </strong>
    <table border="1" >
        <tbody>
            <tr>
                <th class='bg-color'  width="120" >CARRERA</th>
                <td>{{ $data->cCarrera }}</td>
                <th class='bg-color'  width="70" >SEDE</th>
                <td>{{ $data->cFilial }}</td>
            </tr>
            <tr>
                <th class='bg-color'>LUGAR DE ESTUDIOS</th>
                <td>{{ $data->cFilial }}</td>
                <th class='bg-color'>TURNO</th>
                <td>{{ $data->cTurnosDsc }}</td>
            </tr>
        </tbody>
        
   </table>
   <strong>
   III. PROCEDENCIA DE LA INSTITUCIÓN EDUCATIVA
   </strong>
    <table border="1">
        <tbody>
            <tr>
                <th class='bg-color'  width="90">COLEGIO</th>
                <td>{{ $data->cColegio }}</td>
                <th class='bg-color'  width="70">TIPO</th>
                <td  width="100">{{ $data->cGestionDsc }}</td>
            </tr>
            <tr>
                <th class='bg-color'>LUGAR DEL COLEGIO</th>
                <td>{{ $data->cColeDireccion }}</td>
                <th class='bg-color'>EGRESO</th>
                <td>{{ $data->iPreEgreso }}</td>
            </tr>
        </tbody>
        
   </table>
   <strong>
        IV. DATOS DE PADRE O APODERADO
   </strong>
    <table border="1">
        <tbody>
            <tr>
                <th class='bg-color' width="120">PADRE O APODERADO</th>
                <td>{{ $data->cPreApoderado }}</td>
            </tr>
            <tr>
                <th class='bg-color'>DIRECCIÓN</th>
                <td>{{ $data->cPreDireccionAp }}</td>
            </tr>
            <tr>
                <th class='bg-color'>TELÉFONO</th>
                <td>{{ $data->cPreTelefonoAp }}</td>
            </tr>
        </tbody>
   </table>
    <table border="1">
        <thead>
            <tr>
                <th class='bg-color' colspan="4">LLENADO POR EL CENTRO PREUNIVERSITARIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class='bg-color' width="120">RECIBO 01 (MATRICULA)</th>
                <td>{{ $data->matricula }}</td>
                <th class='bg-color' width="80">IMPORTE S/. :</th>
                <td>{{ $data->matriculad }}</td>
            </tr>
            <tr>
                <th class='bg-color'>RECIBO 02 (1RA PENSIÓN)</th>
                <td>{{ $data->pago1 }}</td>
                <th class='bg-color'>IMPORTE S/. :</th>
                <td>{{ $data->pago1d }}</td>
            </tr>
            <tr>
                <th class='bg-color'>RECIBO 03 (2DA PENSIÓN)</th>
                <td>{{ $data->pago2 }}</td>
                <th class='bg-color'>IMPORTE S/. :</th>
                <td>{{ $data->pago2d }}</td>
            </tr>
        </tbody>
   </table>
    <h4 align="center">Declaración Jurada</h4><br>
    <span>DECLARO BAJO JURAMENTO</span>
    <ul>
        <li>Que los datos consignados en el presente documento son exactos y mre pertenecen, en caso contrario me someto a las disposiciones legales vigentes.</li>
        <li>Conozco y acepto las disposiciones del reglamento general del Centro Preuniversitario de la Universidad Nacional de Moquegua.</li>
    </ul>
    <p>Para lo cual firmo la presente declaración jurada</p>
    <table>
        <tr>
            <td height="100"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td width="60" align="center" class="bt">Indice Derecho</td>
            <td></td>
            <td width="120" align="center" class="bt">{{ $data->cPersNombre }} {{ $data->cPersPaterno }} {{ $data->cPersMaterno }}</td>
            <td></td>
            <td width="120" align="center" class="bt">Apoderado</td>
        </tr>
    </table>
</body>
</html>

