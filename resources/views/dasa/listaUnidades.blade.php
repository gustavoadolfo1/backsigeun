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
                <th style="font-size: 18px; text-align: center;height:40px" colspan="8">
                    <img src="./img/logo.png" style="height:55px; position: absolute; float: left">
                    UNIVERSIDAD NACIONAL DE MOQUEGUA
                </th>
            </tr>
            <tr style="font-size: 13px; text-align: center" >
                <th colspan="8">
                    <strong>VICEPRESIDENCIA ACAD&Eacute;MICA</strong>
                </th>
            </tr>
            <tr  style="font-size: 13px; text-align: center">
                <th colspan="8">
                    <strong>DIRECCI&Oacute;N DE ACTIVIDADES Y SERVICIOS ACAD&Eacute;MICOS</strong>
                </th>
            </tr>
            <tr >
                <th height="30" colspan="8">
                    <strong>Reporte de Unidades Cerradas</span>
                </th>
            </tr>
            @if($band ==1)
            <tr>
                <th colspan="2" class="border bg">DOCENTE</th>
                <td colspan="4" class="border">{{$data[0]->Docente}}</td>
                <th class="border bg">SEMESTRE ACADÉMICO</th>
                <td class="border">{{$data[0]->Ciclo_Academico}}</td>
            </tr>
            <tr>
                <th colspan="2" class="border bg">CURSO</th>
                <td colspan="3" class="border">{{ $data[0]->Curso }}</td>
                
                <th colspan="2" class="border bg">Plan</th>
                <td class="border">{{ $data[0]->Plan }}</td>
            </tr>
            <tr>
                <th colspan="2" class="border bg" >ESC. PROFESIONAL</th>
                <td colspan="2" class="border">{{ $data[0]->Carrera }}</td>
                <th class="border bg">SEDE</th>
                <td class="border">{{ $data[0]->Sede }}</td>
                <th  class="border bg"> SECCIÓN</th>
                <td class="border">{{ $data[0]->Seccion }}</td>
            </tr>
            <tr>
                <th height="20" colspan="8"></th>
            </tr>
            @endif
            @if($band == 0)
                <tr>
                    <th class="border bg " colspan="2">SEDE</th>
                    <td class="border"  colspan="6">{{ $data[0]->Sede }}</td>
                </tr>
            @endif
            <tr>
                <th class="border bg">DNI</th>
                <th class="border bg">Docente</th>
                <th class="border bg">Plan</th>
                <th class="border bg">Código <br> Curso</th>
                <th class="border bg">Curso</th>
                <th class="border bg">Sección</th>
                <th class="border bg">Total <br> Unidades</th>
                <th class="border bg">Unidades <br> Cerradas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key=>$row)
            <tr>
                <td class="text-center border">{{ $row->DNI }}</td>
                <td class="border">{{ $row->Docente }}</td>
                <th class="text-center border">{{ $row->Plan}}</th>
                <td class="border">{{ $row->Codigo_Curso }}</td>
                <td class="border">{{ $row->Curso }}</td>
                <td class="text-center border">{{ $row->Seccion }}</td>
                <td class="text-center border">{{ $row->Total_Unidades }}</td>
                <td class="text-center border">{{ $row->Unidades_Cerradas }}</td>
            </tr>
            
            @endforeach
        </tbody>
    </table>
</body>
</body>
</html>
