<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FICHA MATRICULA</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

  
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
    </table>
    <br>

        <p align="left"><strong>FICHA DE MATRICULA</strong></p>

        <table border="1" style="font-family: Calibri Light">
                <thead>
                    <tr  style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;CODIGO/DNI&nbsp;&nbsp;</strong></th>
                        <td width="200px">&nbsp;&nbsp; {{$estudiante->cEstudCodUniv}} / {{$estudiante->cPersDocumento}} </td>
                        <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;SEMESTRE ACAD&Eacute;MICO&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;&nbsp;{{ $detalles[0]->iControlCicloAcad }}</td>
                        
                    </tr>
                    <tr  style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;ESTUDIANTE&nbsp;&nbsp;</strong></th>
                        <td width="200px">&nbsp;&nbsp;{{ $estudiante->cPersPaterno . ' ' . $estudiante->cPersMaterno . ', ' . $estudiante->cPersNombre }}</td>
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;CURRICULA&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;&nbsp;{{ $detalles[0]->cCurricAnio }}</td>
                        
                    </tr>
                    <tr  style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;CARRERA PROFESIONAL&nbsp;&nbsp;</strong></th>
                        <td width="200px">&nbsp;&nbsp; {{ $estudiante->cCarreraDsc }}</td>
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;REGIMEN&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;&nbsp;FLEXIBLE</td>
                        
                    </tr>
                    <tr style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;SEDE/LUGAR&nbsp;&nbsp;</strong></th>
                        <td width="200px">&nbsp;&nbsp;{{ $estudiante->cFilSigla }}</td>
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;FECHA&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;{{ $detalles[0]->dMatricFecha }}&nbsp;</td>

                    </tr>
                    <tr style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;TOTAL CREDITOS&nbsp;&nbsp;</strong></th>
                        <td width="200px">&nbsp;&nbsp;{{ $detalles[0]->nMatricTotalCred }}</td>
                        <th ><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;&nbsp;</td>

                    </tr>
                </thead>
                
            </table>
            <br>
        <p align="left"><strong>DETALLE DE CURSOS</strong></p>
        <div>
            <table border="1" >
                <thead>
                    <tr style="background-color: #F9F9F9;font-size: 13px; text-align: center; ">
                        
                        <td><strong>&nbsp;CODIGO&nbsp;</strong></td>
                        <td width="240px"><strong>NOMBRE DEL CURSO</strong></td>
                        <td><strong>&nbsp;SECCION.&nbsp;</strong></td>
                        <td><strong>&nbsp;CICLO&nbsp;</strong></td>
                        <td><strong>&nbsp;CREDITOS&nbsp;</strong></td>
                        <td><strong>&nbsp;HT&nbsp;</strong></td>
                        <td><strong>&nbsp;HP&nbsp;</strong></td>
                        <td><strong>&nbsp;MATRICULA&nbsp;</strong></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $detalles as $detalle )
                    <tr style="font-size: 10px; text-align: center;">
                        <td>{{ $detalle->cCurricCursoCod }}</td>
                        <td style="text-align: left;">{{ $detalle->cCurricCursoDsc }}</td>
                        <td>{{ $detalle->cMatricDetSeccion }}</td>
                        <td>{{ $detalle->cMatricDetCicloCurso }}</td>
                        <td>{{ $detalle->iMatricDetCredCurso }}</td>
                        <td>{{ $detalle->iMatricDetHrsTcurso }}</td>
                        <td>{{ $detalle->iMatricDetHrsPcurso }}</td>
                        <td>{{ $detalle->n_regular + $detalle->n_verano . " ( R: " . $detalle->n_regular . " V: " . $detalle->n_verano . " )" }}</td>

                    </tr>
                   @endforeach
                </tbody>
            </table>

        </div>
    
<br>
<br><br><br>
<table style="font-family: Calibri Light;">
    <tr>
        <td>__________________________________</td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>__________________________________</td>
    </tr>
    <tr>
        <td style="font-size: 11px; text-align: center">{{ $estudiante->cPersPaterno . ' ' . $estudiante->cPersMaterno . ', ' . $estudiante->cPersNombre }}</td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td style="font-size: 11px; text-align: center">DASA / URC</td>
    </tr>
</table>

<br><br><br>
    <p style="font-size: 11px">URC / {{ date('d-m-Y') }}</p>
   
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>  


</body>

</html>