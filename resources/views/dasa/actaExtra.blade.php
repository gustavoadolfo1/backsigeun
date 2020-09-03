<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> ACTA DE NOTAS </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">

</head>
<style>
    @page { margin: 20px 20px; }
    *{
        font-size:11px;
        font-family:Lucida, sans-serif;
    }
    table{
        width:100%;
        border-collapse: collapse;
    }
    .border{
        padding:2px 4px;
        border:1px solid black
    }
    td{
        font-size:9px
    }
    .bg{
        background-color:#a4b0be;
        font-size:10px
    }
  </style>
<body>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px; text-align: center;height:40px" colspan="12">
                    <img src="./img/logo.png" style="height:55px; position: absolute; float: left">
                    UNIVERSIDAD NACIONAL DE MOQUEGUA
                </th>
            </tr>
            <tr style="font-size: 13px; text-align: center" >
                <th colspan="12">
                    <strong>VICEPRESIDENCIA ACAD&Eacute;MICA</strong>
                </th>
            </tr>
            <tr  style="font-size: 13px; text-align: center">
                <th colspan="12">
                    <strong>DIRECCI&Oacute;N DE ACTIVIDADES Y SERVICIOS ACAD&Eacute;MICOS</strong>
                </th>
            </tr>
            <tr >
                <th height="30" colspan="12">
                    <strong>ACTA DE NOTAS :</strong><span>EVALUACIÓN EXTRAORDINARIA</span>
                </th>
            </tr>
            
            <tr>
                <th colspan="2" class="border bg">DOCENTE</th>
                <td colspan="6" class="border">{{$data[0]->cDocenteNombre}}</td>
                <th colspan="3" class="border bg">SEMESTRE ACADÉMICO</th>
                <td class="border">{{$iControlCicloAcad}}</td>
            </tr>
            <tr>
                <th colspan="2" class="border bg">CURSO</th>
                <td colspan="4" class="border">{{ $data[0]->cCurricCursoDsc }}</td>
                <th class="border bg">CRÉDITOS</th>
                <td class="border">{{ $data[0]->iMatricDetCredCurso }}</td>
                <th colspan="3" class="border bg">CICLO DEL CURSO</th>
                <td class="border">{{ $data[0]->cMatricDetCicloCurso }}</td>
            </tr>
            <tr>
                <th colspan="2" class="border bg" >ESC. PROFESIONAL</th>
                <td colspan="4" class="border">{{ $data[0]->cCarreraDsc }}</td>
                <th class="border bg">SEDE</th>
                <td class="border">{{ $data[0]->filial }}</td>
                <th colspan="3" class="border bg"> SECCIÓN</th>
                <td class="border">{{ $data[0]->cSeccionDsc }}</td>
            </tr>
            <tr>
                <th height="20" colspan="12"></th>
            </tr>
            <tr>
                <th rowspan="2" class="border bg" align="center">N°</th>
                <th rowspan="2" class="border bg" align="center">CODIGO</th>
                <th rowspan="2" class="border bg" align="center" colspan="2">APELLIDOS Y NOMBRES</th>
                <th rowspan="2" class="border bg" align="center">ASIST. %</th>
                <th colspan="2" class="border bg" align="center">EVAL. REGULAR</th>
                <th colspan="2" class="border bg" align="center">EVAL. SUSTITUTORIO</th>
                <th colspan="3" class="border bg" align="center">EVALUACIÓN FINAL</th>
            </tr>
            <tr>
                <th class="border bg" align="center" >N°</th>
                <th class="border bg" align="center">LETRAS</th>
                <th class="border bg" align="center">N°</th>
                <th class="border bg" align="center">LETRAS</th>
                <th class="border bg" align="center">N°</th>
                <th class="border bg" align="center">LETRAS</th>
                <th class="border bg" align="center">APROB. / DESAPR.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key=>$d)
                <tr>
                    <td class="border" align="center">{{ $key + 1 }}</td>
                    <td class="border" align="center">{{ $d->cMatricCodUniv }}</td>
                    <td class="border" colspan="2">{{ $d->cEstudianteNombre }}</td>
                    <td class="border" align="center">{{ $d->iMatricDetAsistEst }}</td>
                    <td class="border" align="center">{{ $d->nMatricDetPF }}</td>
                    <td class="border" align="center">{{ $d->cMatricDetLPF }}</td>
                    <td class="border" align="center">{{ $d->nMatricDetAplaz ?? '-' }}</td>
                    <td class="border" align="center">{{ $d->cMatricDetLaplaz ?? '-' }}</td>
                    <td class="border" align="center">{{ $d->nMatricDetEF }}</td>
                    <td class="border" align="center">{{ $d->cMatricDetLEF }}</td>
                    <td class="border" align="center">{{ $d->cMatricDetOEF }}</td>
                </tr>
            
            @endforeach
        </tbody>
    </table>
    <p><b>{{ $d->cMatricDetDocuAe }}</b></p>
    <table style="width:220px">
        <tr>
            <td height="50"></td>
        </tr>
        <tr>
            <td align="center" style="border-top: 1px solid black;padding:4px 8px">{{$data[0]->cDocenteNombre}}</td>
        </tr>
        <tr>
            <td align="center">DOCENTE ORDINARIO</td>
        </tr>
    </table>
    <br>
</body>
</body>
</html>
