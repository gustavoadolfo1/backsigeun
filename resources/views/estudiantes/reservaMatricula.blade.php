<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <style>
        body {
            font-size: 12px;
        }

        table thead tr {
            background-color: #F9F9F9;
        }
        table {
            border-collapse: collapse;
        }
        .tabla_interna th {
            background-color: gray;
        }
        .tablita {
            margin-right: 15px;
            position: absolute;
        }
        .tablita tr {
            font-size: 11px; text-align: center; 
        }
    </style>
</head>
<body>
    <img src="./img/logo.png" id="img-logo" style="height:70px; position: relative; float: left;">
    <table align="center">
        <tr style="font-size: 24px; text-align: center">
            <th><strong>UNIVERSIDAD NACIONAL DE MOQUEGUA</strong></th>
        </tr>
        <tr style="font-size: 13px; text-align: center">
            <th><strong>VICEPRESIDENCIA ACAD&Eacute;MICA</strong>
            </th>
        </tr>
        <tr  style="font-size: 13px; text-align: center">
            <th><strong>DIRECCI&Oacute;N DE ACTIVIDADES Y SERVICIOS ACAD&Eacute;MICOS</strong>
            </th>
        </tr>
        <tr style="font-size: 13px; text-align: center; margin-top:10px">
            <th style="padding-left: 155px; padding-top: 15px"><?php echo DNS1D::getBarcodeHTML($ficha->cEstudCodUniv, "EAN13")?></th>
        </tr>
        <tr  style="font-size: 13px; text-align: center">
            <td>{{$ficha->cEstudCodUniv}}</td>
        </tr>
    </table>
    <br>
    <p align="left"><strong>RESERVA DE MATRÍCULA / DATOS DE MATRÍCULA</strong></p>

    <table border="1" class="tabla_interna" style="font-family: Calibri Light; width: 100%">
        <thead>
            <tr style="font-size: 10px; ">
                <th style="background-color: #dfdfdf" ><strong>&nbsp;&nbsp;CODIGO/DNI&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp; {{$ficha->cEstudCodUniv}} / {{$ficha->cPersDocumento}} </td>
                <th style="background-color: #dfdfdf" ><strong>&nbsp;&nbsp;SEMESTRE ACAD&Eacute;MICO&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;&nbsp;{{ $ficha->iControlCicloAcad }}</td>
                
            </tr>
            <tr  style="font-size: 10px; ">
                <th style="background-color: #dfdfdf" ><strong>&nbsp;&nbsp;ESTUDIANTE&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp;{{ $ficha->cPersPaterno . ' ' . $ficha->cPersMaterno . ', ' . $ficha->cPersNombre }}</td>
                <th style="background-color: #dfdfdf"><strong>&nbsp;&nbsp;CURRICULA&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;&nbsp;{{ $ficha->cCurricAnio }}</td>
                
            </tr>
            <tr  style="font-size: 10px; ">
                <th style="background-color: #dfdfdf" ><strong>&nbsp;&nbsp;CARRERA PROFESIONAL&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp; {{ $ficha->cCarreraDsc }}</td>
                <th style="background-color: #dfdfdf"><strong>&nbsp;&nbsp;REGIMEN&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;&nbsp;FLEXIBLE</td>
                
            </tr>
            <tr style="font-size: 10px; ">
                <th style="background-color: #dfdfdf"><strong>&nbsp;&nbsp;SEDE/LUGAR&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp;{{ $ficha->cFilSigla }}</td>
                <th style="background-color: #dfdfdf"><strong>&nbsp;&nbsp;FECHA&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;{{ $ficha->dMatricFecha }}&nbsp;</td>
            </tr>
            <tr style="font-size: 10px; ">
                <th style="background-color: #dfdfdf"><strong>&nbsp;&nbsp;TOTAL CR&Eacute;DITOS&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp;{{ $ficha->nMatricTotalCred }}</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
    </table>
    <br><br>
    <h3>RESERVA DE MATRÍCULA</h3>
    <p>TIPO DE FICHA: RESERVA</p>
    <p>{{ $ficha->cMatricObs }}</p>
    @if ($ficha->iMatricEstado == 1)
        <p>ESTADO: VIGENTE</p>
    @else
        <p>ESTADO: INACTIVO</p>
    @endif



    <br><br><br><br><br><br><br>

    <table style="font-family: Calibri Light;">
        <tr>
            <td>__________________________________</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>__________________________________</td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: center">{{ $ficha->cPersPaterno . ' ' . $ficha->cPersMaterno . ', ' . $ficha->cPersNombre }}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td style="font-size: 10px; text-align: center">DASA / URC</td>
        </tr>
    </table>

<br><br><br>
    <p style="font-size: 10px">URC / {{ date('d-m-Y') }}</p>
    
</body>
</body>
</html>