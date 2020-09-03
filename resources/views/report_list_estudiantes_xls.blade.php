<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LISTADO OFICAIL DE ALUMNOS MATRICULADOS</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <style>
        table thead tr {
            background-color: #F9F9F9;
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
    </table>
    <br><br>
    <p align="left"><strong>LISTADO OFICIAL DE ALUMNOS MATRICULADOS</strong></p>

    <table border="1" style="font-family: Calibri Light">
        <thead>
            <tr  style="font-size: 10px; ">
                <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;CODIGO/DNI&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp; {{$list->Codigo_Estudiante}}</td>
                <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;SEMESTRE ACAD&Eacute;MICO&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;&nbsp;</td>

            </tr>
            <tr  style="font-size: 10px; ">
                <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;ESTUDIANTE&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp;</td>
                <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;CURRICULA&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;&nbsp;</td>

            </tr>
            <tr  style="font-size: 10px; ">
                <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;CARRERA PROFESIONAL&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp; </td>
                <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;REGIMEN&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;&nbsp;FLEXIBLE</td>

            </tr>
            <tr style="font-size: 10px; ">
                <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;SEDE/LUGAR&nbsp;&nbsp;</strong></th>
                <td width="200px">&nbsp;&nbsp;</td>
                <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;FECHA&nbsp;&nbsp;</strong></th>
                <td width="100px">&nbsp;&nbsp;</td>
            </tr>
        </thead>

    </table>
    <br>
    <p align="left"><strong>DETALLE DE CURSOS</strong></p>

    <table border="1">
        <thead>
            <tr style="background-color: #F9F9F9;font-size: 11px; text-align: center; ">

                <td><strong>&nbsp;CODIGO&nbsp;</strong></td>
                <td width="240px"><strong>NOMBRE DEL CURSO</strong></td>
                <td><strong>&nbsp;SECCION&nbsp;</strong></td>
                <td><strong>&nbsp;CICLO&nbsp;</strong></td>
                <td><strong>&nbsp;CREDITOS&nbsp;</strong></td>
                <td><strong>&nbsp;NOTA&nbsp;</strong></td>
                <td><strong>&nbsp;PUNTAJE&nbsp;</strong></td>
                <td><strong>&nbsp;ASISTENCIA&nbsp;</strong></td>
                <td><strong>&nbsp;TIPO&nbsp;</strong></td>
                <td><strong>&nbsp;VEZ&nbsp;</strong></td>
            </tr>
        </thead>
        <tbody>
            <!-- <?php $puntaje = 0; $cursosAprobados = 0; $creditosInscritos = 0; $creditosAprobados = 0; ?>
            @foreach( $detalles as $detalle )
            <?php

                $puntaje += $detalle->iMatricDetCredCurso * $detalle->nMatricDetNotaCurso;
                $creditosInscritos += $detalle->iMatricDetCredCurso;

                if ($detalle->nMatricDetNotaCurso >= 11){
                    $creditosAprobados += $detalle->iMatricDetCredCurso; $cursosAprobados += 1;
                }
            ?> -->
            <tr style="font-size: 10px; text-align: center;">
                <td></td>
                <td style="text-align: left;"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            @endforeach
            <tr style="font-size: 10px; text-align: center;">
                <td colspan="5"></td>
                <td>Puntaje</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br>

    <table>
        <tr>
            <td>
                <table border="1" class="tablita">
                    <thead>
                        <tr>
                            <td colspan="2"><strong>&nbsp;CURSOS&nbsp;</strong></td>
                        </tr>
                        <tr>
                            <td>&nbsp;Inscritos&nbsp;</td>
                            <td>&nbsp;Aprobados&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table border="1" class="tablita">
                    <thead>
                        <tr>
                            <td colspan="2"><strong>&nbsp;CRÉDITOS&nbsp;</strong></td>
                        </tr>
                        <tr>
                            <td>&nbsp;Inscritos&nbsp;</td>
                            <td>&nbsp;Aprobados&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table border="1" class="tablita">
                    <thead>
                        <tr>
                            <td><strong>&nbsp;PROMEDIO PONDERADO&nbsp;</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <br><br><br><br><br><br><br>

    <table style="font-family: Calibri Light;">
        <tr>
            <td>__________________________________</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>__________________________________</td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: center">{{ $estudiante->cPersPaterno . ' ' . $estudiante->cPersMaterno . ', ' . $estudiante->cPersNombre }}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td style="font-size: 10px; text-align: center">DASA / URC</td>
        </tr>
    </table>

<br><br><br>
    <p style="font-size: 10px">URC / </p>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>


</body>

</html>